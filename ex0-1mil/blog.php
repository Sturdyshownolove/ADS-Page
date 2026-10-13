<?php
require_once('header.php');

$msg = '';
$msg_type = '';

// Helper: Blog görseli URL'si oluştur, eğer blogresim'de yoksa modelresim'den otomatik kopyala ve göster
function getBlogImageSrc($resim) {
    if (empty($resim) || $resim === 'resimyok.png') {
        return '../blogresim/resimyok.png';
    }
    
    $blog_path = __DIR__ . '/../blogresim/' . $resim;
    $model_path = __DIR__ . '/../modelresim/' . $resim;

    if (file_exists($blog_path) && filesize($blog_path) > 0) {
        return '../blogresim/' . $resim;
    }

    if (file_exists($model_path) && filesize($model_path) > 0) {
        @copy($model_path, $blog_path);
        return '../modelresim/' . $resim;
    }

    return '../blogresim/resimyok.png';
}

// Helper: modelresim ve blogresim klasörlerinden tüm geçerli görselleri listele
function getAvailableBlogImages() {
    $model_dir = __DIR__ . '/../modelresim/';
    $blog_dir = __DIR__ . '/../blogresim/';
    $images = [];

    if (is_dir($model_dir)) {
        $files = scandir($model_dir);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $full_p = $model_dir . $f;
            if (is_file($full_p)) {
                $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                if (in_array($ext, ['webp', 'jpg', 'jpeg', 'png', 'gif'])) {
                    if (!in_array(strtolower($f), ['resimyok.png', 'mega.webp', 'vip.webp', 'gold.webp', 'elit.webp'])) {
                        $images[] = ['name' => $f, 'source' => $full_p];
                    }
                }
            }
        }
    }

    if (is_dir($blog_dir)) {
        $files = scandir($blog_dir);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $full_p = $blog_dir . $f;
            if (is_file($full_p)) {
                $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                if (in_array($ext, ['webp', 'jpg', 'jpeg', 'png', 'gif'])) {
                    if (!in_array(strtolower($f), ['resimyok.png', 'mega.webp', 'vip.webp', 'gold.webp', 'elit.webp'])) {
                        $images[] = ['name' => $f, 'source' => $full_p];
                    }
                }
            }
        }
    }

    return $images;
}

// Blog Makale Ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ekle'])) {
    $baslik = trim($_POST['baslik'] ?? '');
    $kategori = intval($_POST['kategori'] ?? 1);
    $aciklama = $_POST['aciklama'] ?? '';
    $meta = trim($_POST['meta'] ?? '');
    $anahtar = trim($_POST['anahtar'] ?? '');
    $odak_kelime = trim($_POST['odak_kelime'] ?? '');

    if (!empty($baslik)) {
        $seo = seo($baslik);
        $resim_adi = 'resimyok.png';

        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === UPLOAD_ERR_OK) {
            $gecici_yol = $_FILES['dosya']['tmp_name'];
            $hedef_yol = __DIR__ . '/../blogresim/' . $seo . '-' . time() . '.webp';
            if (webpyeCevir($gecici_yol, $hedef_yol, 85)) {
                $resim_adi = $seo . '-' . time() . '.webp';
            }
        } else {
            // Görsel yüklenmemişse modelresim'den rastgele seç ve kopyala
            $avail = getAvailableBlogImages();
            if (!empty($avail)) {
                $rand_item = $avail[array_rand($avail)];
                $resim_adi = $rand_item['name'];
                $target_path = __DIR__ . '/../blogresim/' . $resim_adi;
                if (file_exists($rand_item['source']) && (!file_exists($target_path) || filesize($target_path) == 0)) {
                    @copy($rand_item['source'], $target_path);
                }
            }
        }

        $stmt = $conn->prepare("
            INSERT INTO blog (kategori, baslik, aciklama, resim, meta, anahtar, durum, odak_kelime, seo, paylasim, tarih, yil, hit)
            VALUES (?, ?, ?, ?, ?, ?, 'Aktif', ?, ?, 'Normal', DATE_FORMAT(NOW(), '%d.%m.%Y'), YEAR(NOW()), 0)
        ");
        $stmt->execute([$kategori, $baslik, $aciklama, $resim_adi, $meta, $anahtar, $odak_kelime, $seo]);

        $msg = 'Blog makalesi başarıyla yayınlandı!';
        $msg_type = 'success';
    }
}

// Blog Silme
if (isset($_GET['sil'])) {
    $sil_id = intval($_GET['sil']);
    $conn->prepare("DELETE FROM blog WHERE id = ?")->execute([$sil_id]);
    $msg = 'Makale silindi!';
    $msg_type = 'success';
}

// Tümünü Sil
if (isset($_GET['tumunu_sil'])) {
    $conn->exec("TRUNCATE TABLE blog");
    $msg = 'Tüm eski makaleler başarıyla silindi ve veritabanı temizlendi!';
    $msg_type = 'success';
}

