<?php
require_once 'config.php';

header('Content-Type: application/json');
$headers = getallheaders();
$initData = $headers['Tg-Init-Data'] ?? '';
$tgUser = validateTelegramAuth($initData, $bot_token);

if (!$tgUser) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$uid = (string)$tgUser['id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE tg_uid = ? FOR UPDATE");
    $stmt->execute([$uid]);
    $user = $stmt->fetch();

    if (!$user) {
        $stmt = $pdo->prepare("INSERT INTO users (tg_uid, tg_username, tg_name) VALUES (?, ?, ?)");
        $stmt->execute([$uid, $tgUser['username'] ?? '', $tgUser['first_name'] ?? 'User']);
        $user = ['tg_uid' => $uid, 'balance' => 0.0000, 'is_blocked' => 0];
        
        $ref_by = $_GET['startapp'] ?? '';
        if ($ref_by && $ref_by !== $uid) {
            $checkRef = $pdo->prepare("SELECT tg_uid FROM users WHERE tg_uid = ?");
            $checkRef->execute([$ref_by]);
            if ($checkRef->fetch()) {
                $pdo->prepare("INSERT IGNORE INTO referrals (referrer_uid, referred_uid, status) VALUES (?, ?, 'pending')")
                    ->execute([$ref_by, $uid]);
            }
        }
    } else if ($user['is_blocked']) {
        die(json_encode(['error' => 'Account is blocked']));
    }

    if ($action === 'init') {
        $token = generateCsrfToken($pdo, $uid);
        
        $adCountStmt = $pdo->prepare("SELECT COUNT(*) as count FROM ad_views WHERE tg_uid = ? AND DATE(created_at) = CURDATE()");
        $adCountStmt->execute([$uid]);
        $adCount = $adCountStmt->fetch()['count'];

        $taskStmt = $pdo->prepare("SELECT task_id FROM tasks WHERE tg_uid = ?");
        $taskStmt->execute([$uid]);
        $tasks = $taskStmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo json_encode([
            'balance' => $user['balance'], 
            'csrf_token' => $token,
            'ad_count' => $adCount,
            'tasks_completed' => $tasks
        ]);
    }
    
    elseif ($action === 'verifyAd') {
        checkRateLimit($pdo, $uid, 'verifyAd', 50, 86400); // Max 50 per day
        $token = $_POST['csrf_token'] ?? '';
        validateCsrfToken($pdo, $uid, $token);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM ad_views WHERE tg_uid = ? AND DATE(created_at) = CURDATE()");
        $stmt->execute([$uid]);
        if ($stmt->fetch()['count'] >= 50) {
            die(json_encode(['error' => 'Daily ad limit reached']));
        }
        
        $pdo->prepare("INSERT INTO ad_views (tg_uid, reward) VALUES (?, 0.002)")->execute([$uid]);
        $pdo->prepare("UPDATE users SET balance = balance + 0.002 WHERE tg_uid = ?")->execute([$uid]);
        
        // Check pending referral validation
        $refStmt = $pdo->prepare("SELECT id, referrer_uid FROM referrals WHERE referred_uid = ? AND status = 'pending'");
        $refStmt->execute([$uid]);
        $ref = $refStmt->fetch();
        if ($ref) {
            $pdo->prepare("UPDATE referrals SET status = 'approved' WHERE id = ?")->execute([$ref['id']]);
            $pdo->prepare("UPDATE users SET balance = balance + 0.02 WHERE tg_uid = ?")->execute([$ref['referrer_uid']]);
        }
        
        echo json_encode(['success' => true, 'reward' => 0.002, 'new_csrf' => generateCsrfToken($pdo, $uid)]);
    }

    elseif ($action === 'verifyJoin') {
        checkRateLimit($pdo, $uid, 'verifyJoin', 1, 10);
        $token = $_POST['csrf_token'] ?? '';
        validateCsrfToken($pdo, $uid, $token);
        
        try {
            $pdo->prepare("INSERT INTO tasks (tg_uid, task_id, reward) VALUES (?, 'join_channel', 0.10)")->execute([$uid]);
            $pdo->prepare("UPDATE users SET balance = balance + 0.10 WHERE tg_uid = ?")->execute([$uid]);
            
            $refStmt = $pdo->prepare("SELECT id, referrer_uid FROM referrals WHERE referred_uid = ? AND status = 'pending'");
            $refStmt->execute([$uid]);
            $ref = $refStmt->fetch();
            if ($ref) {
                $pdo->prepare("UPDATE referrals SET status = 'approved' WHERE id = ?")->execute([$ref['id']]);
                $pdo->prepare("UPDATE users SET balance = balance + 0.02 WHERE tg_uid = ?")->execute([$ref['referrer_uid']]);
            }
            echo json_encode(['success' => true, 'reward' => 0.10, 'new_csrf' => generateCsrfToken($pdo, $uid)]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Task already completed']);
        }
    }

    elseif ($action === 'withdraw') {
        checkRateLimit($pdo, $uid, 'withdraw', 1, 60);
        $token = $_POST['csrf_token'] ?? '';
        validateCsrfToken($pdo, $uid, $token);
        
        $amount = (float)$_POST['amount'];
        $method = $_POST['method'];
        $details = $_POST['details'];
        $network = $_POST['network'] ?? null;
        
        if ($amount < 7.00) die(json_encode(['error' => 'Minimum withdrawal is 7₼']));
        if ($user['balance'] < $amount) die(json_encode(['error' => 'Insufficient balance']));
        
        $pdo->prepare("UPDATE users SET balance = balance - ? WHERE tg_uid = ?")->execute([$amount, $uid]);
        $pdo->prepare("INSERT INTO withdrawals (tg_uid, amount, method, network, details) VALUES (?, ?, ?, ?, ?)")
            ->execute([$uid, $amount, $method, $network, $details]);
            
        echo json_encode(['success' => true]);
    }
    
    elseif ($action === 'getHistory') {
        $stmt = $pdo->prepare("SELECT * FROM withdrawals WHERE tg_uid = ? ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$uid]);
        echo json_encode(['history' => $stmt->fetchAll()]);
    }

    elseif ($action === 'getReferrals') {
        $stmt = $pdo->prepare("SELECT r.status, u.tg_name, u.tg_username FROM referrals r JOIN users u ON r.referred_uid = u.tg_uid WHERE r.referrer_uid = ?");
        $stmt->execute([$uid]);
        $referrals = $stmt->fetchAll();
        $approved = count(array_filter($referrals, fn($r) => $r['status'] === 'approved'));
        echo json_encode(['referrals' => $referrals, 'total' => count($referrals), 'approved' => $approved]);
    }

    // ADMIN ENDPOINTS
    elseif (strpos($action, 'admin_') === 0) {
        if ($uid !== '5461064199') die(json_encode(['error' => 'Forbidden']));
        
        if ($action === 'admin_check') {
            echo json_encode(['isAdmin' => true]);
        }
        elseif ($action === 'admin_stats') {
            $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $totalWithdrawals = $pdo->query("SELECT COUNT(*) FROM withdrawals WHERE status='pending'")->fetchColumn();
            echo json_encode(['total_users' => $totalUsers, 'pending_withdrawals' => $totalWithdrawals]);
        }
        elseif ($action === 'admin_search') {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE tg_uid = ? OR tg_username = ?");
            $stmt->execute([$_POST['query'], $_POST['query']]);
            echo json_encode(['user' => $stmt->fetch()]);
        }
        elseif ($action === 'admin_set_balance') {
            $token = $_POST['csrf_token'] ?? '';
            validateCsrfToken($pdo, $uid, $token);
            $target = $_POST['target_uid'];
            $newBal = (float)$_POST['balance'];
            if ($newBal < 0) die(json_encode(['error' => 'Balance cannot be negative']));
            
            $pdo->prepare("UPDATE users SET balance = ? WHERE tg_uid = ?")->execute([$newBal, $target]);
            $pdo->prepare("INSERT INTO admin_logs (admin_uid, action, target_uid, amount) VALUES (?, 'set_balance', ?, ?)")->execute([$uid, $target, $newBal]);
            echo json_encode(['success' => true]);
        }
        elseif ($action === 'admin_process_withdrawal') {
            $token = $_POST['csrf_token'] ?? '';
            validateCsrfToken($pdo, $uid, $token);
            $wId = $_POST['withdrawal_id'];
            $status = $_POST['status']; // 'approved' or 'rejected'
            
            $wStmt = $pdo->prepare("SELECT * FROM withdrawals WHERE id = ? AND status = 'pending' FOR UPDATE");
            $wStmt->execute([$wId]);
            $w = $wStmt->fetch();
            if ($w) {
                $pdo->prepare("UPDATE withdrawals SET status = ? WHERE id = ?")->execute([$status, $wId]);
                if ($status === 'rejected') {
                    $pdo->prepare("UPDATE users SET balance = balance + ? WHERE tg_uid = ?")->execute([$w['amount'], $w['tg_uid']]);
                }
                $pdo->prepare("INSERT INTO admin_logs (admin_uid, action, target_uid, amount) VALUES (?, ?, ?, ?)")->execute([$uid, 'withdrawal_'.$status, $w['tg_uid'], $w['amount']]);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Withdrawal not found or already processed']);
            }
        }
    }
    
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => 'Server error']);
}
?>
