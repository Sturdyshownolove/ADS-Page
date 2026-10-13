<?php
require_once('header.php');

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guncelle'])) {
    $yeni_kullanici = trim($_POST['kullanici'] ?? '');
    $mevcut_sifre = $_POST['mevcut_sifre'] ?? '';
    $yeni_sifre = $_POST['yeni_sifre'] ?? '';
    $yeni_sifre_tekrar = $_POST['yeni_sifre_tekrar'] ?? '';

    // Mevcut kullanıcıyı çek
    $stmt = $conn->prepare("SELECT site_user, site_pass FROM asistan WHERE site_user = ? OR id = 1 LIMIT 1");
    $stmt->execute([$_SESSION['site_user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($mevcut_sifre, $user['site_pass'])) {
        $msg = 'Mevcut şifrenizi hatalı girdiniz!';
        $msg_type = 'error';
    } elseif (!empty($yeni_sifre) && $yeni_sifre !== $yeni_sifre_tekrar) {
        $msg = 'Yeni şifreler birbiriyle eşleşmiyor!';
        $msg_type = 'error';
    } else {
        $update_pass = !empty($yeni_sifre) ? password_hash($yeni_sifre, PASSWORD_BCRYPT) : $user['site_pass'];
        
        $upStmt = $conn->prepare("UPDATE asistan SET site_user = ?, site_pass = ? WHERE id = 1");
        $upStmt->execute([$yeni_kullanici, $update_pass]);

        $_SESSION['site_user'] = $yeni_kullanici;
        $msg = 'Profil ve şifre bilgileriniz başarıyla güncellendi!';
        $msg_type = 'success';
    }
}
?>

<div class="max-w-xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Yönetici Profil Ayarları</h2>
            <p class="text-xs text-slate-400 mt-1">Admin kullanıcı adı ve giriş şifrenizi güncelleyin.</p>
        </div>
    </div>

    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
        <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
        <span><?= htmlspecialchars($msg); ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="" class="glass-panel p-8 rounded-2xl space-y-5">
        
        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Kullanıcı Adı (*)</label>
            <input type="text" name="kullanici" value="<?= htmlspecialchars($_SESSION['site_user']); ?>" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500 font-semibold">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Mevcut Şifre (*)</label>
            <input type="password" name="mevcut_sifre" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
        </div>

        <div class="pt-2 border-t border-slate-800">
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
            <input type="password" name="yeni_sifre" placeholder="••••••••" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Yeni Şifre (Tekrar)</label>
            <input type="password" name="yeni_sifre_tekrar" placeholder="••••••••" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
        </div>

        <button type="submit" name="guncelle" class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-400 hover:to-sky-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all">
            <i class="fa-solid fa-user-shield mr-1.5"></i> Profili Güncelle
        </button>

    </form>
</div>

<?php require_once('footer.php'); ?>
