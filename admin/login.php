<?php
session_start();

if (isset($_SESSION['site_user'])) {
    header('Location: index.php');
    exit();
}

require_once(__DIR__ . '/../include/connect.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $kullanici = trim($_POST['kullanici'] ?? '');
    $sifre = $_POST['sifre'] ?? '';

    if (empty($kullanici) || empty($sifre)) {
        $error = 'Lütfen tüm alanları doldurun.';
    } else {
        try {
            $stmt = $conn->prepare("SELECT site_user, site_pass FROM asistan WHERE site_user = ?");
            $stmt->execute([$kullanici]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($sifre, $user['site_pass'])) {
                session_regenerate_id(true);
                $_SESSION['site_user'] = $user['site_user'];
                header('Location: index.php');
                exit();
            } else {
                $error = 'Kullanıcı adı veya şifre hatalı!';
            }
        } catch (Exception $e) {
            $error = 'Veritabanı bağlantı hatası oluştu.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap | PortalEX</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-box { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black text-slate-100">

    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl p-3 mb-4">
                <img src="../favicon.ico" alt="PortalEX" class="w-10 h-10 object-contain">
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">BUCA <span class="text-sky-400">EX</span></h1>
            <p class="text-xs text-slate-400 mt-1">PortalEX Yönetim Paneli</p>
        </div>

        <!-- Login Card -->
        <div class="glass-box p-8 rounded-2xl shadow-2xl space-y-6">
            <?php if ($error): ?>
            <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-sm"></i>
                <span><?= htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Kullanıcı Adı</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <input type="text" name="kullanici" required autocomplete="username" placeholder="kullanıcı adı" class="w-full pl-10 pr-4 py-3 bg-slate-900/90 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Şifre</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="sifre" required autocomplete="current-password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-slate-900/90 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all">
                    </div>
                </div>

                <button type="submit" name="login" class="w-full py-3.5 px-4 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-400 hover:to-sky-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all transform active:scale-[0.98]">
                    Giriş Yap <i class="fa-solid fa-arrow-right-to-bracket ml-1.5 text-xs"></i>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">© 2026 PortalEX Tüm Hakları Saklıdır.</p>
    </div>

</body>
</html>
