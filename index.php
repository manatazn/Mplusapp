<!DOCTYPE html>
<html lang="az">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,viewport-fit=cover" />
<title id="pageTitle">M Plus</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://telegram.org/js/telegram-web-app.js"></script>

<script>
if (window.Telegram && window.Telegram.WebApp) {
    window.Telegram.WebApp.ready();
    window.Telegram.WebApp.expand();
}
</script>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://sad.adsgram.ai/js/sad.min.js"></script>

<style>
:root {
  --bg-start: #F8FAFC; 
  --bg-end: #E2E8F0;   
  --card-bg: #FFFFFF; 
  --card-border: rgba(37, 99, 235, 0.15);
  --text-light: #FFFFFF;
  --text-dark: #0F172A;
  --text-muted: #64748B;
  --accent: #2563EB; 
  --accent-red: #EF4444; 
  --menu-bg: rgba(255, 255, 255, 0.95);
  --progress-bg: rgba(37, 99, 235, 0.1);
  --input-text: #0F172A; 
  --input-bg: #F1F5F9;
  --btn-gradient-start: #3B82F6; 
  --btn-gradient-end: #1D4ED8;   
  --btn-shadow: rgba(37, 99, 235, 0.3); 
}

body.dark-theme {
  --bg-start: #020617;
  --bg-end: #0F172A;   
  --card-bg: #1E293B;
  --card-border: rgba(59, 130, 246, 0.2);
  --text-light: #F8FAFC;
  --text-dark: #F1F5F9;
  --text-muted: #94A3B8;
  --accent: #3B82F6;
  --accent-red: #F87171;
  --menu-bg: rgba(30, 41, 59, 0.95);
  --progress-bg: rgba(59, 130, 246, 0.15);
  --input-text: #F8FAFC; 
  --input-bg: #0F172A;
  --btn-shadow: rgba(59, 130, 246, 0.25); 
}

*{box-sizing:border-box;margin:0;padding:0;}
body{
  font-family:'Inter',sans-serif;
  background:linear-gradient(135deg,var(--bg-start),var(--bg-end));
  background-attachment: fixed;
  color:var(--text-dark);
  min-height:var(--tg-viewport-stable-height, 100vh);
  user-select:none;
  padding-bottom: 90px; 
}

.container{max-width:480px;margin:auto;padding:40px 20px 20px;} 
.page{display:none;animation:fadeIn .4s;}
.page.active{display:block;}
@keyframes fadeIn{from{opacity:0;transform:translateY(15px) scale(0.98);}to{opacity:1;transform:translateY(0) scale(1);}}

.balance-card, .ad-card, .profile-card{
  background:var(--card-bg);
  border-radius:30px;
  padding:30px 20px;
  margin-bottom:20px;
  box-shadow:0 10px 40px rgba(0, 0, 0, 0.05);
  text-align:center;
  border:1px solid var(--card-border);
}
.balance-card h3{font-size:14px;color:var(--text-muted);margin-bottom:15px;text-transform:uppercase; font-weight: 800;}
.balances-wrapper { display: flex; flex-direction: column; align-items: center; justify-content: center; }
.amount-wrap { display: flex; align-items: center; justify-content: center; gap: 8px; }
.amount{font-size:42px;font-weight:900;color:var(--text-dark);}
.currency{font-size:26px;font-weight:900;color:var(--accent);}

.method-list{display:grid; grid-template-columns: 1fr 1fr; gap: 15px; margin:20px 0;}
.method{padding:25px 15px;border-radius:24px;background:var(--card-bg); color:var(--text-dark);text-align:center;font-weight:800;cursor:pointer;display:flex;flex-direction: column;align-items: center; border: 1px solid var(--card-border);}
.method-logo{width: 60px; height: 60px; margin-bottom: 15px;border-radius: 18px; object-fit: cover;}
.min-withdraw-label {margin-top: 10px; font-size: 11px;font-weight: 800;color: var(--accent-red);background: rgba(239, 68, 68, 0.1);padding: 6px 12px;border-radius: 12px;}

