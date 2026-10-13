<?php
// Sitemap yalnızca masaüstü (canonical) URL'leri içerir — AMP domain'inden
// gelen sitemap istekleri kalıcı olarak ana sitenin sitemap'ine yönlendirilir.
require_once("../include/connect.php");
$site_url = rtrim($SiteBilgi['siteurl'] ?? '', '/');
header('Location: ' . $site_url . '/sitemap.xml', true, 301);
exit;
