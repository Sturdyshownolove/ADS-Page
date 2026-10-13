<?php
define('access', "False");
require_once("include/connect.php");
require_once("include/tarihfonksiyonu.php");

// --- ETİKET KONTROLÜ VE VERİ ÇEKME ---
$etiket_seo = isset($_GET['etiket']) ? trim($_GET['etiket']) : '';
if ($etiket_seo === '') { include('404.php'); exit; }

$etiketQuery = $conn->prepare("SELECT baslik, seo FROM etiketler WHERE seo = ? LIMIT 1");
$etiketQuery->execute([$etiket_seo]);
$etiket = $etiketQuery->fetch();
if (!$etiket) { include('404.php'); exit; }
$EtiketBaslik = $etiket['baslik'];

// --- SAYFALAMA ---
$limit = 10;
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$toplamQuery = $conn->prepare("
    SELECT COUNT(DISTINCT b.id) FROM blog b
    JOIN etiketler e ON e.konu = b.id
    WHERE e.seo = ? AND b.durum = 'Aktif'
");
$toplamQuery->execute([$etiket_seo]);
$toplam = (int)$toplamQuery->fetchColumn();
$sayfasayisi = max(1, (int)ceil($toplam / $limit));
$sayfa = min($sayfa, $sayfasayisi);
$offset = ($sayfa - 1) * $limit;

$yaziQuery = $conn->prepare("
    SELECT DISTINCT b.id, b.seo, b.baslik, b.resim, b.aciklama, b.yayinlanma_tarihi
    FROM blog b
    JOIN etiketler e ON e.konu = b.id
    WHERE e.seo = ? AND b.durum = 'Aktif'
    ORDER BY b.id DESC LIMIT $limit OFFSET $offset
");
$yaziQuery->execute([$etiket_seo]);
$yazilar = $yaziQuery->fetchAll(PDO::FETCH_ASSOC);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = "Etiket: " . htmlspecialchars($EtiketBaslik);
if ($sayfa > 1) { $page_title = "Sayfa " . $sayfa . " - " . $page_title; }
$page_title .= " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = htmlspecialchars($EtiketBaslik) . " etiketine sahip tüm blog yazıları.";
$canonical_url = $site_url . '/etiket/' . htmlspecialchars($etiket['seo']) . ($sayfa > 1 ? '/sayfa/' . $sayfa : '.html');
// Etiket sayfaları ince içerik — indexe kapat, linkleri izle
$meta_robots = "noindex, follow";

require_once("include/header.php");
?>
<body id="top">
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <div class="container" style="padding-top: 70px;">
        <h1>Etiket: <?= htmlspecialchars($EtiketBaslik); ?></h1>

        <?php if (count($yazilar) > 0): ?>
        <?php foreach($yazilar as $yazi): ?>
        <article class="media" style="margin-bottom:20px;">
            <div class="media-left">
                <a href="yazi/<?= htmlspecialchars($yazi['seo']); ?>/<?= intval($yazi['id']); ?>.html">
                    <img class="media-object" src="blogresim/<?= htmlspecialchars($yazi['resim']); ?>" alt="<?= htmlspecialchars($yazi['baslik']); ?>" width="120" height="120" loading="lazy" style="object-fit:cover;">
                </a>
            </div>
            <div class="media-body">
                <h2 class="media-heading" style="font-size:18px;">
                    <a href="yazi/<?= htmlspecialchars($yazi['seo']); ?>/<?= intval($yazi['id']); ?>.html"><?= htmlspecialchars($yazi['baslik']); ?></a>
                </h2>
                <small class="text-muted"><?= turkcetarih('d MMMM yyyy', $yazi['yayinlanma_tarihi']); ?></small>
                <p style="margin-top:5px;"><?= htmlspecialchars(mb_substr(strip_tags($yazi['aciklama']), 0, 200)); ?>...</p>
            </div>
        </article>
        <?php endforeach; ?>

        <?php if ($sayfasayisi > 1): ?>
        <nav aria-label="Sayfalama" class="text-center">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $sayfasayisi; $i++): ?>
                <li class="<?= $i === $sayfa ? 'active' : ''; ?>">
                    <a href="etiket/<?= htmlspecialchars($etiket['seo']); ?><?= $i > 1 ? '/sayfa/' . $i : '.html'; ?>"><?= $i; ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php else: ?>
        <p>Bu etikete ait yayınlanmış yazı bulunmuyor.</p>
        <?php endif; ?>
    </div>
</main>
<?php include('include/alt.php'); ?>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