.task-list { display: flex; flex-direction: column; gap: 15px; margin-bottom: 20px; }
.task-item { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 26px; padding: 25px 20px; text-align: left;}
.task-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.task-title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 12px; color: var(--text-dark); }
.task-icon { display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 14px; color: white; background: linear-gradient(135deg, var(--btn-gradient-start), var(--btn-gradient-end));}
.task-reward-badge { background: var(--progress-bg); color: var(--accent); padding: 8px 16px; border-radius: 14px; font-weight: 900;}
.task-desc { font-size: 14px; color: var(--text-muted); font-weight: 600; margin-bottom: 20px;}
.task-btn { width: 100%; padding: 18px; font-size: 16px; font-weight: 800; border: none; border-radius: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; background: linear-gradient(135deg, var(--btn-gradient-start), var(--btn-gradient-end)); color: white;}
.task-btn.completed { background: var(--input-bg); color: var(--accent-red); cursor: not-allowed;}

.ad-card{padding: 45px 25px 30px 25px;}
.stats{display:flex;justify-content:space-between;margin-top:30px; gap: 15px;}
.stat{background:var(--input-bg);border-radius:20px;padding:20px 15px; flex: 1; border: 1px solid var(--card-border);}
.stat span{display:block;font-size:26px;font-weight:900;color:var(--text-dark);}
.stat label{font-size:12px;color:var(--text-muted);font-weight: 800; text-transform: uppercase;}
.progress{height:14px;background:var(--input-bg);border-radius:14px;overflow:hidden;margin-top:30px; border: 1px solid var(--card-border);}
.progress-fill{height:100%;background:linear-gradient(90deg, var(--accent), var(--accent-red));width:0%;} 
.ad-btn{width:100%;margin-top:30px;padding:20px;font-size:18px;font-weight:900;border:none;border-radius:24px;color:var(--text-light);cursor:pointer;background:linear-gradient(135deg,var(--btn-gradient-start),var(--btn-gradient-end));}

.menu{position:fixed;bottom:25px;left:50%;transform:translateX(-50%);width:94%;max-width:460px; display:flex;justify-content:space-between;background:var(--menu-bg);padding:10px; border-radius:36px;box-shadow:0 25px 50px rgba(0,0,0,.1);z-index: 999;border: 1px solid var(--card-border);}
.menu button{flex:1;margin:0 2px; padding: 12px 0;border:none;border-radius:28px;background:transparent;color:var(--text-muted);cursor: pointer;display: flex;flex-direction: column;align-items: center;justify-content: center;}
.menu button .nav-icon { width: 22px; height: 22px; margin-bottom: 6px; }
.menu button .nav-text { font-size: 10px; font-weight: 800;}
.menu button.active{background:linear-gradient(135deg, var(--btn-gradient-start), var(--btn-gradient-end));color: white;}

