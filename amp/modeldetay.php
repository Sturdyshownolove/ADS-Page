<?php
define('access', "False");
require_once("../include/connect.php");
require_once("../include/tarihfonksiyonu.php");

// --- VERİ ALMA VE DOĞRULAMA ---
$ilan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($ilan_id <= 0) {
    header("HTTP/1.0 404 Not Found"); exit("Geçersiz ID.");
}

// İlanı ve kategori adını tek sorguda çek
$ilanQuery = $conn->prepare("
    SELECT u.*, k.baslik AS kategori_baslik 
    FROM uyeler AS u 
    LEFT JOIN kategori AS k ON u.kategori = k.id 
    WHERE u.id = ? AND u.durum = 'Yayında'
");
$ilanQuery->execute([$ilan_id]);
$ilanYazdir = $ilanQuery->fetch();

if (!$ilanYazdir) {
    header("HTTP/1.0 404 Not Found"); exit("İlan bulunamadı.");
}

// Galeri resimlerini ayrı bir sorgu ile çek
$galleryQuery = $conn->prepare("SELECT resim FROM resimler WHERE uye_id = ? ORDER BY id ASC");
$galleryQuery->execute([$ilan_id]);
$galleryImages = $galleryQuery->fetchAll(PDO::FETCH_COLUMN);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$amp_url = rtrim(htmlspecialchars($AmpBilgi["amp_domain"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = htmlspecialchars($ilanYazdir["isim"]) . " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = htmlspecialchars($ilanYazdir["meta"]);
// Canonical URL, MASAÜSTÜ versiyonuna işaret etmeli
$canonical_url = $site_url . '/ilan/' . htmlspecialchars($ilanYazdir['seo']) . '/' . intval($ilanYazdir['id']) . '.html';
$og_image_url = $site_url . '/modelresim/' . htmlspecialchars($ilanYazdir['resim'] ?: 'resimyok.png');

?>
<!doctype html>
<html ⚡ lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <title><?= $page_title; ?></title>
    <meta name="description" content="<?= $page_description; ?>">
    <link rel="canonical" href="<?= $canonical_url; ?>">

    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "<?= htmlspecialchars($ilanYazdir["isim"]); ?>",
        "image": "<?= $og_image_url; ?>",
        "url": "<?= $canonical_url; ?>",
        "description": "<?= $page_description; ?>",
        "worksFor": {
            "@type": "Organization",
            "name": "<?= htmlspecialchars($SiteBilgi["siteadi"] ?? ''); ?>"
        }
    }
    </script>

    <style amp-custom>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; background-color: #0b0c10; color: #f0f0f5; margin: 0; }
        .header-bar { display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #ce0056 0%, #8a1f4b 100%); color: #fff; padding: 12px 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        .header-bar a { color: inherit; text-decoration: none; font-weight: 800; font-size: 1.1em; }
        amp-sidebar { width: 250px; background: #15161e; padding: 10px; color: #fff; }
        amp-sidebar ul { list-style: none; padding: 0; margin: 0; }
        amp-sidebar li a { display: block; padding: 12px 16px; color: #fff; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
        main { padding: 10px; }
        .card { background: #15161e; margin-bottom: 15px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 8px 24px rgba(0,0,0,0.4); overflow: hidden; }
        .card h1, .card h2 { font-size: 1.3em; padding: 14px 18px; margin: 0; background: linear-gradient(135deg, #ce0056 0%, #8a1f4b 100%); color: #fff; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .card .content { padding: 18px; color: #f0f0f5; }
        .card .content p { margin-top: 0; }
        .contact-buttons { display: flex; flex-direction: column; gap: 12px; margin-top: 15px; }
        .contact-buttons a { display: block; text-align: center; padding: 14px; border-radius: 25px; text-decoration: none; font-weight: bold; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
        .phone-btn { background: linear-gradient(135deg, #ce0056 0%, #8a1f4b 100%); }
        .whatsapp-btn { background: linear-gradient(135deg, #25D366 0%, #1da851 100%); }
        .footer { text-align: center; padding: 25px 10px; font-size: 0.85em; color: #9aa0a6; border-top: 1px solid rgba(255,255,255,0.08); margin-top: 20px; }
        .footer a { color: #e0a96d; text-decoration: none; }
        .amp-copyright { margin-top: 10px; }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(0, 136, 204, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(0, 136, 204, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 136, 204, 0); }
        }
        .telegram-btn { background: linear-gradient(135deg, #0088cc 0%, #005580 100%); color: #ffffff; padding: 7px 15px; border-radius: 20px; text-decoration: none; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; animation: pulseGlow 2s infinite; }
        .telegram-btn:hover { background: #0077b3; color: #ffffff; text-decoration: none; }

    </style>
</head>
<body>
    <amp-sidebar id="sidebar" layout="nodisplay" side="left">
        <ul>
            <li><a href="<?= $amp_url; ?>/">Ana Sayfa</a></li>
            <li><a href="<?= $amp_url; ?>/blog">Blog</a></li>
        </ul>
    </amp-sidebar>

    <header class="header-bar">
        <a href="<?= $amp_url; ?>/"><?= htmlspecialchars($AmpBilgi['amp_baslik'] ?? 'Site Adı'); ?></a>
        <a href="<?= htmlspecialchars($AmpBilgi['amp_telegram'] ?? 'https://t.me/bucamodel'); ?>" target="_blank" rel="noopener" class="telegram-btn">✈ Reklam Ver</a>
    </header>

    <div style="margin: 10px 12px 0 12px;">
        <a href="<?= $amp_url; ?>/" style="display:block; text-decoration:none; border-radius:12px; overflow:hidden; border: 2px dashed #ce0056; box-shadow: 0 4px 15px rgba(206,0,86,0.3);">
            <amp-img src="<?= $site_url; ?>/img/vitrine-don.webp" width="600" height="200" layout="responsive" alt="Vitrine Dönmek İçin Tıklayınız"></amp-img>
        </a>
    </div>

    <main>
        <div class="card">
            <h1><?= htmlspecialchars($ilanYazdir['isim']); ?></h1>
        </div>
        
        <?php if (count($galleryImages) > 0): ?>
        <div class="card">
            <amp-carousel width="400" height="300" layout="responsive" type="slides">
                <?php foreach ($galleryImages as $image): ?>
                <amp-img src="<?= $site_url; ?>/modelresim/<?= htmlspecialchars($image); ?>" width="400" height="300" layout="responsive" alt="<?= htmlspecialchars($ilanYazdir['isim']); ?>"></amp-img>
                <?php endforeach; ?>
            </amp-carousel>
        </div>
        <?php endif; ?>

        <div class="card">
            <h2>Hakkında</h2>
            <div class="content">
                <?= strip_tags($ilanYazdir['aciklama'], '<p><br><a><strong><em><h2><h3><h4><ul><ol><li><blockquote>'); // Panelden gelen HTML içerik ?>
            </div>
        </div>

        <div class="card">
            <h2>İletişim</h2>
            <div class="content">
                <div class="contact-buttons">
                    <a class="phone-btn" href="tel:<?= htmlspecialchars($ilanYazdir['telefon']); ?>">TELEFONLA ARA</a>
                    <a class="whatsapp-btn" href="https://wa.me/<?= formatWhatsappNumber($ilanYazdir['telefon']); ?>?text=<?= rawurlencode('Merhaba numaranızı Buca Kale sitesinden buldum sizinle görüşmek istiyorum detaylı bilgi alabilir miyim?'); ?>" target="_blank">WHATSAPP'TAN YAZ</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        
        <div class="amp-copyright"><?= htmlspecialchars($SiteBilgi['copyright'] ?? ''); ?></div>
    </footer>

</body>
</html>