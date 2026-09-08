<?php
session_start();

$db_host = 'localhost';
$db_name = 'mplus';
$db_user = 'root';
$db_pass = '';
$bot_token = getenv('BOT_TOKEN') ?: 'ENTER_YOUR_BOT_TOKEN_HERE';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::ASSOC);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed']));
}

function validateTelegramAuth($initData, $botToken) {
    parse_str($initData, $data);
    if (!isset($data['hash'])) return false;
    $hash = $data['hash'];
    unset($data['hash']);
    ksort($data);
    $dataCheckString = [];
    foreach ($data as $key => $value) {
        $dataCheckString[] = $key . '=' . $value;
    }
    $dataCheckString = implode("\n", $dataCheckString);
    $secretKey = hash_hmac('sha256', $botToken, 'WebAppData', true);
    $calculatedHash = bin2hex(hash_hmac('sha256', $dataCheckString, $secretKey, true));
    if (hash_equals($calculatedHash, $hash)) {
        return json_decode($data['user'], true);
    }
    return false;
}

function checkRateLimit($pdo, $uid, $endpoint, $limit, $seconds) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_logs WHERE admin_uid = ? AND action = ? AND created_at > (NOW() - INTERVAL ? SECOND)");
    $stmt->execute([$uid, 'rate_limit_'.$endpoint, $seconds]);
    if ($stmt->fetch()['count'] >= $limit) {
        die(json_encode(['error' => 'Rate limit exceeded']));
    }
    $pdo->prepare("INSERT INTO admin_logs (admin_uid, action) VALUES (?, ?)")->execute([$uid, 'rate_limit_'.$endpoint]);
}

function generateCsrfToken($pdo, $uid) {
    $token = bin2hex(random_bytes(32));
    $stmt = $pdo->prepare("INSERT INTO csrf_tokens (token, tg_uid, expires_at) VALUES (?, ?, NOW() + INTERVAL 1 HOUR)");
    $stmt->execute([$token, $uid]);
    return $token;
}

function validateCsrfToken($pdo, $uid, $token) {
    $stmt = $pdo->prepare("SELECT * FROM csrf_tokens WHERE token = ? AND tg_uid = ? AND expires_at > NOW()");
    $stmt->execute([$token, $uid]);
    $result = $stmt->fetch();
    if ($result) {
        $pdo->prepare("DELETE FROM csrf_tokens WHERE token = ?")->execute([$token]);
        return true;
    }
    die(json_encode(['error' => 'Invalid or expired CSRF token']));
}
?>
