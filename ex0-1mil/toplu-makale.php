<?php
require_once('header.php');

$msg = '';
$msg_type = '';
$yuklenen_sayisi = 0;

$target_folder = 'C:/Users/Pc/Desktop/makaleler';

function getAvailableBlogImages() {
    $model_dir = __DIR__ . '/../modelresim/';
    $blog_dir = __DIR__ . '/../blogresim/';
    $images = [];

    if (is_dir($model_dir)) {
        $files = scandir($model_dir);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if (in_array($ext, ['webp', 'jpg', 'jpeg', 'png', 'gif'])) {
                if (!in_array($f, ['resimyok.png', 'mega.webp', 'vip.webp', 'gold.webp', 'elit.webp'])) {
                    $images[] = ['name' => $f, 'source' => $model_dir . $f];
                }
            }
        }
    }

    if (is_dir($blog_dir)) {
        $files = scandir($blog_dir);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if (in_array($ext, ['webp', 'jpg', 'jpeg', 'png', 'gif'])) {
                if (!in_array($f, ['resimyok.png', 'mega.webp', 'vip.webp', 'gold.webp', 'elit.webp'])) {
                    $images[] = ['name' => $f, 'source' => $blog_dir . $f];
                }
            }
        }
    }

    return $images;
}

// Bütünüyle Mevcut Makalelerin Resimlerini Güncelleme İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resimleri_guncelle'])) {
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

            if (file_exists($img_source) && !file_exists($img_target)) {
                @copy($img_source, $img_target);
            }

            $updateStmt->execute([$img_name, $b['id']]);
            $guncel_sayi++;
        }
        $msg = "$guncel_sayi adet makalenin kapak resmi modelresim/ klasöründen kopyalanarak blogresim/ dizininde güncellendi!";
        $msg_type = 'success';
    } else {
        $msg = 'Görsel klasörlerinde uygun resim bulunamadı.';
        $msg_type = 'error';
    }
}

