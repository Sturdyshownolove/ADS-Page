<?php
require_once('header.php');

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kaydet'])) {
    $isim = trim($_POST['isim'] ?? '');
    $kategori = intval($_POST['kategori'] ?? 0);
    $telefon = trim($_POST['telefon'] ?? '');
    $sehir = trim($_POST['sehir'] ?? 'İzmir');
    $semt = trim($_POST['semt'] ?? 'Buca');
    $aciklama = $_POST['aciklama'] ?? '';
    $seviye = $_POST['seviye'] ?? 'mega';
    $btarih = $_POST['btarih'] ?? date('Y-m-d', strtotime('+1 month'));

    if (empty($isim)) {
        $msg = 'Lütfen ilan adını girin.';
        $msg_type = 'error';
    } else {
        $seo_val = seo($isim) . '-' . time();
        $resim_adi = 'resimyok.png';

        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === UPLOAD_ERR_OK) {
            $gecici_yol = $_FILES['dosya']['tmp_tmp_name'] ?? $_FILES['dosya']['tmp_name'];
            $hedef_yol = __DIR__ . '/../modelresim/' . $seo_val . '.webp';
            if (webpyeCevir($gecici_yol, $hedef_yol, 85)) {
                $resim_adi = $seo_val . '.webp';
            }
        }

        $stmt = $conn->prepare("
            INSERT INTO uyeler (kategori, isim, telefon, sehir, semt, aciklama, seviye, resim, seo, durum, tarih, btarih, sira)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Yayında', CURDATE(), ?, 1)
        ");
        $stmt->execute([$kategori, $isim, $telefon, $sehir, $semt, $aciklama, $seviye, $resim_adi, $seo_val, $btarih]);

        $msg = 'İlan başarıyla eklendi!';
        $msg_type = 'success';
    }
}
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Yeni İlan Ekle</h2>
            <p class="text-xs text-slate-400 mt-1">İlan bilgilerini girip yayın seviyesini seçerek kaydet.</p>
        </div>
        <a href="ilanlar.php" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl border border-slate-700 transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Geri Dön
        </a>
    </div>

    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
        <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
        <span><?= htmlspecialchars($msg); ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="glass-panel p-8 rounded-2xl space-y-6">
        
        <!-- İlan Adı ve Seviye -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">İlan Adı / Model Adı (*)</label>
                <input type="text" name="isim" required placeholder="Örn: Meltem" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Vitrin Seviyesi (*)</label>
                <select name="seviye" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
                    <option value="mega">👑 Mega VIP İlan (3'lü Dizilim)</option>
                    <option value="vip">✨ VIP İlan (5'li Dizilim)</option>
                    <option value="gold">⭐ Gold İlan (5'li Dizilim)</option>
                    <option value="elit">💎 Elit İlan (5'li Dizilim)</option>
                </select>
            </div>
        </div>

        <!-- Kategori & Telefon -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Kategori</label>
                <select name="kategori" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
                    <?php 
                    $kats = $conn->query("SELECT id, baslik FROM kategori ORDER BY baslik ASC")->fetchAll(PDO::FETCH_ASSOC);
                    foreach($kats as $k): ?>
                    <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['baslik']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Telefon Numarası</label>
                <input type="text" name="telefon" placeholder="05xxxxxxxxx" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <!-- Şehir & Semt & Bitiş Tarihi -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Şehir</label>
                <input type="text" name="sehir" value="İzmir" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">İlçe / Semt</label>
                <input type="text" name="semt" value="Buca" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Yayın Bitiş Tarihi</label>
                <input type="date" name="btarih" value="<?= date('Y-m-d', strtotime('+1 month')); ?>" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <!-- Görsel Yükleme (Otomatik WebP Dönüşümü) -->
        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Vitrin Görseli (Otomatik WebP Çevrilir)</label>
            <input type="file" name="dosya" accept="image/*" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-sky-500/10 file:text-sky-400 hover:file:bg-sky-500/20">
        </div>

        <!-- Açıklama -->
        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">İlan Açıklaması</label>
            <textarea name="aciklama" rows="4" placeholder="İlan detayları..." class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500"></textarea>
        </div>

        <!-- Submit -->
        <button type="submit" name="kaydet" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition-all">
            <i class="fa-solid fa-floppy-disk mr-2"></i> İlanı Kaydet
        </button>

    </form>
</div>

<?php require_once('footer.php'); ?>
