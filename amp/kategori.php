<?php
define('access', "False");
require_once("../include/connect.php");
require_once("../include/tarihfonksiyonu.php");

// 1. Girdileri güvenli bir şekilde al
$katid = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($katid <= 0) {
    header("HTTP/1.0 404 Not Found"); exit("Geçersiz ID.");
}

// 2. Kategori var mı diye kontrol et
$kategoriCek = $conn->prepare("SELECT id, baslik, seo, meta FROM kategori WHERE id = ?");
$kategoriCek->execute([$katid]);
$kat = $kategoriCek->fetch();

if (!$kat) {
    header("HTTP/1.0 404 Not Found"); exit("Kategori bulunamadı.");
}

// --- SAYFALAMA MANTIĞI ---
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$gosterilecek = 10;
$baslangic = ($gosterilecek * $sayfa) - $gosterilecek;
$countQuery = $conn->prepare("SELECT COUNT(id) FROM uyeler WHERE durum='Yayında' AND kategori = ?");
$countQuery->execute([$katid]);
$verisayisi = $countQuery->fetchColumn();
$sayfasayisi = ceil($verisayisi / $gosterilecek);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$amp_url = rtrim(htmlspecialchars($AmpBilgi["amp_domain"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = htmlspecialchars($kat["baslik"]) . " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
// Canonical URL, MASAÜSTÜ versiyonuna işaret etmeli
$canonical_url = $site_url . '/kategori/' . htmlspecialchars($kat['seo']) . '/' . intval($kat['id']) . '.html';
if ($sayfa > 1) { $canonical_url .= '?sayfa=' . $sayfa; }

// --- VERİ ÇEKME ---
$ilanQuery = $conn->prepare("
    SELECT id, seo, isim, resim FROM uyeler 
    WHERE durum='Yayında' AND kategori = :kategori_id 
    ORDER BY sira ASC, id DESC LIMIT :baslangic, :limit
");
$ilanQuery->bindValue(':kategori_id', $katid, PDO::PARAM_INT);
$ilanQuery->bindValue(':baslangic', $baslangic, PDO::PARAM_INT);
$ilanQuery->bindValue(':limit', $gosterilecek, PDO::PARAM_INT);
$ilanQuery->execute();
$ilanlar = $ilanQuery->fetchAll();

?>
<!doctype html>
<html ⚡ lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <title><?= $page_title; ?></title>
    <meta name="description" content="<?= htmlspecialchars($kat['meta']); ?>">
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
        </amp-sidebar>
    <header class="header-bar">
        <a href="<?= $amp_url; ?>/"><?= htmlspecialchars($AmpBilgi['amp_baslik'] ?? 'Site Adı'); ?></a>
        <a href="<?= htmlspecialchars($AmpBilgi['amp_telegram'] ?? 'https://t.me/exampleportal'); ?>" target="_blank" rel="noopener" class="telegram-btn">✈ Reklam Ver</a>
    </header>

    <main>
        <div class="page-title">
            <h1><?= htmlspecialchars($kat['baslik']); ?></h1>
        </div>

        <?php if(count($ilanlar) > 0): foreach($ilanlar as $ilan): ?>
        <?php 
            $is_ad = (strpos($ilan['resim'], 'platin') !== false || strpos($ilan['isim'], 'REKLAM') !== false);
            $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/exampleportal';
        ?>
        <a class="ilan-card" href="<?= $is_ad ? $tg_link : $amp_url . '/modeldetay.php?id=' . intval($ilan['id']); ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
            <amp-img src="<?= $site_url; ?>/modelresim/<?= htmlspecialchars($ilan['resim'] ?: 'resimyok.png'); ?>"
                     width="4"
                     height="3"
                     layout="responsive"
                     alt="<?= htmlspecialchars($ilan['isim']); ?>">
            </amp-img>
            <h2><?= htmlspecialchars($ilan['isim']); ?></h2>
        </a>
        <?php endforeach; else: ?>
            <div class="amp-empty-state">Bu kategoride aktif ilan bulunamadı.</div>
        <?php endif; ?>

        <div class="amp-pagination">
            <?php if($sayfa > 1): ?>
                <a href="<?= $amp_url; ?>/kategori.php?id=<?= $katid; ?>&sayfa=<?= $sayfa - 1; ?>">« Önceki Sayfa</a>
            <?php endif; ?>
            <?php if($sayfa < $sayfasayisi): ?>
                &nbsp; &nbsp;
                <a href="<?= $amp_url; ?>/kategori.php?id=<?= $katid; ?>&sayfa=<?= $sayfa + 1; ?>">Sonraki Sayfa »</a>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        
        <div class="amp-copyright"><?= htmlspecialchars($SiteBilgi['copyright'] ?? ''); ?></div>
    </footer>
</body>
</html>