<?php
// Bu header dosyası, çağrıldığı sayfada aşağıdaki değişkenlerin tanımlanmış olmasını bekler:
// $page_title (gerekli), $page_description (gerekli), $canonical_url (gerekli)
// $amp_page_url (önerilir) - sayfanın AMP karşılığı; tanımlıysa mobil ziyaretçi oraya yönlendirilir
// $prev_url, $next_url, $og_image_url, $og_type, $meta_robots (opsiyonel)

$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$amp_domain = rtrim(htmlspecialchars($AmpBilgi["amp_domain"] ?? '', ENT_QUOTES, 'UTF-8'), '/');

// $amp_page_url tanımlı değilse ve $amp_path verilmişse AMP domain'inden kur
if (empty($amp_page_url) && !empty($amp_path) && $amp_domain) {
    $amp_page_url = $amp_domain . $amp_path;
}

// Mobil ziyaretçiyi AMP sürümüne yönlendir (çıktıdan ÖNCE çalışmalı)
require_once(__DIR__ . "/mobilyonlendir.php");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $site_url . '/'; ?>">

    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title><?= $page_title ?? 'Başlık Belirtilmemiş'; ?></title>
    <meta name="description" content="<?= $page_description ?? ''; ?>" />
    <?php if(!empty($SiteBilgi['anahtar'])): ?><meta name="keywords" content="<?= htmlspecialchars($SiteBilgi['anahtar'], ENT_QUOTES, 'UTF-8'); ?>" /><?php endif; ?>
    <?php if (!empty($meta_robots)): ?><meta name="robots" content="<?= $meta_robots; ?>" /><?php endif; ?>
    <meta name="rating" content="RTA-5042-1996-1400-1577-RTA">
    <link rel="canonical" href="<?= $canonical_url ?? $site_url; ?>" />

    <?php if (!empty($amp_page_url)): ?>
    <link rel="amphtml" href="<?= $amp_page_url; ?>" />
    <link rel="alternate" media="only screen and (max-width: 640px)" href="<?= $amp_page_url; ?>" />
    <?php endif; ?>

    <?php if (isset($prev_url) && $prev_url): ?><link rel="prev" href="<?= $prev_url; ?>" /><?php endif; ?>
    <?php if (isset($next_url) && $next_url): ?><link rel="next" href="<?= $next_url; ?>" /><?php endif; ?>

    <meta property="og:title" content="<?= $page_title ?? ''; ?>" />
    <meta property="og:description" content="<?= $page_description ?? ''; ?>" />
    <meta property="og:image" content="<?= $og_image_url ?? $site_url . '/img/og-image.jpg'; ?>" />
    <meta property="og:url" content="<?= $canonical_url ?? $site_url; ?>" />
    <meta property="og:type" content="<?= $og_type ?? 'website'; ?>" />
    <meta property="og:site_name" content="<?= htmlspecialchars($SiteBilgi['siteadi'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:locale" content="tr_TR" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= $page_title ?? ''; ?>" />
    <meta name="twitter:description" content="<?= $page_description ?? ''; ?>" />
    <meta name="twitter:image" content="<?= $og_image_url ?? $site_url . '/img/og-image.jpg'; ?>" />

    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
