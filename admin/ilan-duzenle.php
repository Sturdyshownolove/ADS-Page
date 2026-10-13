<?php
require_once('header.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM uyeler WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<div class='p-6 text-center text-rose-400 font-bold'>İlan bulunamadı!</div>";
    require_once('footer.php');
    exit();
}

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guncelle'])) {
    $isim = trim($_POST['isim'] ?? '');
    $kategori = intval($_POST['kategori'] ?? 0);
    $telefon = trim($_POST['telefon'] ?? '');
    $sehir = trim($_POST['sehir'] ?? 'İzmir');
    $semt = trim($_POST['semt'] ?? 'Buca');
    $aciklama = $_POST['aciklama'] ?? '';
    $seviye = $_POST['seviye'] ?? 'mega';
    $durum = $_POST['durum'] ?? 'Yayında';
    $btarih = $_POST['btarih'] ?? $row['btarih'];
    $sira = intval($_POST['sira'] ?? $row['sira']);

    $resim_adi = $row['resim'];

    if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === UPLOAD_ERR_OK) {
        $gecici_yol = $_FILES['dosya']['tmp_name'];
        $seo_val = seo($isim) . '-' . time();
        $hedef_yol = __DIR__ . '/../modelresim/' . $seo_val . '.webp';
        if (webpyeCevir($gecici_yol, $hedef_yol, 85)) {
            $resim_adi = $seo_val . '.webp';
        }
    }

    $updateStmt = $conn->prepare("
        UPDATE uyeler SET kategori = ?, isim = ?, telefon = ?, sehir = ?, semt = ?, aciklama = ?, seviye = ?, resim = ?, durum = ?, btarih = ?, sira = ? WHERE id = ?
    ");
    $updateStmt->execute([$kategori, $isim, $telefon, $sehir, $semt, $aciklama, $seviye, $resim_adi, $durum, $btarih, $sira, $id]);

    $msg = 'İlan bilgileri başarıyla güncellendi!';
    $msg_type = 'success';

    // Yeniden veri çek
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">İlan Düzenle: <span class="text-sky-400">#<?= $row['id']; ?> <?= htmlspecialchars($row['isim']); ?></span></h2>
            <p class="text-xs text-slate-400 mt-1">İlan detaylarını değiştirin ve güncelleyin.</p>
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
                <input type="text" name="isim" value="<?= htmlspecialchars($row['isim']); ?>" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Vitrin Seviyesi (*)</label>
                <select name="seviye" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
                    <option value="mega" <?= $row['seviye'] == 'mega' ? 'selected' : ''; ?>>👑 Mega VIP İlan (3'lü Dizilim)</option>
                    <option value="vip" <?= $row['seviye'] == 'vip' ? 'selected' : ''; ?>>✨ VIP İlan (5'li Dizilim)</option>
                    <option value="gold" <?= $row['seviye'] == 'gold' ? 'selected' : ''; ?>>⭐ Gold İlan (5'li Dizilim)</option>
                    <option value="elit" <?= $row['seviye'] == 'elit' ? 'selected' : ''; ?>>💎 Elit İlan (5'li Dizilim)</option>
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
                    <option value="<?= $k['id']; ?>" <?= $k['id'] == $row['kategori'] ? 'selected' : ''; ?>><?= htmlspecialchars($k['baslik']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Telefon Numarası</label>
                <input type="text" name="telefon" value="<?= htmlspecialchars($row['telefon']); ?>" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <!-- Sıra & Durum & Bitiş Tarihi -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Vitrin Sırası</label>
                <input type="number" name="sira" value="<?= intval($row['sira']); ?>" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Yayın Durumu</label>
                <select name="durum" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
                    <option value="Yayında" <?= $row['durum'] == 'Yayında' ? 'selected' : ''; ?>>Yayında</option>
                    <option value="Pasif" <?= $row['durum'] == 'Pasif' ? 'selected' : ''; ?>>Pasif / Gizli</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Bitiş Tarihi</label>
                <input type="date" name="btarih" value="<?= htmlspecialchars($row['btarih']); ?>" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <!-- Mevcut Görsel & Yeni Görsel Yükleme -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
            <div class="md:col-span-1">
                <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Mevcut Görsel</span>
                <img src="../modelresim/<?= htmlspecialchars($row['resim'] ?: 'resimyok.png'); ?>" class="w-20 h-28 object-cover rounded-xl border border-slate-700 shadow-md">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Yeni Görsel Yükle (Otomatik WebP Yapılır)</label>
                <input type="file" name="dosya" accept="image/*" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-sky-500/10 file:text-sky-400 hover:file:bg-sky-500/20">
            </div>
        </div>

        <!-- Açıklama -->
        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">İlan Açıklaması</label>
            <textarea name="aciklama" rows="4" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500"><?= htmlspecialchars($row['aciklama']); ?></textarea>
        </div>

        <!-- Submit -->
        <button type="submit" name="guncelle" class="w-full py-4 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-400 hover:to-sky-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all">
            <i class="fa-solid fa-floppy-disk mr-2"></i> Değişiklikleri Kaydet
        </button>

    </form>
</div>

<?php require_once('footer.php'); ?>
