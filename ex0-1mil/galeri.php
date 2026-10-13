<?php
require_once('header.php');

$uye_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$ilanStmt = $conn->prepare("SELECT id, isim, resim, seo FROM uyeler WHERE id = ?");
$ilanStmt->execute([$uye_id]);
$ilan = $ilanStmt->fetch(PDO::FETCH_ASSOC);

if (!$ilan) {
    echo "<div class='p-6 text-center text-rose-400 font-bold'>İlan bulunamadı!</div>";
    require_once('footer.php');
    exit();
}

$msg = '';
$msg_type = '';

// Yeni Galeri Resmi Yükle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['yukle'])) {
    if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === UPLOAD_ERR_OK) {
        $gecici_yol = $_FILES['dosya']['tmp_name'];
        $resim_seo = $ilan['seo'] . '-galeri-' . time() . '-' . rand(100, 999) . '.webp';
        $hedef_yol = __DIR__ . '/../modelresim/' . $resim_seo;

        if (webpyeCevir($gecici_yol, $hedef_yol, 85)) {
            $stmt = $conn->prepare("INSERT INTO resimler (uye_id, resim) VALUES (?, ?)");
            $stmt->execute([$uye_id, $resim_seo]);
            $msg = 'Yeni galeri fotoğrafı başarıyla yüklendi (WebP yapıldı)!';
            $msg_type = 'success';
        } else {
            $msg = 'Görsel yüklenirken bir hata oluştu!';
            $msg_type = 'error';
        }
    } else {
        $msg = 'Lütfen geçerli bir görsel seçin!';
        $msg_type = 'error';
    }
}

// Galeri Resmi Sil
if (isset($_GET['sil'])) {
    $sil_id = intval($_GET['sil']);
    $conn->prepare("DELETE FROM resimler WHERE id = ? AND uye_id = ?")->execute([$sil_id, $uye_id]);
    $msg = 'Galeri fotoğrafı silindi!';
    $msg_type = 'success';
}

$resimler = $conn->prepare("SELECT * FROM resimler WHERE uye_id = ? ORDER BY id DESC");
$resimler->execute([$uye_id]);
$galeri = $resimler->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Fotoğraf Galerisi: <span class="text-sky-400"><?= htmlspecialchars($ilan['isim']); ?></span></h2>
            <p class="text-xs text-slate-400 mt-1">Bu model için ekstra galeri fotoğrafları yükleyin ve düzenleyin.</p>
        </div>
        <a href="ilanlar.php" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl border border-slate-700 transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> İlanlara Dön
        </a>
    </div>

    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
        <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
        <span><?= htmlspecialchars($msg); ?></span>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Fotoğraf Yükleme Formu -->
        <div class="lg:col-span-1">
            <form method="POST" action="" enctype="multipart/form-data" class="glass-panel p-6 rounded-2xl space-y-4">
                <h3 class="text-base font-bold text-white mb-2 pb-2 border-b border-slate-800">Galeriye Fotoğraf Ekle</h3>
                
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Fotoğraf Seç (Otomatik WebP Çevrilir)</label>
                    <input type="file" name="dosya" accept="image/*" required class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-slate-300 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[11px] file:bg-sky-500/10 file:text-sky-400">
                </div>

                <button type="submit" name="yukle" class="w-full py-3 bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-500/20 transition-all">
                    <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Fotoğrafı Yükle
                </button>
            </form>
        </div>

        <!-- Yüklü Galeri Fotoğrafları -->
        <div class="lg:col-span-2">
            <div class="glass-panel p-6 rounded-2xl">
                <h3 class="text-base font-bold text-white mb-4">Galeri Fotoğrafları (<?= count($galeri); ?>)</h3>
                
                <?php if (count($galeri) > 0): ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <?php foreach($galeri as $g): ?>
                    <div class="relative group rounded-xl overflow-hidden border border-slate-800 bg-slate-900">
                        <img src="../modelresim/<?= htmlspecialchars($g['resim']); ?>" class="w-full h-36 object-cover">
                        <div class="absolute inset-0 bg-slate-950/70 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-xs">
                            <a href="galeri.php?id=<?= $uye_id; ?>&sil=<?= $g['id']; ?>" onclick="return confirm('Bu fotoğrafı silmek istediğinizden emin misiniz?')" class="p-2 rounded-lg bg-rose-500 hover:bg-rose-600 text-white text-xs shadow-lg transition-all" title="Sil">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="p-8 text-center text-slate-500 text-xs">
                    Bu ilan için henüz ekstra galeri fotoğrafı eklenmemiş.
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require_once('footer.php'); ?>
