<?php
define('access', "False");
require_once("include/connect.php");
require_once("include/tarihfonksiyonu.php");

// --- ID KONTROLÜ VE VERİ ÇEKME ---
$ilan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($ilan_id <= 0) { include('404.php'); exit; }

$ilanQuery = $conn->prepare("
    SELECT u.*, k.baslik AS kategori_baslik, k.seo AS kategori_seo
    FROM uyeler AS u
    LEFT JOIN kategori AS k ON u.kategori = k.id
    WHERE u.id = ? AND u.durum = 'Yayında'
");
$ilanQuery->execute([$ilan_id]);
$ilanYazdir = $ilanQuery->fetch();
if (!$ilanYazdir) { include('404.php'); exit; }

// Galeri resimleri
$galleryQuery = $conn->prepare("SELECT resim FROM resimler WHERE uye_id = ? ORDER BY id ASC");
$galleryQuery->execute([$ilan_id]);
$galleryImages = $galleryQuery->fetchAll(PDO::FETCH_COLUMN);
$anaResim = $galleryImages[0] ?? null;

// İlan etiketleri
$tagQuery = $conn->prepare("SELECT baslik, seo FROM uyeetiketler WHERE konu = ?");
$tagQuery->execute([$ilan_id]);
$ilanEtiketleri = $tagQuery->fetchAll(PDO::FETCH_ASSOC);

// Aynı kategoriden diğer ilanlar
$benzerQuery = $conn->prepare("SELECT id, seo, isim, resim FROM uyeler WHERE kategori = ? AND durum = 'Yayında' AND id != ? ORDER BY sira ASC LIMIT 4");
$benzerQuery->execute([intval($ilanYazdir['kategori']), $ilan_id]);
$benzerIlanlar = $benzerQuery->fetchAll(PDO::FETCH_ASSOC);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = htmlspecialchars($ilanYazdir["isim"]) . " - " . htmlspecialchars($SiteBilgi["siteadi"] ?? '');
$page_description = htmlspecialchars($ilanYazdir['meta'] ?: mb_substr(strip_tags($ilanYazdir['aciklama'] ?? ''), 0, 160));
$canonical_url = $site_url . '/ilan/' . htmlspecialchars($ilanYazdir['seo']) . '/' . intval($ilanYazdir['id']) . '.html';
$og_image_url = $site_url . '/modelresim/' . htmlspecialchars($anaResim ?: ($ilanYazdir['resim'] ?? 'resimyok.png'));
$amp_path = '/ilan/' . htmlspecialchars($ilanYazdir['seo']) . '/' . intval($ilanYazdir['id']) . '.html';

require_once("include/header.php");
?>
<body id="top">
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "<?= htmlspecialchars($ilanYazdir["isim"]); ?>",
    "image": "<?= $og_image_url; ?>",
    "url": "<?= $canonical_url; ?>",
    "description": "<?= $page_description; ?>"
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Ana Sayfa", "item": "<?= $site_url; ?>/"},
        {"@type": "ListItem", "position": 2, "name": "<?= htmlspecialchars($ilanYazdir['kategori_baslik'] ?? 'Kategori'); ?>", "item": "<?= $site_url; ?>/kategori/<?= htmlspecialchars($ilanYazdir['kategori_seo'] ?? ''); ?>/<?= intval($ilanYazdir['kategori']); ?>.html"},
        {"@type": "ListItem", "position": 3, "name": "<?= htmlspecialchars($ilanYazdir['isim']); ?>", "item": "<?= $canonical_url; ?>"}
    ]
}
</script>
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <div class="container" style="padding-top: 70px;">
        <ol class="breadcrumb">
            <li><a href="/">Ana Sayfa</a></li>
            <?php if(!empty($ilanYazdir['kategori_baslik'])): ?>
            <li><a href="kategori/<?= htmlspecialchars($ilanYazdir['kategori_seo']); ?>/<?= intval($ilanYazdir['kategori']); ?>.html"><?= htmlspecialchars($ilanYazdir['kategori_baslik']); ?></a></li>
            <?php endif; ?>
            <li class="active"><?= htmlspecialchars($ilanYazdir['isim']); ?></li>
        </ol>

        <h1><?= htmlspecialchars($ilanYazdir['isim']); ?></h1>
        <p class="text-muted">
            <?php if(!empty($ilanYazdir['sehir'])): ?><span><?= htmlspecialchars($ilanYazdir['sehir']); ?></span><?php endif; ?>
            <?php if(!empty($ilanYazdir['semt'])): ?> / <span><?= htmlspecialchars($ilanYazdir['semt']); ?></span><?php endif; ?>
        </p>

        <div class="row">
            <div class="col-md-5">
                <?php if (count($galleryImages) > 0): ?>
                <div id="ilanGaleri" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($galleryImages as $i => $image): ?>
                        <div class="item <?= $i === 0 ? 'active' : ''; ?>">
                            <img src="modelresim/<?= htmlspecialchars($image); ?>" alt="<?= htmlspecialchars($ilanYazdir['isim']); ?> - <?= $i + 1; ?>" width="400" height="533" <?= $i > 0 ? 'loading="lazy"' : ''; ?> style="width:100%;height:auto;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($galleryImages) > 1): ?>
                    <a class="left carousel-control" href="#ilanGaleri" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
                    <a class="right carousel-control" href="#ilanGaleri" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
                    <?php endif; ?>
                </div>
                <?php elseif (!empty($ilanYazdir['resim'])): ?>
                <img src="modelresim/<?= htmlspecialchars($ilanYazdir['resim']); ?>" alt="<?= htmlspecialchars($ilanYazdir['isim']); ?>" width="400" height="533" style="width:100%;height:auto;">
                <?php endif; ?>

                <?php if (!empty($ilanYazdir['telefon'])): ?>
                <div style="margin-top:15px;">
                    <a class="btn btn-primary btn-block btn-lg" href="tel:<?= htmlspecialchars($ilanYazdir['telefon']); ?>" rel="nofollow">TELEFONLA ARA</a>
                    <a class="btn btn-success btn-block btn-lg" href="https://wa.me/<?= formatWhatsappNumber($ilanYazdir['telefon']); ?>?text=<?= rawurlencode('Merhaba numaranızı Buca Kale sitesinden buldum sizinle görüşmek istiyorum detaylı bilgi alabilir miyim?'); ?>" target="_blank" rel="nofollow noopener">WHATSAPP'TAN YAZ</a>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 style="margin:0;font-size:18px;">Hakkında</h2></div>
                    <div class="panel-body">
                        <?= $ilanYazdir['aciklama']; // Panelden girilen HTML içerik ?>
                    </div>
                </div>

                <?php if (count($ilanEtiketleri) > 0): ?>
                <p>
                    <strong>Etiketler:</strong>
                    <?php foreach($ilanEtiketleri as $tag): ?>
                    <a href="ilan-etiket/<?= htmlspecialchars($tag['seo']); ?>.html" class="label label-default"><?= htmlspecialchars($tag['baslik']); ?></a>
                    <?php endforeach; ?>
                </p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (count($benzerIlanlar) > 0): ?>
        <section>
            <h2 class="page-header">Aynı Kategoriden Diğer İlanlar</h2>
            <div class="row">
                <?php foreach($benzerIlanlar as $benzer): ?>
                <div class="col-md-3 col-xs-6">
                    <a href="ilan/<?= htmlspecialchars($benzer['seo']); ?>/<?= intval($benzer['id']); ?>.html" class="thumbnail">
                        <img src="modelresim/<?= htmlspecialchars($benzer['resim'] ?: 'resimyok.png'); ?>" alt="<?= htmlspecialchars($benzer['isim']); ?>" width="250" height="350" loading="lazy" style="width:100%;height:auto;">
                        <div class="caption text-center"><strong><?= htmlspecialchars($benzer['isim']); ?></strong></div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</main>
<?php include('include/alt.php'); ?>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