// Tüm Makalelerin Resimlerini Güncelle ve blogresim'e Kopyala
if (isset($_GET['resimleri_guncelle'])) {
    $tum_resimler = getAvailableBlogImages();
    if (!empty($tum_resimler)) {
        $blog_posts = $conn->query("SELECT id FROM blog")->fetchAll(PDO::FETCH_ASSOC);
        $guncel_sayi = 0;
        $updateStmt = $conn->prepare("UPDATE blog SET resim = ? WHERE id = ?");
        $blog_dir = __DIR__ . '/../blogresim/';

        foreach ($blog_posts as $b) {
            $chosen = $tum_resimler[array_rand($tum_resimler)];
            $img_name = $chosen['name'];
            $img_source = $chosen['source'];
            $img_target = $blog_dir . $img_name;

            if (file_exists($img_source) && (!file_exists($img_target) || filesize($img_target) == 0)) {
                @copy($img_source, $img_target);
            }

            $updateStmt->execute([$img_name, $b['id']]);
            $guncel_sayi++;
        }
        $msg = "$guncel_sayi adet makalenin kapak resmi modelresim klasöründen kopyalanarak blogresim/ dizininde güncellendi!";
        $msg_type = 'success';
    } else {
        $msg = 'Görsel klasörlerinde uygun resim bulunamadı.';
        $msg_type = 'error';
    }
}

$makaleler = $conn->query("
    SELECT b.*, k.baslik AS kat_baslik 
    FROM blog b 
    LEFT JOIN kategori k ON b.kategori = k.id 
    ORDER BY b.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-white tracking-tight">Makale Yönetimi</h2>
        <p class="text-xs text-slate-400 mt-1">SEO içeriklerini ve blog yazılarını güncelleyin ve yayınlayın.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="blog.php?resimleri_guncelle=1" onclick="return confirm('Tüm makalelerin kapak resimleri modelresim klasöründen blogresim klasörüne kopyalanıp yenilenecektir. Onaylıyor musunuz?')" class="px-4 py-2.5 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/20 font-bold text-xs rounded-xl transition-all inline-flex items-center gap-1.5" title="Kapak Resimlerini Yenile">
            <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
            <span>Resimleri Yenile</span>
        </a>
        <a href="blog.php?tumunu_sil=1" onclick="return confirm('TÜM MAKALELER SİLİNECEK! Emin misiniz?')" class="px-4 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 font-bold text-xs rounded-xl transition-all inline-flex items-center gap-1.5">
            <i class="fa-solid fa-trash-can text-xs"></i>
            <span>Tüm Makaleleri Sil</span>
        </a>
        <a href="toplu-makale.php" class="px-4 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-400 hover:to-sky-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-500/20 transition-all inline-flex items-center gap-2">
            <i class="fa-solid fa-bolt text-xs"></i>
            <span>Otomatik Toplu Yükle</span>
        </a>
    </div>
</div>

<?php if ($msg): ?>
<div class="p-4 mb-6 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
    <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
    <span><?= htmlspecialchars($msg); ?></span>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Yeni Makale Ekle Formu -->
    <div class="lg:col-span-1">
        <form method="POST" action="" enctype="multipart/form-data" class="glass-panel p-6 rounded-2xl space-y-4">
            <h3 class="text-base font-bold text-white mb-2 pb-2 border-b border-slate-800">Yeni Makale Ekle</h3>
            
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Makale Başlığı (*)</label>
                <input type="text" name="baslik" required placeholder="Örn: Buca Model Rehberi" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Kategori</label>
                <select name="kategori" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
                    <?php 
                    $kats = $conn->query("SELECT id, baslik FROM kategori ORDER BY baslik ASC")->fetchAll(PDO::FETCH_ASSOC);
                    foreach($kats as $k): ?>
                    <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['baslik']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Kapak Resmi (Boş bırakılırsa rastgele model seçilir)</label>
                <input type="file" name="dosya" accept="image/*" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-slate-300 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[11px] file:bg-sky-500/10 file:text-sky-400">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Meta Açıklama</label>
                <textarea name="meta" rows="2" placeholder="SEO meta açıklaması..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Makale İçeriği</label>
                <textarea name="aciklama" rows="5" placeholder="Makale detayları..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <button type="submit" name="ekle" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
                <i class="fa-solid fa-paper-plane mr-1"></i> Makaleyi Yayınla
            </button>
        </form>
    </div>

    <!-- Yayınlanmış Makaleler Tablosu -->
    <div class="lg:col-span-2">
        <div class="glass-panel p-6 rounded-2xl">
            <h3 class="text-base font-bold text-white mb-4">Yayındaki Makaleler (<?= count($makaleler); ?>)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-3">Görsel</th>
                            <th class="py-3 px-3">Başlık</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Tarih</th>
                            <th class="py-3 px-3 text-right">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-xs">
                        <?php foreach($makaleler as $m): ?>
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-3">
                                <img src="<?= getBlogImageSrc($m['resim']); ?>" class="w-10 h-10 object-cover rounded-lg border border-slate-700">
                            </td>
                            <td class="py-3 px-3 font-bold text-white max-w-[200px] truncate"><?= htmlspecialchars($m['baslik']); ?></td>
                            <td class="py-3 px-3 text-slate-400"><?= htmlspecialchars($m['kat_baslik'] ?? 'Genel'); ?></td>
                            <td class="py-3 px-3 text-slate-500"><?= htmlspecialchars($m['tarih'] ?? ''); ?></td>
                            <td class="py-3 px-3 text-right space-x-1">
                                <a href="blog-duzenle.php?id=<?= $m['id']; ?>" class="p-2 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/20 transition-all inline-flex items-center" title="Düzenle">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <a href="../yazi/<?= htmlspecialchars($m['seo']); ?>/<?= $m['id']; ?>.html" target="_blank" class="p-2 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 transition-all inline-flex items-center" title="Oku">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="blog.php?sil=<?= $m['id']; ?>" onclick="return confirm('Bu makaleyi silmek istediğinizden emin misiniz?')" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all inline-flex items-center" title="Sil">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php require_once('footer.php'); ?>
