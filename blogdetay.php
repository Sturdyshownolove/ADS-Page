<?php
define('access', "False");
require_once("include/connect.php");
require_once("include/tarihfonksiyonu.php");

// --- ID KONTROLÜ VE VERİ ÇEKME ---
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($blog_id <= 0) { include('404.php'); exit; }

$blogQuery = $conn->prepare("
    SELECT b.*, k.baslik AS kategori_baslik, k.seo AS kategori_seo
    FROM blog AS b
    LEFT JOIN kategori AS k ON b.kategori = k.id
    WHERE b.id = ? AND b.durum = 'Aktif'
");
$blogQuery->execute([$blog_id]);
$yaziYaz = $blogQuery->fetch();
if (!$yaziYaz) { include('404.php'); exit; }

// Etiketler
$tagsQuery = $conn->prepare("SELECT baslik, seo FROM etiketler WHERE konu = ?");
$tagsQuery->execute([$blog_id]);
$tags = $tagsQuery->fetchAll(PDO::FETCH_ASSOC);

// Aynı kategoriden ilgili yazılar
$ilgiliQuery = $conn->prepare("SELECT id, seo, baslik, resim, yayinlanma_tarihi FROM blog WHERE kategori = ? AND durum = 'Aktif' AND id != ? ORDER BY id DESC LIMIT 4");
$ilgiliQuery->execute([intval($yaziYaz['kategori']), $blog_id]);
$ilgiliYazilar = $ilgiliQuery->fetchAll(PDO::FETCH_ASSOC);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = htmlspecialchars($yaziYaz["baslik"]) . " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = htmlspecialchars($yaziYaz['meta'] ?: mb_substr(strip_tags($yaziYaz['aciklama'] ?? ''), 0, 160));
$canonical_url = $site_url . '/yazi/' . htmlspecialchars($yaziYaz['seo']) . '/' . intval($yaziYaz['id']) . '.html';
$og_image_url = $site_url . '/blogresim/' . htmlspecialchars($yaziYaz['resim'] ?? '');
$og_type = 'article';
$amp_path = '/yazi/' . htmlspecialchars($yaziYaz['seo']) . '/' . intval($yaziYaz['id']) . '.html';
$yayinTarihi = !empty($yaziYaz['yayinlanma_tarihi']) ? date('c', strtotime($yaziYaz['yayinlanma_tarihi'])) : null;

require_once("include/header.php");
?>
<body id="top">
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "<?= htmlspecialchars($yaziYaz['baslik']); ?>",
    "image": "<?= $og_image_url; ?>",
    "url": "<?= $canonical_url; ?>",
    <?php if ($yayinTarihi): ?>"datePublished": "<?= $yayinTarihi; ?>",<?php endif; ?>
    "description": "<?= $page_description; ?>",
    "publisher": {
        "@type": "Organization",
        "name": "<?= htmlspecialchars($SiteBilgi['siteadi'] ?? ''); ?>",
        "url": "<?= $site_url; ?>"
    },
    "author": {
        "@type": "Organization",
        "name": "<?= htmlspecialchars($SiteBilgi['siteadi'] ?? ''); ?>"
    },
    "mainEntityOfPage": "<?= $canonical_url; ?>"
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Ana Sayfa", "item": "<?= $site_url; ?>/"},
        {"@type": "ListItem", "position": 2, "name": "Blog", "item": "<?= $site_url; ?>/blog"},
        {"@type": "ListItem", "position": 3, "name": "<?= htmlspecialchars($yaziYaz['baslik']); ?>", "item": "<?= $canonical_url; ?>"}
    ]
}
</script>
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <div class="container" style="padding-top: 70px;">
        <div class="row">
            <div class="col-md-8">
                <ol class="breadcrumb">
                    <li><a href="/">Ana Sayfa</a></li>
                    <li><a href="blog">Blog</a></li>
                    <li class="active"><?= htmlspecialchars($yaziYaz['baslik']); ?></li>
                </ol>

                <article>
                    <h1><?= htmlspecialchars($yaziYaz['baslik']); ?></h1>
                    <p class="text-muted">
                        <?php if(!empty($yaziYaz['yayinlanma_tarihi'])): ?>
                        <time datetime="<?= htmlspecialchars($yayinTarihi); ?>"><?= turkcetarih('d MMMM yyyy', $yaziYaz['yayinlanma_tarihi']); ?></time>
                        <?php endif; ?>
                        <?php if(!empty($yaziYaz['kategori_baslik'])): ?>
                        · <a href="kategori/<?= htmlspecialchars($yaziYaz['kategori_seo']); ?>/<?= intval($yaziYaz['kategori']); ?>.html"><?= htmlspecialchars($yaziYaz['kategori_baslik']); ?></a>
                        <?php endif; ?>
                    </p>

                    <?php if (!empty($yaziYaz['resim'])): ?>
                    <img src="blogresim/<?= htmlspecialchars($yaziYaz['resim']); ?>" alt="<?= htmlspecialchars($yaziYaz['baslik']); ?>" width="750" height="422" style="width:100%;height:auto;margin-bottom:15px;">
                    <?php endif; ?>

                    <div class="yazi-icerik">
                        <?= $yaziYaz['aciklama']; // Panelden girilen HTML içerik ?>
                    </div>

                    <?php if (count($tags) > 0): ?>
                    <p style="margin-top:20px;">
                        <strong>Etiketler:</strong>
                        <?php foreach($tags as $tag): ?>
                        <a href="etiket/<?= htmlspecialchars($tag['seo']); ?>.html" class="label label-default"><?= htmlspecialchars($tag['baslik']); ?></a>
                        <?php endforeach; ?>
                    </p>
                    <?php endif; ?>
                </article>
            </div>
            <div class="col-md-4">
                <?php if (count($ilgiliYazilar) > 0): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 style="margin:0;font-size:16px;">İlgili Yazılar</h2></div>
                    <div class="list-group">
                        <?php foreach($ilgiliYazilar as $ilgili): ?>
                        <a href="yazi/<?= htmlspecialchars($ilgili['seo']); ?>/<?= intval($ilgili['id']); ?>.html" class="list-group-item">
                            <?= htmlspecialchars($ilgili['baslik']); ?>
                            <br><small class="text-muted"><?= turkcetarih('d MMMM yyyy', $ilgili['yayinlanma_tarihi']); ?></small>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include('include/alt.php'); ?>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
