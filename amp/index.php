<?php
require_once("../include/connect.php");
require_once("../include/tarihfonksiyonu.php");

// --- PERFORMANS İÇİN OPTİMİZE EDİLMİŞ VERİ ÇEKME İŞLEMLERİ ---
try {
    $megaUyeler = $conn->query("SELECT id, seo, isim, resim, sehir, telefon FROM uyeler WHERE durum='Yayında' AND seviye='mega' ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);
    $vipUyeler = $conn->query("SELECT id, seo, isim, resim, sehir, telefon FROM uyeler WHERE durum='Yayında' AND seviye='vip' ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);
    $goldUyeler = $conn->query("SELECT id, seo, isim, resim, sehir, telefon FROM uyeler WHERE durum='Yayında' AND seviye='gold' ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);
    $elitUyeler = $conn->query("SELECT id, seo, isim, resim, sehir, telefon FROM uyeler WHERE durum='Yayında' AND seviye='elit' ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);
    $blogYazilari = $conn->query("SELECT id, seo, baslik, resim, aciklama, CONCAT(yil, '-', tarih) AS yayinlanma_tarihi FROM blog WHERE durum = 'Aktif' ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("AMP Anasayfa Veri Çekme Hatası: " . $e->getMessage());
    $megaUyeler = $vipUyeler = $goldUyeler = $elitUyeler = $blogYazilari = [];
}

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$amp_url = rtrim(htmlspecialchars($AmpBilgi["amp_domain"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = htmlspecialchars($SiteBilgi["siteadi"] ?? 'Site Adı');
$page_description = htmlspecialchars($SiteBilgi["meta"] ?? 'Site Açıklaması');
$canonical_url = $site_url;

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
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "<?= $canonical_url; ?>",
      "name": "<?= $page_title; ?>",
      "description": "<?= $page_description; ?>"
    }
    </script>
    
    <style amp-custom>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.5; background: #0b0b0f; color: #f0f2f5; margin: 0; }
        .header-bar { display: flex; align-items: center; justify-content: space-between; background: rgba(19, 20, 28, 0.98); color: #fff; padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.08); position: sticky; top: 0; z-index: 100; }
        .header-bar a { color: inherit; text-decoration: none; font-weight: 800; font-size: 1.1em; }
        amp-sidebar { width: 250px; background: #13141c; padding: 10px; color: #fff; }
        amp-sidebar ul { list-style: none; padding: 0; margin: 0; }
        amp-sidebar li a { display: block; padding: 12px 16px; color: #fff; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
        
        .vitrin-hero { background: #13141c; padding: 18px 12px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 12px; }
        .vitrin-hero h1, .vitrin-hero h2 { font-size: 17px; font-weight: 700; color: #fff; margin: 0 0 8px 0; line-height: 1.4; }
        .vitrin-hero p { font-size: 13px; color: #94a3b8; margin: 0; }

        main { padding: 8px; max-width: 900px; margin: 0 auto; }
        
        .container-title { padding: 6px 0; border-bottom: 2px solid #e11d48; margin: 15px 0 10px 0; }
        .container-title h2 { margin: 0; font-size: 1.1em; font-weight: 800; color: #ffffff; text-transform: uppercase; }

        /* VİTRİN KART DÜZENLERİ */
        .glist { display: flex; flex-wrap: wrap; gap: 5px; justify-content: flex-start; }
        .glist-mega { gap: 6px; }
        .glist-mega .list2 { width: calc((100% - 12px) / 3); }
        .glist-5 { gap: 4px; }
        .glist-5 .list2 { width: calc((100% - 16px) / 5); }

        .list2 { background: #000000; border-radius: 6px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.6); border: 1px solid rgba(255,255,255,0.1); margin-bottom: 6px; box-sizing: border-box; }
        .list2 a { display: block; text-decoration: none; }
        .isim { background: #fbbf02; color: #000000; font-size: 11px; font-weight: 900; text-align: center; padding: 4px 2px; text-transform: uppercase; font-family: arial, sans-serif; letter-spacing: 0.3px; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .mega-isim { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); color: #ffffff; }
        .vip-isim { background: linear-gradient(135deg, #fbbf02 0%, #d97706 100%); color: #000000; }
        .gold-isim { background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%); color: #000000; }
        .elit-isim { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; }

        .tel { background: #000000; color: #ffffff; font-size: 12px; font-weight: 800; text-align: center; padding: 4px 2px; text-transform: uppercase; font-family: arial, sans-serif; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-top: 1px solid rgba(255,255,255,0.15); }
        .listelegold { display: block; width: 100%; border: 0; }

        .site-content { background: #13141c; border: 1px solid rgba(255,255,255,0.08); padding: 16px; border-radius: 10px; margin-bottom: 16px; color: #d1d5db; font-size: 13.5px; }
        .blog-list-item a { display: flex; gap: 12px; text-decoration: none; color: #fff; background: #13141c; padding: 10px; margin-bottom: 8px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); align-items: center;}
        .blog-list-item h3 { font-size: 0.9em; margin: 0; color: #fff; }
        .blog-list-item small { color: #94a3b8; }
        
        .footer { text-align: center; padding: 20px 10px; font-size: 0.85em; color: #94a3b8; border-top: 1px solid rgba(255,255,255,0.08); margin-top: 20px; }
        .footer a { color: #fbbf24; text-decoration: none; }
        .amp-copyright { margin-top: 8px; }
        
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(2, 132, 199, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(2, 132, 199, 0); }
            100% { box-shadow: 0 0 0 0 rgba(2, 132, 199, 0); }
        }
        .telegram-btn { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; padding: 6px 14px; border-radius: 20px; text-decoration: none; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; animation: pulseGlow 2s infinite; }
        
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

    <section class="vitrin-hero">
        <?php $ustYazi = $SiteBilgi['ustalanyazisi'] ?? ''; ?>
        <?php if (!empty($ustYazi)): ?>
            <div><?= $ustYazi; ?></div>
        <?php else: ?>
            <h1><?= htmlspecialchars($SiteBilgi['siteadi'] ?? 'ÖNE ÇIKAN İLAN ☘️ İzmir Hizmet Rehberi ⭐️ Güncel İlanlar'); ?></h1>
            <p><?= htmlspecialchars($SiteBilgi['meta'] ?? 'Gizli, lüks ve kaliteli elit bayan ilanları.'); ?></p>
        <?php endif; ?>
    </section>

    <main>
        <?php if (!empty($SiteBilgi['anasayfa'])): ?>
        <section class="site-content">
            <?= $SiteBilgi['anasayfa']; ?>
        </section>
        <?php endif; ?>

        <!-- 1. MEGA VIP İLANLAR (3'lü Dizilim) -->
        <?php if (count($megaUyeler) > 0): ?>
        <div class="container-title"><h2>👑 Mega VIP Üyeler</h2></div>
        <div class="glist glist-mega">
            <?php foreach($megaUyeler as $Mega): ?>
            <?php 
                $is_ad = (in_array($Mega['resim'], ['mega.webp','vip.webp','gold.webp','elit.webp','platin1.webp','platin.webp']) || strpos($Mega['isim'], 'REKLAM') !== false);
                $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/exampleportal';
            ?>
            <div class="list2">
                <div class="isim mega-isim"><?= $is_ad ? 'REKLAM ALANI' : 'İZMİR MODEL'; ?></div>
                <a href="<?= $is_ad ? $tg_link : $amp_url . '/ilan/' . htmlspecialchars($Mega['seo']) . '/' . intval($Mega['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
                    <amp-img class="listelegold" src="<?= $site_url; ?>/modelresim/<?= htmlspecialchars($Mega['resim']); ?>" width="100" height="150" layout="responsive" alt="<?= htmlspecialchars($Mega['isim']); ?>"></amp-img>
                </a>
                <div class="tel"><?= htmlspecialchars(mb_strtoupper($Mega['isim'], 'UTF-8')); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- 2. VIP İLANLAR (5'li Dizilim) -->
        <?php if (count($vipUyeler) > 0): ?>
        <div class="container-title"><h2>✨ VIP Üyeler</h2></div>
        <div class="glist glist-5">
            <?php foreach($vipUyeler as $Vip): ?>
            <?php 
                $is_ad = (in_array($Vip['resim'], ['mega.webp','vip.webp','gold.webp','elit.webp','platin1.webp','platin.webp']) || strpos($Vip['isim'], 'REKLAM') !== false);
                $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/exampleportal';
            ?>
            <div class="list2">
                <div class="isim vip-isim"><?= $is_ad ? 'REKLAM ALANI' : 'İZMİR MODEL'; ?></div>
                <a href="<?= $is_ad ? $tg_link : $amp_url . '/ilan/' . htmlspecialchars($Vip['seo']) . '/' . intval($Vip['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
                    <amp-img class="listelegold" src="<?= $site_url; ?>/modelresim/<?= htmlspecialchars($Vip['resim']); ?>" width="100" height="150" layout="responsive" alt="<?= htmlspecialchars($Vip['isim']); ?>"></amp-img>
                </a>
                <div class="tel"><?= htmlspecialchars(mb_strtoupper($Vip['isim'], 'UTF-8')); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- 3. GOLD İLANLAR (5'li Dizilim) -->
        <?php if (count($goldUyeler) > 0): ?>
        <div class="container-title"><h2>⭐ Gold Üyeler</h2></div>
        <div class="glist glist-5">
            <?php foreach($goldUyeler as $Gold): ?>
            <?php 
                $is_ad = (in_array($Gold['resim'], ['mega.webp','vip.webp','gold.webp','elit.webp','platin1.webp','platin.webp']) || strpos($Gold['isim'], 'REKLAM') !== false);
                $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/exampleportal';
            ?>
            <div class="list2">
                <div class="isim gold-isim"><?= $is_ad ? 'REKLAM ALANI' : 'ÖNE ÇIKAN İLAN'; ?></div>
                <a href="<?= $is_ad ? $tg_link : $amp_url . '/ilan/' . htmlspecialchars($Gold['seo']) . '/' . intval($Gold['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
                    <amp-img class="listelegold" src="<?= $site_url; ?>/modelresim/<?= htmlspecialchars($Gold['resim']); ?>" width="100" height="150" layout="responsive" alt="<?= htmlspecialchars($Gold['isim']); ?>"></amp-img>
                </a>
                <div class="tel"><?= htmlspecialchars(mb_strtoupper($Gold['isim'], 'UTF-8')); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- 4. ELİT İLANLAR (5'li Dizilim) -->
        <?php if (count($elitUyeler) > 0): ?>
        <div class="container-title"><h2>💎 Elit Üyeler</h2></div>
        <div class="glist glist-5">
            <?php foreach($elitUyeler as $Elit): ?>
            <?php 
                $is_ad = (in_array($Elit['resim'], ['mega.webp','vip.webp','gold.webp','elit.webp','platin1.webp','platin.webp']) || strpos($Elit['isim'], 'REKLAM') !== false);
                $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/exampleportal';
            ?>
            <div class="list2">
                <div class="isim elit-isim"><?= $is_ad ? 'REKLAM ALANI' : 'ELİT MODEL'; ?></div>
                <a href="<?= $is_ad ? $tg_link : $amp_url . '/ilan/' . htmlspecialchars($Elit['seo']) . '/' . intval($Elit['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
                    <amp-img class="listelegold" src="<?= $site_url; ?>/modelresim/<?= htmlspecialchars($Elit['resim']); ?>" width="100" height="150" layout="responsive" alt="<?= htmlspecialchars($Elit['isim']); ?>"></amp-img>
                </a>
                <div class="tel"><?= htmlspecialchars(mb_strtoupper($Elit['isim'], 'UTF-8')); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (count($blogYazilari) > 0): ?>
        <div class="container-title"><h2>📝 Son Blog Yazıları</h2></div>
        <div class="blog-list">
            <?php foreach($blogYazilari as $yazi): ?>
            <div class="blog-list-item">
                <a href="<?= $amp_url; ?>/yazi/<?= htmlspecialchars($yazi['seo']); ?>/<?= intval($yazi['id']); ?>.html">
                    <amp-img src="<?= $site_url; ?>/blogresim/<?= htmlspecialchars($yazi['resim']); ?>" width="70" height="70" alt="<?= htmlspecialchars($yazi['baslik']); ?>"></amp-img>
                    <div>
                        <h3><?= htmlspecialchars($yazi['baslik']); ?></h3>
                        <small><?= turkcetarih('d MMMM yyyy', $yazi['yayinlanma_tarihi']); ?></small>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        
        <div class="amp-copyright"><?= htmlspecialchars($SiteBilgi['copyright'] ?? ''); ?></div>
    </footer>


    
    <?php if (!empty($SiteBilgi["analytics"])): ?>
    <amp-analytics type="googleanalytics">
        <script type="application/json">
        {
            "vars": { "account": "<?= htmlspecialchars($SiteBilgi['analytics'], ENT_QUOTES, 'UTF-8'); ?>" },
            "triggers": { "trackPageview": { "on": "visible", "request": "pageview" } }
        }
        </script>
    </amp-analytics>
    <?php endif; ?>
</body>
</html>