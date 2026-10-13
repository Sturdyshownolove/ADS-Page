<?php
define('access', "False");
require_once("include/connect.php");
require_once("include/tarihfonksiyonu.php");

// --- SAYFALAMA ---
$limit = 20;
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$toplam = (int)$conn->query("SELECT COUNT(*) FROM blog WHERE durum = 'Aktif'")->fetchColumn();
$sayfasayisi = max(1, (int)ceil($toplam / $limit));
$sayfa = min($sayfa, $sayfasayisi);
$offset = ($sayfa - 1) * $limit;

$yazilar = $conn->query("
    SELECT id, seo, baslik, yayinlanma_tarihi FROM blog
    WHERE durum = 'Aktif' ORDER BY id DESC LIMIT $limit OFFSET $offset
")->fetchAll(PDO::FETCH_ASSOC);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = "Tüm Blog Yazıları";
if ($sayfa > 1) { $page_title .= " - Sayfa " . $sayfa; }
$page_title .= " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = "Sitemizdeki tüm blog yazıları ve makaleler.";
$canonical_url = $site_url . '/tum-yazilar';
if ($sayfa > 1) { $canonical_url .= '/sayfa/' . $sayfa; }
$prev_url = ($sayfa > 1) ? $site_url . '/tum-yazilar' . ($sayfa > 2 ? '/sayfa/' . ($sayfa - 1) : '') : null;
$next_url = ($sayfa < $sayfasayisi) ? $site_url . '/tum-yazilar/sayfa/' . ($sayfa + 1) : null;

require_once("include/header.php");
?>
<body id="top">
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <div class="container" style="padding-top: 70px;">
        <h1>Tüm Blog Yazıları<?= $sayfa > 1 ? ' - Sayfa ' . $sayfa : ''; ?></h1>

        <?php if (count($yazilar) > 0): ?>
        <div class="list-group">
            <?php foreach($yazilar as $yazi): ?>
            <a href="yazi/<?= htmlspecialchars($yazi['seo']); ?>/<?= intval($yazi['id']); ?>.html" class="list-group-item">
                <?= htmlspecialchars($yazi['baslik']); ?>
                <span class="pull-right text-muted"><small><?= turkcetarih('d MMMM yyyy', $yazi['yayinlanma_tarihi']); ?></small></span>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if ($sayfasayisi > 1): ?>
        <nav aria-label="Sayfalama" class="text-center">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $sayfasayisi; $i++): ?>
                <li class="<?= $i === $sayfa ? 'active' : ''; ?>">
                    <a href="tum-yazilar<?= $i > 1 ? '/sayfa/' . $i : ''; ?>"><?= $i; ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php else: ?>
        <p>Henüz yayınlanmış yazı bulunmuyor.</p>
        <?php endif; ?>
    </div>
</main>
<?php include('include/alt.php'); ?>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
