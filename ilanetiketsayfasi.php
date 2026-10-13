<?php
define('access', "False");
require_once("include/connect.php");
require_once("include/tarihfonksiyonu.php");

// --- ETİKET KONTROLÜ VE VERİ ÇEKME ---
$etiket_seo = isset($_GET['etiket']) ? trim($_GET['etiket']) : '';
if ($etiket_seo === '') { include('404.php'); exit; }

$etiketQuery = $conn->prepare("SELECT baslik, seo FROM uyeetiketler WHERE seo = ? LIMIT 1");
$etiketQuery->execute([$etiket_seo]);
$etiket = $etiketQuery->fetch();
if (!$etiket) { include('404.php'); exit; }
$EtiketBaslik = $etiket['baslik'];

// --- SAYFALAMA ---
$limit = 12;
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$toplamQuery = $conn->prepare("
    SELECT COUNT(DISTINCT u.id) FROM uyeler u
    JOIN uyeetiketler e ON e.konu = u.id
    WHERE e.seo = ? AND u.durum = 'Yayında'
");
$toplamQuery->execute([$etiket_seo]);
$toplam = (int)$toplamQuery->fetchColumn();
$sayfasayisi = max(1, (int)ceil($toplam / $limit));
$sayfa = min($sayfa, $sayfasayisi);
$offset = ($sayfa - 1) * $limit;

$uyeQuery = $conn->prepare("
    SELECT DISTINCT u.id, u.seo, u.isim, u.resim, u.sehir, u.sira
    FROM uyeler u
    JOIN uyeetiketler e ON e.konu = u.id
    WHERE e.seo = ? AND u.durum = 'Yayında'
    ORDER BY u.sira ASC LIMIT $limit OFFSET $offset
");
$uyeQuery->execute([$etiket_seo]);
$uyeler = $uyeQuery->fetchAll(PDO::FETCH_ASSOC);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = "Etiket: " . htmlspecialchars($EtiketBaslik);
if ($sayfa > 1) { $page_title = "Sayfa " . $sayfa . " - " . $page_title; }
$page_title .= " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = htmlspecialchars($EtiketBaslik) . " etiketine sahip tüm ilanlar.";
$canonical_url = $site_url . '/ilan-etiket/' . htmlspecialchars($etiket['seo']) . ($sayfa > 1 ? '/sayfa/' . $sayfa : '.html');
// Etiket sayfaları ince içerik — indexe kapat, linkleri izle
$meta_robots = "noindex, follow";

require_once("include/header.php");
?>
<body id="top">
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <div class="container" style="padding-top: 70px;">
        <h1>Etiket: <?= htmlspecialchars($EtiketBaslik); ?></h1>

        <?php if (count($uyeler) > 0): ?>
        <div class="row" style="margin-top:15px;">
            <?php foreach($uyeler as $uye): ?>
            <div class="col-md-3 col-sm-4 col-xs-6">
                <a href="ilan/<?= htmlspecialchars($uye['seo']); ?>/<?= intval($uye['id']); ?>.html" class="thumbnail">
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
                    <a href="ilan-etiket/<?= htmlspecialchars($etiket['seo']); ?><?= $i > 1 ? '/sayfa/' . $i : '.html'; ?>"><?= $i; ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php else: ?>
        <p>Bu etikete ait yayında ilan bulunmuyor.</p>
        <?php endif; ?>
    </div>
</main>
<?php include('include/alt.php'); ?>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
