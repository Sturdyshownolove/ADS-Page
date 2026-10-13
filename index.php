<?php
define('access', "False");
require_once("include/connect.php");
require_once("include/tarihfonksiyonu.php");
require_once("include/zamanfonksiyonu.php");

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi['siteurl'] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = htmlspecialchars($SiteBilgi['siteadi'] ?? 'Site Adı', ENT_QUOTES, 'UTF-8');
$page_description = htmlspecialchars($SiteBilgi['meta'] ?? 'Site Açıklaması', ENT_QUOTES, 'UTF-8');
$canonical_url = $site_url;
$og_image_url = $site_url . '/img/og-image.jpg';
$amp_path = '/'; // AMP karşılığı: AMP domain ana sayfası

// --- VERİ ÇEKME İŞLEMLERİ ---
$megas = $conn->query("SELECT id, seo, isim, resim, sehir FROM uyeler WHERE durum='Yayında' AND seviye='mega' ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);
$vips = $conn->query("SELECT id, seo, isim, resim, sehir FROM uyeler WHERE durum='Yayında' AND seviye='vip' ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);
$golds = $conn->query("SELECT id, seo, isim, resim, sehir FROM uyeler WHERE durum='Yayında' AND seviye='gold' ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);
$elits = $conn->query("SELECT id, seo, isim, resim, sehir FROM uyeler WHERE durum='Yayında' AND seviye='elit' ORDER BY sira ASC")->fetchAll(PDO::FETCH_ASSOC);

$makaleler = $conn->query("
    SELECT b.id, b.seo, b.baslik, b.resim, b.aciklama, CONCAT(b.yil, '-', b.tarih) AS yayinlanma_tarihi, b.kategori, k.seo AS kategori_seo, k.baslik AS kategori_baslik
    FROM blog AS b LEFT JOIN kategori AS k ON b.kategori = k.id
    WHERE b.durum = 'Aktif' ORDER BY b.id DESC LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

require_once("include/header.php");
?>
<body id="top">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "url": "<?= $canonical_url; ?>",
  "name": "<?= $page_title; ?>",
  "description": "<?= $page_description; ?>",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "<?= $site_url; ?>/?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <!-- Vitrin Showcase Top Hero Section -->
    <section class="vitrin-top-frame">
        <div class="grid-container">
            <div class="site-branding">
                <?php $ustYazi = $SiteBilgi['ustalanyazisi'] ?? ''; ?>
                <?php if (!empty($ustYazi)): ?>
                <div><?= $ustYazi; ?></div>
                <?php else: ?>
                <h1 class="main-title"><?= htmlspecialchars($SiteBilgi['siteadi'] ?? 'Buca Model ☘️ İzmir Model ⭐️ VIP İlanlar'); ?></h1>
                <p class="site-description"><?= htmlspecialchars($SiteBilgi['meta'] ?? 'Gizli, lüks ve kaliteli elit bayan ilanları.'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="grid-container">
        <?php if (!empty($SiteBilgi['anasayfa'])): ?>
        <section class="site-content">
            <?= $SiteBilgi['anasayfa']; // Panelden girilen SEO tanıtım metni ?>
        </section>
        <?php endif; ?>

        <!-- 1. MEGA VIP İLANLAR (3'lü Dizilim) -->
        <?php if (count($megas) > 0): ?>
        <section>
            <div class="section-title-wrapper">
                <h2 class="section-title">👑 MEGA VIP İLANLAR</h2>
            </div>
            <div class="glist glist-mega">
                <?php foreach($megas as $uye): ?>
                <?php 
                    $is_ad = (in_array($uye['resim'], ['mega.webp','vip.webp','gold.webp','elit.webp','platin1.webp','platin.webp']) || strpos($uye['isim'], 'REKLAM') !== false);
                    $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/bucamodel';
                ?>
                <div class="list2">
                    <div class="isim mega-isim"><?= $is_ad ? 'REKLAM ALANI' : 'İZMİR MODEL'; ?></div>
                    <a href="<?= $is_ad ? $tg_link : 'ilan/' . htmlspecialchars($uye['seo']) . '/' . intval($uye['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
                        <img src="modelresim/<?= htmlspecialchars($uye['resim'] ?: 'resimyok.png'); ?>" alt="<?= htmlspecialchars($uye['isim']); ?>" width="100" height="150" loading="lazy">
                    </a>
                    <div class="tel"><?= htmlspecialchars(mb_strtoupper($uye['isim'], 'UTF-8')); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 2. VIP İLANLAR (5'li Dizilim) -->
        <?php if (count($vips) > 0): ?>
        <section>
            <div class="section-title-wrapper">
                <h2 class="section-title">✨ VIP İLANLAR</h2>
            </div>
            <div class="glist glist-5">
                <?php foreach($vips as $uye): ?>
                <?php 
                    $is_ad = (in_array($uye['resim'], ['mega.webp','vip.webp','gold.webp','elit.webp','platin1.webp','platin.webp']) || strpos($uye['isim'], 'REKLAM') !== false);
                    $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/bucamodel';
                ?>
                <div class="list2">
                    <div class="isim vip-isim"><?= $is_ad ? 'REKLAM ALANI' : 'İZMİR MODEL'; ?></div>
                    <a href="<?= $is_ad ? $tg_link : 'ilan/' . htmlspecialchars($uye['seo']) . '/' . intval($uye['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
                        <img src="modelresim/<?= htmlspecialchars($uye['resim'] ?: 'resimyok.png'); ?>" alt="<?= htmlspecialchars($uye['isim']); ?>" width="100" height="150" loading="lazy">
                    </a>
                    <div class="tel"><?= htmlspecialchars(mb_strtoupper($uye['isim'], 'UTF-8')); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 3. GOLD İLANLAR (5'li Dizilim) -->
        <?php if (count($golds) > 0): ?>
        <section>
            <div class="section-title-wrapper">
                <h2 class="section-title">⭐ GOLD İLANLAR</h2>
            </div>
            <div class="glist glist-5">
                <?php foreach($golds as $uye): ?>
                <?php 
                    $is_ad = (in_array($uye['resim'], ['mega.webp','vip.webp','gold.webp','elit.webp','platin1.webp','platin.webp']) || strpos($uye['isim'], 'REKLAM') !== false);
                    $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/bucamodel';
                ?>
                <div class="list2">
                    <div class="isim gold-isim"><?= $is_ad ? 'REKLAM ALANI' : 'BUCA MODEL'; ?></div>
                    <a href="<?= $is_ad ? $tg_link : 'ilan/' . htmlspecialchars($uye['seo']) . '/' . intval($uye['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
                        <img src="modelresim/<?= htmlspecialchars($uye['resim'] ?: 'resimyok.png'); ?>" alt="<?= htmlspecialchars($uye['isim']); ?>" width="100" height="150" loading="lazy">
                    </a>
                    <div class="tel"><?= htmlspecialchars(mb_strtoupper($uye['isim'], 'UTF-8')); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- 4. ELİT İLANLAR (5'li Dizilim) -->
        <?php if (count($elits) > 0): ?>
        <section>
            <div class="section-title-wrapper">
                <h2 class="section-title">💎 ELİT İLANLAR</h2>
            </div>
            <div class="glist glist-5">
                <?php foreach($elits as $uye): ?>
                <?php 
                    $is_ad = (in_array($uye['resim'], ['mega.webp','vip.webp','gold.webp','elit.webp','platin1.webp','platin.webp']) || strpos($uye['isim'], 'REKLAM') !== false);
                    $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/bucamodel';
                ?>
                <div class="list2">
                    <div class="isim elit-isim"><?= $is_ad ? 'REKLAM ALANI' : 'ELİT MODEL'; ?></div>
                    <a href="<?= $is_ad ? $tg_link : 'ilan/' . htmlspecialchars($uye['seo']) . '/' . intval($uye['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?>>
                        <img src="modelresim/<?= htmlspecialchars($uye['resim'] ?: 'resimyok.png'); ?>" alt="<?= htmlspecialchars($uye['isim']); ?>" width="100" height="150" loading="lazy">
                    </a>
                    <div class="tel"><?= htmlspecialchars(mb_strtoupper($uye['isim'], 'UTF-8')); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (count($makaleler) > 0): ?>
        <section>
            <div class="section-title-wrapper">
                <h2 class="section-title">📝 Son Blog Yazıları</h2>
            </div>
            <div class="row">
                <?php foreach($makaleler as $yazi): ?>
                <div class="col-md-6">
                    <div class="media">
                        <div class="media-left">
                            <a href="yazi/<?= htmlspecialchars($yazi['seo']); ?>/<?= intval($yazi['id']); ?>.html">
                                <img class="media-object" src="blogresim/<?= htmlspecialchars($yazi['resim']); ?>" alt="<?= htmlspecialchars($yazi['baslik']); ?>" width="90" height="90" loading="lazy" style="object-fit:cover;">
                            </a>
                        </div>
                        <div class="media-body">
                            <h3 class="media-heading" style="font-size:16px;">
                                <a href="yazi/<?= htmlspecialchars($yazi['seo']); ?>/<?= intval($yazi['id']); ?>.html"><?= htmlspecialchars($yazi['baslik']); ?></a>
                            </h3>
                            <small class="text-muted">
                                <?= turkcetarih('d MMMM yyyy', $yazi['yayinlanma_tarihi']); ?> ·
                                <a href="kategori/<?= htmlspecialchars($yazi['kategori_seo']); ?>/<?= intval($yazi['kategori']); ?>.html"><?= htmlspecialchars($yazi['kategori_baslik']); ?></a>
                            </small>
                            <p style="margin-top:5px;"><?= htmlspecialchars(mb_substr(strip_tags($yazi['aciklama']), 0, 120)); ?>...</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <p class="text-center" style="margin-top:20px;"><a href="tum-yazilar/sayfa/1" class="btn btn-default">Tüm Yazıları Gör</a></p>
        </section>
        <?php endif; ?>
    </div>
</main>
<?php include('include/alt.php'); ?>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
