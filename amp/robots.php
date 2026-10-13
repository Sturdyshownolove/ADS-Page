<?php
// AMP domain'i için dinamik robots.txt — sitemap her zaman ana (masaüstü) sitededir.
require_once("../include/connect.php");
$site_url = rtrim($SiteBilgi['siteurl'] ?? '', '/');

header("Content-Type: text/plain; charset=UTF-8");
?>
User-agent: *
Allow: /

Sitemap: <?= $site_url; ?>/sitemap.xml