.profile-header-wrap { display: flex; align-items: center; justify-content: center; flex-direction: column; margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid var(--card-border);}
.profile-avatar { width: 85px; height: 85px; background: linear-gradient(135deg, var(--btn-gradient-start), var(--accent-red)); border-radius: 25px; display: flex; align-items: center; justify-content: center; color: #fff; border: 3px solid var(--card-bg); margin-bottom: 15px;}
.profile-name { font-size: 24px; font-weight: 900; color: var(--text-dark); margin-bottom: 6px;}
.profile-id { font-size: 14px; font-weight: 700; color: var(--accent); background: var(--progress-bg); padding: 5px 15px; border-radius: 12px;}

#form{display:none;}
.back-btn-ui {background: var(--accent); border: none; color: #fff; font-weight:800; font-size:15px; border-radius: 16px; padding: 14px 24px; width: max-content; margin: 0 0 25px 0; cursor: pointer;}
#form input{width:100%;padding:20px;margin:12px 0;border-radius:20px;border:2px solid transparent;background:var(--input-bg);color:var(--input-text); font-size: 16px;font-weight: 700;}
#form button#withdrawBtn{width:100%;padding:20px;margin-top:25px;border:none;border-radius:24px;background:linear-gradient(135deg,var(--btn-gradient-start),var(--btn-gradient-end));color:var(--text-light);font-weight:900;font-size: 18px;cursor:pointer;}

.history-card { background: var(--card-bg); border-radius: 30px; padding: 30px 20px; margin-top: 30px; border: 1px solid var(--card-border); text-align: left;}
.history-item { background: var(--input-bg); padding: 18px; border-radius: 20px; border: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; margin-bottom:10px;}
.history-amount { font-weight: 900; font-size: 17px; color: var(--accent); }

#taskNotification { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.8); background: linear-gradient(135deg, var(--btn-gradient-start), var(--accent-red)); color: #FFFFFF; padding: 20px 30px; border-radius: 24px; font-weight: 800; opacity: 0; visibility: hidden; z-index: 10000; text-align: center;}
.notification-show { opacity: 1 !important; visibility: visible !important; transform: translate(-50%, -50%) scale(1) !important; }

/* Admin UI */
.admin-input { width: 100%; padding: 15px; margin-bottom: 10px; border-radius: 10px; border: 1px solid var(--card-border); }
.admin-btn { padding: 10px 20px; background: var(--accent-red); color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold;}
</style>
</head>
<body id="body">
<div id="taskNotification"><span id="taskNotificationText"></span></div>

<div class="container">
  <!-- Settings -->
  <div id="settingsPage" class="page">
    <div class="profile-card">
        <h2 style="font-weight:900; margin-bottom:25px; text-align: left;">Ayarlar</h2>
        <h3 style="font-weight:900; margin: 35px 0 15px; text-align: left;">Dostlarını Dəvət Et</h3>
        <div style="display:flex; flex-direction:column; gap:12px;">
            <button class="task-btn" onclick="copyRefLink()">Linki Kopyala</button>
            <button class="task-btn" onclick="shareAppAction()">Telegram ilə Paylaş</button>
        </div>
    </div>
  </div>

  <!-- Tasks -->
  <div id="tasksPage" class="page">
    <div class="balance-card">
      <h3>Balansınız</h3>
      <div class="balances-wrapper">
         <div class="amount-wrap">
             <div class="amount"><span class="bal-val">0.000</span></div>
             <div class="currency">₼</div>
         </div>
      </div>
    </div>
    <div class="task-list">
        <div class="task-item">
            <div class="task-header">
                <div class="task-title"><span data-i18n="task1_title">Kanala Qoşul</span></div>
                <div class="task-reward-badge">0.10 ₼</div>
            </div>
            <div class="task-desc">Rəsmi M Plus kanalına qoşul və qazan!</div>
            <button id="joinAppBtn" class="task-btn" onclick="verifyJoinTask()">Kanala Qoşul / Yoxla</button>
        </div>
    </div>
  </div>

  <!-- Ads (Home) -->
  <div id="adsPage" class="page active">
    <div class="balance-card">
      <h3>Balansınız</h3>
      <div class="balances-wrapper">
         <div class="amount-wrap">
             <div class="amount"><span class="bal-val">0.000</span></div>
             <div class="currency">₼</div>
         </div>
      </div>
    </div>
    
    <div class="ad-card"> 
      <h2>Gündəlik Reklamlar</h2>
      <p>Hər reklam üçün <b class="reward-text-b">0.002 ₼</b> qazanın</p>
      
      <div class="stats">
        <div class="stat"><span id="adsCount">0</span><label>İzlənən</label></div>
        <div class="stat"><span>50</span><label>Maksimum</label></div>
      </div>
      
      <div class="progress"><div class="progress-fill" id="progress"></div></div>
      <p style="margin-top:15px; font-weight:800; font-size: 15px;">İrəliləyiş: <span id="progressTextSpan">0/50</span></p>
      
      <button id="adBtn" class="ad-btn" onclick="watchAd()">Reklam İzlə</button>
    </div>
  </div>
  
  <!-- Referrals -->
  <div id="referralPage" class="page">
    <div class="balance-card">
      <h3>Referal Sistemi</h3>
      <p style="color:var(--text-muted); font-size:14px; margin-bottom:15px;">Dəvət etdiyiniz hər dostunuz ilk tapşırığı etdikdə 0.02₼ qazanın.</p>
      <div class="stats">
        <div class="stat"><span id="refTotal">0</span><label>Ümumi</label></div>
        <div class="stat"><span id="refApproved">0</span><label>Təsdiqlənmiş</label></div>
      </div>
    </div>
    <div class="history-card" id="refListContainer">
        <h3>Dəvət Edilənlər</h3>
        <div id="refList" style="margin-top: 15px;"></div>
    </div>
  </div>

  <!-- Wallet -->
  <div id="walletPage" class="page">
    <div class="balance-card">
      <h3>Balansınız</h3>
      <div class="balances-wrapper">
         <div class="amount-wrap">
             <div class="amount"><span class="bal-val">0.000</span></div>
             <div class="currency">₼</div>
         </div>
      </div>
    </div>
    
    <div id="methodsContainer">
        <h2 style="text-align:center;margin-bottom:10px;">Çıxarış Üsulu</h2>
        <div class="method-list">
            <div class="method" onclick="selectMethod('Bank Kartı')">
              <span class="method-text">Bank Kartı</span>
              <span class="min-withdraw-label">Min: 7 ₼</span>
            </div>
            <div class="method" onclick="selectMethod('M10')">
              <span class="method-text">M10</span>
              <span class="min-withdraw-label">Min: 7 ₼</span>
            </div>
        </div>
    </div>

    <div id="form">
      <button id="backBtn" class="back-btn-ui" onclick="hideForm()">Geri Qayıt</button>
      <h3 style="margin-bottom: 20px;">Məlumatları daxil et</h3>
      <input id="details" type="text" placeholder="Kart və ya Nömrə">
      <input id="amount" type="number" step="0.01" placeholder="Məbləğ (Min 7 ₼)">
      <button id="withdrawBtn" onclick="submitWithdraw()">Təsdiqlə və Çıxar</button>
    </div>

    <div class="history-card">
        <h3>Çıxarış Tarixçəsi</h3>
        <div id="historyList"></div>
    </div>
  </div>

  <!-- Profile -->
  <div id="profilePage" class="page">
    <div class="profile-card">
        <div class="profile-header-wrap">
            <div class="profile-avatar"><i data-lucide="user"></i></div>
            <div>
                <div class="profile-name" id="displayUsername">İstifadəçi</div>
                <div class="profile-id">ID: <span id="displayUserId" style="cursor:pointer;"></span></div>
            </div>
        </div>
    </div>
  </div>

  <!-- Admin Panel -->
  <div id="adminPage" class="page">
    <div class="profile-card">
        <h2>Admin Panel</h2>
        <div style="margin:20px 0;">
            <p>Total Users: <b id="adminTotUsers">0</b></p>
            <p>Pending Withdrawals: <b id="adminPendWith">0</b></p>
        </div>
        <hr style="margin:15px 0;">
        <input type="text" id="adminSearchInput" class="admin-input" placeholder="User UID">
        <button class="admin-btn" onclick="adminSearch()">Search User</button>
        <div id="adminSearchResult" style="margin-top:15px; text-align:left;"></div>
    </div>
  </div>

</div>

<div class="menu">
  <button onclick="showPage('settingsPage',this)"><i data-lucide="settings" class="nav-icon"></i><span class="nav-text">Ayarlar</span></button>
  <button onclick="showPage('tasksPage',this)"><i data-lucide="layout-list" class="nav-icon"></i><span class="nav-text">Tapşırıqlar</span></button>
  <button onclick="showPage('adsPage',this)" class="active"><i data-lucide="house" class="nav-icon"></i><span class="nav-text">M Plus</span></button>
  <button onclick="showPage('referralPage',this)"><i data-lucide="users" class="nav-icon"></i><span class="nav-text">Referal</span></button>
  <button onclick="showPage('walletPage',this)"><i data-lucide="wallet" class="nav-icon"></i><span class="nav-text">Cüzdan</span></button>
  <button onclick="showPage('profilePage',this)"><i data-lucide="user" class="nav-icon"></i><span class="nav-text">Profil</span></button>
</div>

<script>
lucide.createIcons();

let tgUser = window.Telegram.WebApp.initDataUnsafe.user || {id: 0, first_name: 'Guest'};
let initData = window.Telegram.WebApp.initData || '';
let csrfToken = '';
let balance = 0.000;
let adCount = 0;
let selectedWMethod = '';

const apiFetch = async (action, data = {}) => {
    const formData = new URLSearchParams();
    formData.append('action', action);
    formData.append('csrf_token', csrfToken);
    for (let key in data) formData.append(key, data[key]);

    try {
        const res = await fetch(`api.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Tg-Init-Data': initData
            },
            body: formData.toString()
        });
        return await res.json();
    } catch(e) {
        return {error: 'Network Error'};
    }
};

async function initApp() {
    document.getElementById('displayUsername').innerText = tgUser.first_name;
    document.getElementById('displayUserId').innerText = tgUser.id;

    const res = await fetch(`api.php?action=init`, { headers: {'Tg-Init-Data': initData} });
    const data = await res.json();
    if(data.error) { alert(data.error); return; }
    
    balance = data.balance;
    csrfToken = data.csrf_token;
    adCount = data.ad_count;
    
    updateUI();
    if (data.tasks_completed.includes('join_channel')) {
        document.getElementById('joinAppBtn').classList.add('completed');
        document.getElementById('joinAppBtn').innerText = 'Tamamlandı';
        document.getElementById('joinAppBtn').onclick = null;
    }
}

function updateUI() {
    document.querySelectorAll('.bal-val').forEach(el => el.innerText = parseFloat(balance).toFixed(3));
    document.getElementById('adsCount').innerText = adCount;
    document.getElementById('progressTextSpan').innerText = `${adCount}/50`;
    document.getElementById('progress').style.width = `${(adCount/50)*100}%`;
}

function showPage(pageId, btn) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.getElementById(pageId).classList.add('active');
    if (btn) {
        document.querySelectorAll('.menu button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
    if (pageId === 'walletPage') loadHistory();
    if (pageId === 'referralPage') loadReferrals();
}

function showNotification(text) {
    const notif = document.getElementById('taskNotification');
    document.getElementById('taskNotificationText').innerText = text;
    notif.classList.add('notification-show');
    setTimeout(() => notif.classList.remove('notification-show'), 2500);
}

// Adsgram integration
let adManager = null;
if (window.Adsgram) {
    adManager = window.Adsgram.init({ blockId: "int-28369" });
}
async function watchAd() {
    if (adCount >= 50) return alert('Günlük limit doldu!');
    if (!adManager) return alert('Reklam sistemi yüklənməyib.');
    
    adManager.show().then(async (result) => {
        const res = await apiFetch('verifyAd');
        if (res.success) {
            csrfToken = res.new_csrf;
            balance = parseFloat(balance) + parseFloat(res.reward);
            adCount++;
            updateUI();
            showNotification(`Təbrik! ${res.reward} ₼ qazandınız`);
        } else {
            alert(res.error || 'Xəta baş verdi');
        }
    }).catch((result) => {
        alert('Reklam izlənmədi.');
    });
}

async function verifyJoinTask() {
    const res = await apiFetch('verifyJoin');
    if (res.success) {
        csrfToken = res.new_csrf;
        balance = parseFloat(balance) + parseFloat(res.reward);
        updateUI();
        document.getElementById('joinAppBtn').classList.add('completed');
        document.getElementById('joinAppBtn').innerText = 'Tamamlandı';
        document.getElementById('joinAppBtn').onclick = null;
        showNotification(`Təbrik! ${res.reward} ₼ qazandınız`);
    } else {
        alert(res.error || 'Xəta');
    }
}

// Withdrawals
function selectMethod(method) {
    selectedWMethod = method;
    document.getElementById('methodsContainer').style.display = 'none';
    document.getElementById('form').style.display = 'block';
}
function hideForm() {
    document.getElementById('methodsContainer').style.display = 'block';
    document.getElementById('form').style.display = 'none';
}
async function submitWithdraw() {
    const amount = parseFloat(document.getElementById('amount').value);
    const details = document.getElementById('details').value;
    if (amount < 7) return alert('Minimum çıxarış 7 ₼');
    if (!details) return alert('Məlumatları daxil edin');
    
    const res = await apiFetch('withdraw', { amount, method: selectedWMethod, details });
    if (res.success) {
        balance -= amount;
        updateUI();
        alert('Çıxarış tələbi göndərildi!');
        hideForm();
        loadHistory();
    } else {
        alert(res.error || 'Xəta');
    }
}
async function loadHistory() {
    const res = await apiFetch('getHistory');
    if (res.history) {
        const hList = document.getElementById('historyList');
        hList.innerHTML = res.history.map(h => `
            <div class="history-item">
                <div>${h.method} - ${h.status}</div>
                <div class="history-amount">${h.amount} ₼</div>
            </div>
        `).join('') || '<p>Keçmiş boşdur</p>';
    }
}

// Referrals
async function loadReferrals() {
    const res = await apiFetch('getReferrals');
    if (res.referrals) {
        document.getElementById('refTotal').innerText = res.total;
        document.getElementById('refApproved').innerText = res.approved;
        document.getElementById('refList').innerHTML = res.referrals.map(r => `
            <div class="history-item">
                <div>@${r.tg_username || r.tg_name}</div>
                <div>${r.status === 'approved' ? 'Təsdiqlənib (0.02₼)' : 'Gözləyir'}</div>
            </div>
        `).join('') || '<p>Heç kim dəvət edilməyib.</p>';
    }
}
function copyRefLink() {
    const link = `https://t.me/xpverse_bot?startapp=${tgUser.id}`;
    navigator.clipboard.writeText(link);
    showNotification('✅ Referal linki kopyalandı!');
}
function shareAppAction() {
    const link = `https://t.me/xpverse_bot?startapp=${tgUser.id}`;
    window.Telegram.WebApp.openTelegramLink(`https://t.me/share/url?url=${encodeURIComponent(link)}&text=M%20Plus%20ilə%20qazan!`);
}

// Admin Trigger (5 clicks on ID)
let adminClicks = 0;
let adminClickTimer;
document.getElementById('displayUserId').addEventListener('click', () => {
    adminClicks++;
    clearTimeout(adminClickTimer);
    adminClickTimer = setTimeout(() => adminClicks = 0, 1000);
    if(adminClicks >= 5) {
        adminClicks = 0;
        apiFetch('admin_check').then(res => {
            if(res.isAdmin) {
                showPage('adminPage');
                apiFetch('admin_stats').then(st => {
                    document.getElementById('adminTotUsers').innerText = st.total_users;
                    document.getElementById('adminPendWith').innerText = st.pending_withdrawals;
                });
            }
        });
    }
});
async function adminSearch() {
    const q = document.getElementById('adminSearchInput').value;
    const res = await apiFetch('admin_search', {query: q});
    if (res.user) {
        document.getElementById('adminSearchResult').innerHTML = `
            <p>UID: ${res.user.tg_uid}</p>
            <p>Name: ${res.user.tg_name}</p>
            <p>Balance: ${res.user.balance} ₼</p>
            <input type="number" id="adminSetBalInp" value="${res.user.balance}" step="0.01">
            <button onclick="adminSetBal('${res.user.tg_uid}')">Set Balance</button>
        `;
    } else {
        document.getElementById('adminSearchResult').innerHTML = '<p>Not found</p>';
    }
}
async function adminSetBal(uid) {
    const b = document.getElementById('adminSetBalInp').value;
    const res = await apiFetch('admin_set_balance', {target_uid: uid, balance: b});
    if(res.success) alert('Balance updated');
}

initApp();
</script>
</body>
</html>
