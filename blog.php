<?php
define('access', "False");
require_once("include/connect.php");
require_once("include/tarihfonksiyonu.php");

// --- SAYFALAMA ---
$limit = 10;
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$toplam = (int)$conn->query("SELECT COUNT(*) FROM blog WHERE durum = 'Aktif'")->fetchColumn();
$sayfasayisi = max(1, (int)ceil($toplam / $limit));
$sayfa = min($sayfa, $sayfasayisi);
$offset = ($sayfa - 1) * $limit;

$makaleler = $conn->query("
    SELECT b.id, b.seo, b.baslik, b.resim, b.aciklama, b.yayinlanma_tarihi, b.kategori, k.seo AS kategori_seo, k.baslik AS kategori_baslik
    FROM blog AS b LEFT JOIN kategori AS k ON b.kategori = k.id
    WHERE b.durum = 'Aktif' ORDER BY b.id DESC LIMIT $limit OFFSET $offset
")->fetchAll(PDO::FETCH_ASSOC);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = "Blog ve Makaleler";
if ($sayfa > 1) { $page_title .= " - Sayfa " . $sayfa; }
$page_title .= " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = "Sitemizde yayınlanan en son makaleler ve blog yazıları.";
$canonical_url = $site_url . '/blog';
if ($sayfa > 1) { $canonical_url .= '/sayfa/' . $sayfa; }
$prev_url = ($sayfa > 1) ? $site_url . '/blog' . ($sayfa > 2 ? '/sayfa/' . ($sayfa - 1) : '') : null;
$next_url = ($sayfa < $sayfasayisi) ? $site_url . '/blog/sayfa/' . ($sayfa + 1) : null;
$amp_path = '/blog';

require_once("include/header.php");
?>
<body id="top">
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <div class="container" style="padding-top: 70px;">
        <h1>Blog ve Makaleler<?= $sayfa > 1 ? ' - Sayfa ' . $sayfa : ''; ?></h1>

        <?php if (count($makaleler) > 0): ?>
        <?php foreach($makaleler as $yazi): ?>
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
                <small class="text-muted">
                    <?= turkcetarih('d MMMM yyyy', $yazi['yayinlanma_tarihi']); ?> ·
                    <a href="kategori/<?= htmlspecialchars($yazi['kategori_seo']); ?>/<?= intval($yazi['kategori']); ?>.html"><?= htmlspecialchars($yazi['kategori_baslik']); ?></a>
                </small>
                <p style="margin-top:5px;"><?= htmlspecialchars(mb_substr(strip_tags($yazi['aciklama']), 0, 200)); ?>...</p>
            </div>
        </article>
        <?php endforeach; ?>

        <?php if ($sayfasayisi > 1): ?>
        <nav aria-label="Sayfalama" class="text-center">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $sayfasayisi; $i++): ?>
                <li class="<?= $i === $sayfa ? 'active' : ''; ?>">
                    <a href="blog<?= $i > 1 ? '/sayfa/' . $i : ''; ?>"><?= $i; ?></a>
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
