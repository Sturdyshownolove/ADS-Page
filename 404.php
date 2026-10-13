<?php
define('access', "False");
require_once("include/connect.php");

http_response_code(404);

// --- SEO DEĞİŞKENLERİ ---
$site_url = rtrim(htmlspecialchars($SiteBilgi["siteurl"] ?? '', ENT_QUOTES, 'UTF-8'), '/');
$page_title = "Sayfa Bulunamadı - " . htmlspecialchars($SiteBilgi['siteadi'] ?? '');
$page_description = "Aradığınız sayfa bulunamadı veya taşınmış olabilir.";
$canonical_url = $site_url;
$meta_robots = "noindex, follow";

require_once("include/header.php");
?>
<body id="top">
<?php include('include/ustmenu.php'); ?>
<main role="main">
    <div class="container" style="padding-top: 80px; text-align: center;">
        <h1>404 - Sayfa Bulunamadı</h1>
        <p>Aradığınız sayfa bulunamadı, kaldırılmış ya da taşınmış olabilir.</p>
        <p><a href="/" class="btn btn-primary">Ana Sayfaya Dön</a> <a href="/blog" class="btn btn-default">Blog Yazıları</a></p>
    </div>
</main>
<?php include('include/alt.php'); ?>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
