<?php
define('access', "False");
require_once("../include/connect.php");
require_once("../include/tarihfonksiyonu.php");

// --- VERİ ALMA VE DOĞRULAMA ---
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($blog_id <= 0) {
    header("HTTP/1.0 404 Not Found"); exit("Geçersiz ID.");
}

// Performans için blog yazısı ve kategoriyi TEK SORGUIDA birleştir (JOIN).
$blogQuery = $conn->prepare("
    SELECT b.*, k.baslik AS kategori_baslik
    FROM blog AS b
    LEFT JOIN kategori AS k ON b.kategori = k.id
    WHERE b.id = ? AND b.durum = 'Aktif'
");
$blogQuery->execute([$blog_id]);
$yaziYaz = $blogQuery->fetch();

if (!$yaziYaz) {
    header("HTTP/1.0 404 Not Found"); exit("Yazı bulunamadı.");
}

// Etiketleri ayrı bir sorgu ile çek
$tagsQuery = $conn->prepare("SELECT baslik FROM etiketler WHERE konu = ?");
$tagsQuery->execute([$blog_id]);
$tags = $tagsQuery->fetchAll(PDO::FETCH_COLUMN);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$amp_url = rtrim(htmlspecialchars($AmpBilgi["amp_domain"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = htmlspecialchars($yaziYaz["baslik"]) . " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = htmlspecialchars($yaziYaz['meta'] ?: mb_substr(strip_tags($yaziYaz['aciklama'] ?? ''), 0, 160));
// Canonical URL, MASAÜSTÜ versiyonuna işaret etmeli
$canonical_url = $site_url . '/yazi/' . htmlspecialchars($yaziYaz['seo']) . '/' . intval($yaziYaz['id']) . '.html';

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

        <style amp-custom>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; background-color: #0b0c10; color: #f0f0f5; margin: 0; }
        .header-bar { display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #ce0056 0%, #8a1f4b 100%); color: #fff; padding: 12px 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        .header-bar a { color: inherit; text-decoration: none; font-weight: 800; font-size: 1.1em; }
        amp-sidebar { width: 250px; background: #15161e; padding: 10px; color: #fff; }
        amp-sidebar ul { list-style: none; padding: 0; margin: 0; }
        amp-sidebar li a { display: block; padding: 12px 16px; color: #fff; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
        main { padding: 10px; }
        .card, .post-card, .page-title { background: #15161e; margin-bottom: 15px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 8px 24px rgba(0,0,0,0.4); overflow: hidden; }
        .card h1, .card h2, .page-title h1 { font-size: 1.3em; padding: 14px 18px; margin: 0; background: linear-gradient(135deg, #ce0056 0%, #8a1f4b 100%); color: #fff; }
        .card .content, .post-content { padding: 18px; color: #f0f0f5; }
        .post-content, .post-content *, .content, .content * { color: #e2e8f0; background-color: transparent; }
        .post-title { font-size: 1.2em; font-weight: bold; margin: 0 0 10px 0; color: #fff; }
        .post-excerpt { font-size: 0.9em; color: #9aa0a6; }
        .post-meta { font-size: 0.8em; color: #9aa0a6; margin-top: 10px; display: flex; gap: 10px; }
        .ilan-card { display: block; background: #15161e; text-decoration: none; color: #fff; border-radius: 14px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 8px 20px rgba(0,0,0,0.4); }
        .ilan-card h2 { font-size: 1.1em; margin: 0; padding: 10px; color: #fff; }
        .footer { text-align: center; padding: 25px 10px; font-size: 0.85em; color: #9aa0a6; border-top: 1px solid rgba(255,255,255,0.08); margin-top: 20px; }
        .footer a { color: #e0a96d; text-decoration: none; }
        .amp-pagination { text-align: center; margin-top: 20px; }
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
        <a href="<?= htmlspecialchars($AmpBilgi['amp_telegram'] ?? 'https://t.me/exampleportal'); ?>" target="_blank" rel="noopener" class="telegram-btn">✈ Reklam Ver</a>
    </header>

    <div style="margin: 10px 12px 0 12px;">
        <a href="<?= $amp_url; ?>/" style="display:block; text-decoration:none; border-radius:12px; overflow:hidden; border: 2px dashed #ce0056; box-shadow: 0 4px 15px rgba(206,0,86,0.3);">
            <amp-img src="<?= $site_url; ?>/img/vitrine-don.webp" width="600" height="200" layout="responsive" alt="Vitrine Dönmek İçin Tıklayınız"></amp-img>
        </a>
    </div>

    <main>
        <article class="content">
            <h1 class="post-title"><?= htmlspecialchars($yaziYaz['baslik']); ?></h1>
            <div class="meta-info">
                <span><?= turkcetarih('d MMMM yyyy', $yaziYaz['yayinlanma_tarihi']); ?></span>
                <?php if(!empty($yaziYaz['kategori_baslik'])): ?>
                <span>Kategori: <?= htmlspecialchars($yaziYaz['kategori_baslik']); ?></span>
                <?php endif; ?>
            </div>
            
            <amp-img src="<?= $site_url; ?>/blogresim/<?= htmlspecialchars($yaziYaz['resim']); ?>"
                     width="16"
                     height="9"
                     layout="responsive"
                     alt="<?= htmlspecialchars($yaziYaz['baslik']); ?>">
            </amp-img>

            <div class="post-content">
                <?= strip_tags($yaziYaz['aciklama'], '<p><br><a><strong><em><h2><h3><h4><ul><ol><li><blockquote>'); // Panelden girilen güvenli HTML olduğu varsayılıyor ?>
            </div>

            <?php if (count($tags) > 0): ?>
            <div class="tags">
                <strong>Etiketler:</strong>
                <?php foreach($tags as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </article>
    </main>

    <footer class="footer">
        <div>
            
        </div>
        <div class="amp-copyright">
            <?= htmlspecialchars($SiteBilgi['copyright'] ?? ''); ?>
        </div>
    </footer>
</body>
</html>