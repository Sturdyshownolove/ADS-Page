<?php
/**
 * AMP Header Template
 * Fully compliant AMP header with proper validation
 */

// Define access constant if not already defined
if (!defined('access')) {
    define('access', 'True');
}

// Set default values for missing variables
$EtiketBaslik = isset($EtiketBaslik) ? $EtiketBaslik : '';
$ilanYazdir = isset($ilanYazdir) ? $ilanYazdir : [];
$kat = isset($kat) ? $kat : [];
$yaziYaz = isset($yaziYaz) ? $yaziYaz : [];

// Get current page data
$currentPage = isset($currentPage) ? $currentPage : 'index';
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Generate dynamic meta data based on page type
$pageTitle = isset($SiteBilgi["siteadi"]) ? $SiteBilgi["siteadi"] : "Buca Elit Model";
$pageDescription = isset($SiteBilgi["meta"]) ? $SiteBilgi["meta"] : "Buca model hizmetleri, elit bayanlar ile unutulmaz anlar yaşayın.";
$pageKeywords = isset($SiteBilgi["anahtar"]) ? $SiteBilgi["anahtar"] : "Buca model, elit model, Buca bayan, model hizmetleri";
$pageImage = (isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/") . "img/model.webp";

// Page-specific meta data
switch ($currentPage) {
    case 'index':
        $pageTitle = (isset($SiteBilgi["siteadi"]) ? $SiteBilgi["siteadi"] : "Buca Elit Model") . " - " . (isset($SiteBilgi["logoyazi"]) ? $SiteBilgi["logoyazi"] : "Buca Elit Model Hizmetleri");
        break;
    case 'blog':
        $pageTitle = "Blog ve Makaleler - " . (isset($SiteBilgi["siteadi"]) ? $SiteBilgi["siteadi"] : "Buca Elit Model");
        $pageDescription = "En güncel blog yazıları ve makaleler. " . (isset($SiteBilgi["meta"]) ? $SiteBilgi["meta"] : "Buca model hizmetleri hakkında bilgiler.");
        break;
    case 'modeldetay':
        if (isset($ilanYazdir) && is_array($ilanYazdir) && isset($ilanYazdir['isim'])) {
            $pageTitle = htmlspecialchars($ilanYazdir['isim'], ENT_QUOTES, 'UTF-8') . " - " . (isset($SiteBilgi["siteadi"]) ? $SiteBilgi["siteadi"] : "Buca Elit Model");
            $pageDescription = isset($ilanYazdir['meta']) ? $ilanYazdir['meta'] : htmlspecialchars($ilanYazdir['isim'], ENT_QUOTES, 'UTF-8') . " hakkında detaylı bilgiler. " . (isset($SiteBilgi["meta"]) ? $SiteBilgi["meta"] : "Buca model hizmetleri");
            $pageImage = (isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/") . "modelresim/" . htmlspecialchars($ilanYazdir['resim'], ENT_QUOTES, 'UTF-8');
        }
        break;
    case 'blogdetay':
        if (isset($yaziYaz) && is_array($yaziYaz) && isset($yaziYaz['baslik'])) {
            $pageTitle = htmlspecialchars($yaziYaz['baslik'], ENT_QUOTES, 'UTF-8') . " - " . (isset($SiteBilgi["siteadi"]) ? $SiteBilgi["siteadi"] : "Buca Elit Model");
            $pageDescription = isset($yaziYaz['aciklama']) ? strip_tags($yaziYaz['aciklama']) : htmlspecialchars($yaziYaz['baslik'], ENT_QUOTES, 'UTF-8') . " hakkında detaylı bilgiler.";
            $pageImage = (isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/") . "blogresim/" . htmlspecialchars($yaziYaz['resim'], ENT_QUOTES, 'UTF-8');
        }
        break;
    case 'kategori':
        if (isset($kat) && is_array($kat) && isset($kat['kategoriadi'])) {
            $pageTitle = htmlspecialchars($kat['kategoriadi'], ENT_QUOTES, 'UTF-8') . " - " . (isset($SiteBilgi["siteadi"]) ? $SiteBilgi["siteadi"] : "Buca Elit Model");
            $pageDescription = htmlspecialchars($kat['kategoriadi'], ENT_QUOTES, 'UTF-8') . " kategorisindeki model bayanlar. " . (isset($SiteBilgi["meta"]) ? $SiteBilgi["meta"] : "Buca model hizmetleri");
        }
        break;
    case 'etiket':
        if (!empty($EtiketBaslik)) {
            $pageTitle = htmlspecialchars($EtiketBaslik, ENT_QUOTES, 'UTF-8') . " - " . (isset($SiteBilgi["siteadi"]) ? $SiteBilgi["siteadi"] : "Buca Elit Model");
            $pageDescription = htmlspecialchars($EtiketBaslik, ENT_QUOTES, 'UTF-8') . " etiketi ile ilgili model bayanlar. " . (isset($SiteBilgi["meta"]) ? $SiteBilgi["meta"] : "Buca model hizmetleri");
        }
        break;
}

// Clean and optimize meta data
$pageTitle = htmlspecialchars(trim($pageTitle), ENT_QUOTES, 'UTF-8');
$pageDescription = htmlspecialchars(trim($pageDescription), ENT_QUOTES, 'UTF-8');
$pageKeywords = htmlspecialchars(trim($pageKeywords), ENT_QUOTES, 'UTF-8');
$pageImage = htmlspecialchars(trim($pageImage), ENT_QUOTES, 'UTF-8');
// Canonical URL: masaüstü (desktop) sitesine işaret etmeli — AMP sayfası
// kendi URL'sini canonical yaparsa Google duplicate content olarak görür.
$desktop_site_url = rtrim($SiteBilgi['siteurl'] ?? '', '/');
$desktopCanonical = $desktop_site_url . ($_SERVER['REQUEST_URI'] ?? '/');
?>
<!doctype html>
<html ⚡ lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="<?php echo $pageKeywords; ?>">
    <meta name="theme-color" content="#732344">
    <link rel="canonical" href="<?php echo htmlspecialchars($desktopCanonical, ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- AMP Boilerplate -->
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style>
    <noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    
    <!-- AMP Runtime -->
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    
    <!-- AMP Components -->
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
    <script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style amp-custom>
        /* Reset and Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Quicksand', sans-serif;
            font-weight: 400;
            color: #f0f0f5;
            line-height: 1.44;
            font-size: 15px;
            background: #0b0c10;
        }
        
        .amp-wrapper {
            max-width: 780px;
            margin: 0 auto;
            background: #0b0c10;
            border: 1px solid rgba(255,255,255,0.08);
        }
        
        .wrap {
            padding: 2%;
        }
        
        /* Header Styles */
        .site-header {
            height: 52px;
            width: 100%;
            position: relative;
            margin: 0;
            color: #fff;
            background: linear-gradient(135deg, #ce0056 0%, #8a1f4b 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
        
        .site-header .branding {
            display: block;
            text-align: center;
            font-size: 20px;
            font-weight: 400;
            text-decoration: none;
            color: #fff;
            position: absolute;
            top: 0;
            width: 100%;
            padding: 10px 55px;
            z-index: 9;
            height: 52px;
            line-height: 32px;
        }
        
        .navbar-toggle {
            color: #fff;
            font-weight: 400;
            font-size: 18px;
            position: absolute;
            top: 0;
            z-index: 99;
            border: none;
            background: rgba(0,0,0,.1);
            height: 52px;
            line-height: 50px;
            margin: 0;
            padding: 0;
            width: 52px;
            text-align: center;
            outline: 0;
            cursor: pointer;
            left: 0;
        }
        
        /* Sidebar Styles */
        .sidebar-content {
            padding: 20px;
            background: #fff;
            height: 100vh;
        }
        
        .close-sidebar {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }
        
        .sidebar-content h3 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .sidebar-content ul {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-content li {
            margin-bottom: 10px;
        }
        
        .sidebar-content a {
            color: #333;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 8px 0;
        }
        
        .sidebar-content a:hover {
            color: #732344;
        }
        
        /* Content Styles */
        .sitebaslik {
            padding: 4px;
            background: <?php echo isset($AmpBilgi['amp_baslikarkarenk']) ? $AmpBilgi['amp_baslikarkarenk'] : '#333'; ?>;
            color: <?php echo isset($AmpBilgi['amp_baslikyazirenk']) ? $AmpBilgi['amp_baslikyazirenk'] : '#fff'; ?>;
            margin: 10px 0;
            font-size: 15px;
            text-align: center;
        }
        
        .paketbaslik {
            padding: 5px;
            background: <?php echo isset($AmpBilgi['amp_ilanarkarenk']) ? $AmpBilgi['amp_ilanarkarenk'] : '#ddd'; ?>;
            color: <?php echo isset($AmpBilgi['amp_ilanyazirenk']) ? $AmpBilgi['amp_ilanyazirenk'] : '#000'; ?>;
            text-align: center;
            margin: 10px 0;
            font-weight: 500;
        }
        
        /* Image Styles */
        .platin {
            height: auto;
            width: 100%;
            border: 2px solid transparent;
            float: left;
            margin: 2px 0;
        }
        
        .vip {
            height: auto;
            width: 50%;
            border: 2px solid transparent;
            float: left;
            margin: 2px 0;
        }
        
        .gold {
            height: auto;
            width: 25%;
            border: 2px solid transparent;
            float: left;
            margin: 2px 0;
        }
        
        .yan-cerceve {
            width: 75px;
            height: 75px;
            background: #fff;
            float: left;
            margin: 0;
            border: 2px solid #ddd;
            border-radius: 3px;
            margin-right: 7px;
        }
        
        .yan-resim {
            object-fit: contain;
            max-width: 100%;
            max-height: 100%;
        }
        
        /* Blog Styles */
        .blog-liste {
            margin-bottom: 30px;
            margin-top: 1px;
            padding: 5px;
        }
        
        .ilan-liste-2 {
            padding: 12px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .ilan-liste-2:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .post-title {
            font-size: 18px;
            margin: 0 0 10px;
        }
        
        .post-title a {
            color: #363636;
            text-decoration: none;
        }
        
        .post-thumbnail {
            margin-bottom: 4px;
        }
        
        .post-thumbnail amp-img {
            max-width: 100%;
            float: left;
            margin-right: 7px;
        }
        
        .post-excerpt {
            color: #333;
            font-weight: 500;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .post-read-more {
            font-size: 12px;
            background: #f9f9f9;
            border: 1px solid #d8d8d8;
            padding: 0 13px;
            border-radius: 33px;
            display: inline-block;
            line-height: 24px;
            color: #363636;
            text-decoration: none;
            float: right;
        }
        
        /* Utility Classes */
        .text-center {
            text-align: center;
        }
        
        .margintop20 {
            margin-top: 20px;
        }
        
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .small {
            font-size: 12px;
        }
        
        /* Footer Styles */
        .amp-footer {
            margin: 0;
            background: #f3f3f3;
        }
        
        .amp-copyright {
            padding: 17px 10px;
            text-align: center;
            font-weight: 400;
            color: #494949;
            border-top: 1px solid rgba(0,0,0,.1);
            font-size: 13px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .vip {
                width: 100%;
            }
            
            .gold {
                width: 50%;
            }
        }
        
        @media (max-width: 480px) {
            .gold {
                width: 100%;
            }
        }

        /* Category Detail Styles */
        .category-detail {
            padding: 20px;
        }

        .category-description {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .category-description p {
            line-height: 1.6;
            color: #333;
            margin: 0;
        }

        .modell-list h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        .modell-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .modell-item {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .modell-item:hover {
            transform: translateY(-5px);
        }

        .modell-item a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .modell-item h3 {
            padding: 15px;
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .modell-item p {
            padding: 0 15px 15px;
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .no-modells {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .back-to-categories {
            text-align: center;
            margin-top: 40px;
        }

        .back-btn {
            display: inline-block;
            background: #732344;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
        }

        .back-btn:hover {
            background: #5a1c35;
        }

        @media (max-width: 768px) {
            .modell-list {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
            }
        }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(0, 136, 204, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(0, 136, 204, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 136, 204, 0); }
        }
        .telegram-btn { background: linear-gradient(135deg, #0088cc 0%, #005580 100%); color: #ffffff; padding: 7px 15px; border-radius: 20px; text-decoration: none; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; animation: pulseGlow 2s infinite; float: right; margin: 8px; }
        .telegram-btn:hover { background: #0077b3; color: #ffffff; text-decoration: none; }
    </style>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?php echo $pageTitle; ?>",
        "url": "<?php echo $currentUrl; ?>",
        "logo": "<?php echo $pageImage; ?>",
        "description": "<?php echo $pageDescription; ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Buca",
            "addressCountry": "TR"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "<?php echo isset($SiteBilgi['telefon']) ? htmlspecialchars($SiteBilgi['telefon'], ENT_QUOTES, 'UTF-8') : ''; ?>",
            "contactType": "customer service"
        }
    }
    </script>
</head>

<body>
    <div class="amp-wrapper">
        <!-- Header -->
        <header class="site-header">
            <a href="<?php echo htmlspecialchars($AmpBilgi['amp_telegram'] ?? 'https://t.me/bucamodel', ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="telegram-btn">✈ Reklam Ver</a>
            <a href="<?php echo isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/"; ?>" class="branding">
                <?php echo isset($SiteBilgi["logoyazi"]) ? htmlspecialchars($SiteBilgi["logoyazi"], ENT_QUOTES, 'UTF-8') : 'Buca Elit Model'; ?>
            </a>
        </header>
        
        <!-- Sidebar -->
        <amp-sidebar id="sidebar" layout="nodisplay" side="left">
            <div class="sidebar-content">
                <button class="close-sidebar" on="tap:sidebar.close" aria-label="Close sidebar">×</button>
                <h3>Menü</h3>
                <ul>
                    <li><a href="<?php echo isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/"; ?>">Ana Sayfa</a></li>
                    <li><a href="<?php echo isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/"; ?>blog.php">Blog</a></li>
                    <li><a href="tel:<?php echo isset($SiteBilgi['telefon']) ? htmlspecialchars($SiteBilgi['telefon'], ENT_QUOTES, 'UTF-8') : ''; ?>">Ara</a></li>
                </ul>
            </div>
        </amp-sidebar>
        
        <!-- Main Content -->
        <main class="wrap">
