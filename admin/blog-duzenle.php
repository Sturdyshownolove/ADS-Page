<?php
require_once('header.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM blog WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<div class='p-6 text-center text-rose-400 font-bold'>Makale bulunamadı!</div>";
    require_once('footer.php');
    exit();
}

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guncelle'])) {
    $baslik = trim($_POST['baslik'] ?? '');
    $kategori = intval($_POST['kategori'] ?? 1);
    $aciklama = $_POST['aciklama'] ?? '';
    $meta = trim($_POST['meta'] ?? '');
    $anahtar = trim($_POST['anahtar'] ?? '');
    $odak_kelime = trim($_POST['odak_kelime'] ?? '');

    if (!empty($baslik)) {
        $seo = seo($baslik);
        $resim_adi = $row['resim'];

        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === UPLOAD_ERR_OK) {
            $gecici_yol = $_FILES['dosya']['tmp_name'];
            $hedef_yol = __DIR__ . '/../blogresim/' . $seo . '-' . time() . '.webp';
            if (webpyeCevir($gecici_yol, $hedef_yol, 85)) {
                $resim_adi = $seo . '-' . time() . '.webp';
            }
        }

        $updateStmt = $conn->prepare("
            UPDATE blog 
            SET kategori = ?, baslik = ?, aciklama = ?, resim = ?, meta = ?, anahtar = ?, odak_kelime = ?, seo = ? 
            WHERE id = ?
        ");
        $updateStmt->execute([$kategori, $baslik, $aciklama, $resim_adi, $meta, $anahtar, $odak_kelime, $seo, $id]);

        $msg = 'Makale başarıyla güncellendi!';
        $msg_type = 'success';

        // Yeniden güncel veriyi çek
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $msg = 'Makale başlığı boş bırakılamaz!';
        $msg_type = 'error';
    }
}
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Makale Düzenle: <span class="text-sky-400">#<?= $row['id']; ?> <?= htmlspecialchars($row['baslik']); ?></span></h2>
            <p class="text-xs text-slate-400 mt-1">Makale detaylarını, görselini ve SEO ayarlarını değiştirin.</p>
        </div>
        <a href="blog.php" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl border border-slate-700 transition-all flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Geri Dön</span>
        </a>
    </div>

    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
        <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
        <span><?= htmlspecialchars($msg); ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="glass-panel p-8 rounded-2xl space-y-6">
        
        <!-- Başlık ve Kategori -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Makale Başlığı (*)</label>
                <input type="text" name="baslik" value="<?= htmlspecialchars($row['baslik']); ?>" required class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>

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
        </div>

        <!-- Mevcut Görsel & Yeni Görsel Yükleme -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
            <div class="md:col-span-1">
                <span class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Mevcut Görsel</span>
                <img src="../blogresim/<?= htmlspecialchars($row['resim'] ?: 'resimyok.png'); ?>" class="w-20 h-20 object-cover rounded-xl border border-slate-700 shadow-md">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Yeni Kapak Resmi Yükle (Otomatik WebP Yapılır)</label>
                <input type="file" name="dosya" accept="image/*" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-sky-500/10 file:text-sky-400 hover:file:bg-sky-500/20">
            </div>
        </div>

        <!-- Meta Açıklama -->
        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Meta Açıklama (SEO)</label>
            <textarea name="meta" rows="2" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500"><?= htmlspecialchars($row['meta'] ?? ''); ?></textarea>
        </div>

        <!-- Odak Kelime & Anahtar Kelimeler -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Odak Kelime</label>
                <input type="text" name="odak_kelime" value="<?= htmlspecialchars($row['odak_kelime'] ?? ''); ?>" placeholder="Örn: ÖNE ÇIKAN İLAN" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Anahtar Kelimeler</label>
                <input type="text" name="anahtar" value="<?= htmlspecialchars($row['anahtar'] ?? ''); ?>" placeholder="Örn: ÖNE ÇIKAN İLAN, izmir model" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <!-- Makale İçeriği -->
        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Makale İçeriği (HTML Destekli)</label>
            <textarea name="aciklama" rows="12" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-sky-500 font-mono text-xs leading-relaxed"><?= htmlspecialchars($row['aciklama'] ?? ''); ?></textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4">
            <button type="submit" name="guncelle" class="flex-1 py-4 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-400 hover:to-sky-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Değişiklikleri Kaydet
            </button>
            <a href="../yazi/<?= htmlspecialchars($row['seo']); ?>/<?= $row['id']; ?>.html" target="_blank" class="px-6 py-4 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 font-bold text-sm rounded-xl transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-eye"></i>
                <span>Sitede Gör</span>
            </a>
        </div>

    </form>
</div>

<?php require_once('footer.php'); ?>