// Otomatik Yükleme İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otomatik_yukle'])) {
    
    // İstenirse önce tüm eski demo makaleleri temizle
    if (isset($_POST['temizle_eski'])) {
        $conn->exec("TRUNCATE TABLE blog");
    }

    // 1. Modelresim ve blogresim klasörlerindeki mevcut görselleri tara
    $tum_resimler = getAvailableBlogImages();
    $blog_dir = __DIR__ . '/../blogresim/';

    $sabit_anahtarlar = 'buca model, izmir buca model, buca vip model, buca saatlik model, buca eve gelen model, buca otele gelen model, buca ucuz model, buca elit model, buca model bayan, şirinyer model, buca şirinyer model, izmir şirinyer model, buca hasanağa model, hasanağa parkı model, buca gölet model, gölet model bayan, adatepe model, buca adatepe model, buca koop model, buca koop model bayan, kuruçeşme model, buca kuruçeşme model, buca model numaraları, buca model ilanları, buca model rehberi, buca bayan model, buca bağımsız model, buca türbanlı model, buca liseli model, buca masaj salonu';

    $makale_icerikleri = [];

    // YÖNTEM A: Bilgisayardan çoklu .txt dosya yüklemesi yapılmışsa
    if (isset($_FILES['makale_dosyalari']) && !empty($_FILES['makale_dosyalari']['name'][0])) {
        $toplam_dosya = count($_FILES['makale_dosyalari']['name']);
        for ($i = 0; $i < $toplam_dosya; $i++) {
            if ($_FILES['makale_dosyalari']['error'][$i] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['makale_dosyalari']['tmp_name'][$i];
                $fname = $_FILES['makale_dosyalari']['name'][$i];
                $txt_content = file_get_contents($tmp);
                if (!empty($txt_content)) {
                    $makale_icerikleri[] = [
                        'content' => $txt_content,
                        'filename' => $fname
                    ];
                }
            }
        }
    } 
    // YÖNTEM B: Sunucu üzerindeki yerel klasör yolundan oku (XAMPP / Local testler için)
    else {
        $klasor = trim($_POST['klasor_yolu'] ?? '');
        if (empty($klasor)) {
            $klasor = $target_folder;
        }
        if (is_dir($klasor)) {
            $files = glob($klasor . '/*.txt');
            if (is_array($files)) {
                foreach ($files as $file) {
                    $txt_content = file_get_contents($file);
                    if (!empty($txt_content)) {
                        $makale_icerikleri[] = [
                            'content' => $txt_content,
                            'filename' => basename($file)
                        ];
                    }
                }
            }
        }
    }

    if (!empty($makale_icerikleri)) {
        foreach ($makale_icerikleri as $item) {
            $content = $item['content'];
            $file_label = $item['filename'];

            // Başlık çıkar (<h2>...</h2> etiketinden veya ilk satırdan)
            $baslik = '';
            if (preg_match('/<h2>(.*?)<\/h2>/i', $content, $match)) {
                $baslik = strip_tags(trim($match[1]));
            } else {
                $baslik = 'Buca Model Rehberi - ' . basename($file_label, '.txt');
            }

            // Kategori Tespiti & Odak Kelime Belirleme
            $kategori_id = 36; // Varsayılan: Buca Model
            $odak_kelime = 'buca model';

            $content_lower = mb_strtolower($content, 'UTF-8');
            $baslik_lower = mb_strtolower($baslik, 'UTF-8');
            
            if (strpos($content_lower, 'şirinyer') !== false || strpos($baslik_lower, 'şirinyer') !== false) {
                $kategori_id = 10;
                $odak_kelime = 'şirinyer model';
            } elseif (strpos($content_lower, 'hasanağa') !== false || strpos($baslik_lower, 'hasanağa') !== false) {
                $kategori_id = 41;
                $odak_kelime = 'buca hasanağa model';
            } elseif (strpos($content_lower, 'gölet') !== false || strpos($baslik_lower, 'gölet') !== false) {
                $kategori_id = 18;
                $odak_kelime = 'buca gölet model';
            } elseif (strpos($content_lower, 'adatepe') !== false || strpos($baslik_lower, 'adatepe') !== false) {
                $kategori_id = 19;
                $odak_kelime = 'adatepe model';
            } elseif (strpos($content_lower, 'koop') !== false || strpos($baslik_lower, 'koop') !== false) {
                $kategori_id = 20;
                $odak_kelime = 'buca koop model';
            } elseif (strpos($content_lower, 'kuruçeşme') !== false || strpos($baslik_lower, 'kuruçeşme') !== false) {
                $kategori_id = 21;
                $odak_kelime = 'kuruçeşme model';
            }

            // Rastgele Görsel Seçimi & blogresim/ dizinine kopyalama
            $secilen_resim = 'resimyok.png';
            if (!empty($tum_resimler)) {
                $chosen = $tum_resimler[array_rand($tum_resimler)];
                $secilen_resim = $chosen['name'];
                $img_source = $chosen['source'];
                $img_target = $blog_dir . $secilen_resim;

                if (file_exists($img_source) && !file_exists($img_target)) {
                    @copy($img_source, $img_target);
                }
            }

            // Otomatik Meta Açıklama
            $meta = '';
            if (preg_match('/<p>(.*?)<\/p>/i', $content, $pmatch)) {
                $temiz_p = strip_tags(trim($pmatch[1]));
                $meta = mb_substr($temiz_p, 0, 155, 'UTF-8');
            } else {
                $meta = 'Buca model bayan ilanları ve VIP buluşma rehberi.';
            }

            $seo = seo($baslik);

            // DB Ekleme
            $stmt = $conn->prepare("
                INSERT INTO blog (kategori, baslik, aciklama, resim, meta, anahtar, durum, odak_kelime, seo, paylasim, tarih, yil, hit)
                VALUES (?, ?, ?, ?, ?, ?, 'Aktif', ?, ?, 'Normal', DATE_FORMAT(NOW(), '%d.%m.%Y'), YEAR(NOW()), 0)
            ");
            $stmt->execute([$kategori_id, $baslik, $content, $secilen_resim, $meta, $sabit_anahtarlar, $odak_kelime, $seo]);

            $yuklenen_sayisi++;
        }

        $msg = "$yuklenen_sayisi adet makale bilgisayarınızdan başarıyla yüklendi, kapak resimleri blogresim/ klasörüne kopyalanarak yayınlandı!";
        $msg_type = 'success';
    } else {
        $msg = 'Lütfen bilgisayarınızdan .txt dosyalarını seçin veya geçerli bir klasör belirtin.';
        $msg_type = 'error';
    }
}
?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Otomatik Toplu Makale Yükleyici</h2>
            <p class="text-xs text-slate-400 mt-1">Kendi bilgisayarınızdan çoklu .txt dosyası seçerek tek tıkla veritabanına aktarın.</p>
        </div>
        <a href="blog.php" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl border border-slate-700 transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Makalelere Dön
        </a>
    </div>

    <?php if ($msg): ?>
    <div class="p-4 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
        <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
        <span><?= htmlspecialchars($msg); ?></span>
    </div>
    <?php endif; ?>

    <!-- 1. HIZLI RESİM ATAMA BUTONU (Yüklü Makaleler İçin) -->
    <div class="glass-panel p-6 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-sky-500/20 bg-sky-500/5">
        <div>
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-image text-sky-400"></i>
                <span>Yayınlanmış Makalelerin Resimlerini Rastgele Güncelle</span>
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">Sistemde ekli olan tüm makalelerin kapak resimlerini <code>modelresim/</code> klasöründen <code>blogresim/</code> klasörüne kopyalayarak yeniler.</p>
        </div>
        <form method="POST" action="" class="shrink-0">
            <button type="submit" name="resimleri_guncelle" class="px-4 py-2.5 bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-500/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Mevcut Makalelere Resim Ata</span>
            </button>
        </form>
    </div>

    <!-- 2. TOPLU DOSYA YÜKLEME FORMU -->
    <div class="glass-panel p-8 rounded-2xl space-y-6">
        <form method="POST" action="" enctype="multipart/form-data">
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                    📁 Bilgisayarınızdan Makale Dosyalarını Seçin (.txt)
                </label>
                <input type="file" name="makale_dosyalari[]" multiple accept=".txt" class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-sky-500/10 file:text-sky-400 hover:file:bg-sky-500/20 cursor-pointer">
                <p class="text-[11px] text-slate-400 mt-1.5">Masaüstünüzdeki <code>makaleler</code> klasörüne girip <strong>tüm 20 adedi seçebilirsiniz</strong> (CTRL + A ile hepsini işaretleyin).</p>
            </div>

            <!-- Temizle Seçeneği -->
            <div class="mb-6 p-4 rounded-xl bg-slate-900/60 border border-slate-800 flex items-center gap-3">
                <input type="checkbox" name="temizle_eski" id="temizle_eski" value="1" checked class="w-4 h-4 rounded text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500">
                <label for="temizle_eski" class="text-xs font-bold text-slate-300 cursor-pointer">
                    Yükleme öncesi tüm eski demo makaleleri (Marmaris vb.) veritabanından tamamen sil ve temizle
                </label>
            </div>

            <button type="submit" name="otomatik_yukle" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-bolt text-sm"></i>
                <span>Otomasyonu Başlat & Seçilen Makaleleri Yayınla</span>
            </button>
        </form>
    </div>
</div>

<?php require_once('footer.php'); ?>
