<?php
require_once("include/connect.php");
$site_url = rtrim($SiteBilgi['siteurl'] ?? '', '/');

header("Content-Type: text/xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Yalnızca masaüstü (canonical) URL'ler listelenir; AMP URL'leri sitemap'e girmez.
// lastmod sadece gerçek tarih biliniyorsa basılır (sahte "şimdi" değeri güven kaybettirir).

// Ana Sayfa
echo "<url><loc>" . htmlspecialchars($site_url) . "/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>\n";
// Blog Ana Sayfası
echo "<url><loc>" . htmlspecialchars($site_url) . "/blog</loc><changefreq>daily</changefreq><priority>0.8</priority></url>\n";
// Tüm Yazılar
echo "<url><loc>" . htmlspecialchars($site_url) . "/tum-yazilar</loc><changefreq>weekly</changefreq><priority>0.5</priority></url>\n";

// İlanlar
$query_ilanlar = $conn->query("SELECT id, seo, tarih FROM uyeler WHERE durum = 'Yayında' ORDER BY id DESC");
foreach($query_ilanlar as $goster) {
    $loc = $site_url . "/ilan/" . htmlspecialchars($goster["seo"]) . "/" . intval($goster["id"]) . ".html";
    $lastmod = '';
    if (!empty($goster["tarih"]) && ($ts = strtotime($goster["tarih"])) !== false) {
        $lastmod = "<lastmod>" . date("Y-m-d", $ts) . "</lastmod>";
    }
    echo "<url><loc>" . $loc . "</loc>" . $lastmod . "<changefreq>weekly</changefreq><priority>0.7</priority></url>\n";
}

// Kategoriler
$query_kategoriler = $conn->query("SELECT id, seo FROM kategori ORDER BY id ASC");
foreach($query_kategoriler as $goster) {
    echo "<url><loc>" . $site_url . "/kategori/" . htmlspecialchars($goster["seo"]) . "/" . intval($goster["id"]) . ".html</loc><changefreq>weekly</changefreq><priority>0.6</priority></url>\n";
}

// Blog Yazıları
$query_blog = $conn->query("SELECT id, seo, tarih FROM blog WHERE durum = 'Aktif' ORDER BY id DESC");
foreach($query_blog as $goster) {
    $loc = $site_url . "/yazi/" . htmlspecialchars($goster["seo"]) . "/" . intval($goster["id"]) . ".html";
    $lastmod = '';
    if (!empty($goster["tarih"]) && ($ts = strtotime($goster["tarih"])) !== false) {
        $lastmod = "<lastmod>" . date("Y-m-d", $ts) . "</lastmod>";
    }
    echo "<url><loc>" . $loc . "</loc>" . $lastmod . "<changefreq>monthly</changefreq><priority>0.5</priority></url>\n";
}

echo "</urlset>";
?>
