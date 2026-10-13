<?php
define('access', "False");
require_once("include/connect.php");
require_once("include/tarihfonksiyonu.php");

// --- ID KONTROLÜ VE VERİ ÇEKME ---
$kategori_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($kategori_id <= 0) { include('404.php'); exit; }

$katQuery = $conn->prepare("SELECT * FROM kategori WHERE id = ?");
$katQuery->execute([$kategori_id]);
$kat = $katQuery->fetch();
if (!$kat) { include('404.php'); exit; }

// --- SAYFALAMA ---
$limit = 12;
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$toplamQuery = $conn->prepare("SELECT COUNT(*) FROM uyeler WHERE kategori = ? AND durum = 'Yayında'");
$toplamQuery->execute([$kategori_id]);
$toplam = (int)$toplamQuery->fetchColumn();
$sayfasayisi = max(1, (int)ceil($toplam / $limit));
$sayfa = min($sayfa, $sayfasayisi);
$offset = ($sayfa - 1) * $limit;

$uyeQuery = $conn->prepare("SELECT id, seo, isim, resim, sehir FROM uyeler WHERE kategori = ? AND durum = 'Yayında' ORDER BY sira ASC LIMIT $limit OFFSET $offset");
$uyeQuery->execute([$kategori_id]);
$uyeler = $uyeQuery->fetchAll(PDO::FETCH_ASSOC);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = htmlspecialchars($kat["baslik"] ?? '');
if ($sayfa > 1) { $page_title .= " - Sayfa " . $sayfa; }
$page_title .= " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = htmlspecialchars($kat['meta'] ?? '');
$canonical_url = $site_url . '/kategori/' . htmlspecialchars($kat['seo'] ?? '') . '/' . intval($kat['id']) . '.html';
if ($sayfa > 1) { $canonical_url .= '?sayfa=' . $sayfa; }
$amp_path = '/kategori/' . htmlspecialchars($kat['seo'] ?? '') . '/' . intval($kat['id']) . '.html';

require_once("include/header.php");
?>
<body id="top">
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "<?= htmlspecialchars($kat['baslik']); ?>",
    "url": "<?= $site_url . '/kategori/' . htmlspecialchars($kat['seo']) . '/' . intval($kat['id']); ?>.html",
    "description": "<?= htmlspecialchars($kat['meta'] ?? ''); ?>",
    "isPartOf": {
        "@type": "WebSite",
        "name": "<?= htmlspecialchars($SiteBilgi['siteadi'] ?? ''); ?>",
        "url": "<?= $site_url; ?>"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Ana Sayfa", "item": "<?= $site_url; ?>/"},
        {"@type": "ListItem", "position": 2, "name": "<?= htmlspecialchars($kat['baslik']); ?>", "item": "<?= $site_url . '/kategori/' . htmlspecialchars($kat['seo']) . '/' . intval($kat['id']); ?>.html"}
    ]
}
</script>
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <div class="container" style="padding-top: 70px;">
        <ol class="breadcrumb">
            <li><a href="/">Ana Sayfa</a></li>
            <li class="active"><?= htmlspecialchars($kat['baslik']); ?></li>
        </ol>

        <h1><?= htmlspecialchars($kat['baslik']); ?></h1>
        <?php if (!empty($kat['aciklama']) && $sayfa === 1): ?>
        <div class="kategori-aciklama"><?= nl2br(htmlspecialchars($kat['aciklama'])); ?></div>
        <?php endif; ?>

        <?php if (count($uyeler) > 0): ?>
        <div class="row" style="margin-top:15px;">
            <?php foreach($uyeler as $uye): ?>
            <?php 
                $is_ad = (strpos($uye['resim'], 'platin') !== false || strpos($uye['isim'], 'REKLAM') !== false);
                $tg_link = !empty($AmpBilgi['amp_telegram']) ? htmlspecialchars($AmpBilgi['amp_telegram']) : 'https://t.me/bucamodel';
            ?>
            <div class="col-md-3 col-sm-4 col-xs-6">
                <a href="<?= $is_ad ? $tg_link : 'ilan/' . htmlspecialchars($uye['seo']) . '/' . intval($uye['id']) . '.html'; ?>" <?= $is_ad ? 'target="_blank" rel="noopener"' : ''; ?> class="thumbnail">
                    <img src="modelresim/<?= htmlspecialchars($uye['resim'] ?: 'resimyok.png'); ?>" alt="<?= htmlspecialchars($uye['isim']); ?>" width="250" height="350" loading="lazy" style="width:100%;height:auto;">
                    <div class="caption text-center">
                        <strong><?= htmlspecialchars($uye['isim']); ?></strong>
                        <?php if(!empty($uye['sehir'])): ?><br><small><?= htmlspecialchars($uye['sehir']); ?></small><?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($sayfasayisi > 1): ?>
        <nav aria-label="Sayfalama" class="text-center">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $sayfasayisi; $i++): ?>
                <li class="<?= $i === $sayfa ? 'active' : ''; ?>">
                    <a href="kategori/<?= htmlspecialchars($kat['seo']); ?>/<?= intval($kat['id']); ?>.html<?= $i > 1 ? '?sayfa=' . $i : ''; ?>"><?= $i; ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php else: ?>
        <p>Bu kategoride henüz yayında ilan bulunmuyor.</p>
        <?php endif; ?>
    </div>
</main>
<?php include('include/alt.php'); ?>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
