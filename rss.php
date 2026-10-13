<?php
require_once("include/connect.php");
$site_url = rtrim($SiteBilgi['siteurl'] ?? '', '/');

// Sadece gerekli sütunları çek
$sql = $conn->prepare('SELECT id, seo, isim, meta FROM uyeler WHERE durum = ? ORDER BY id DESC');
$sql->execute(['Yayında']);
$rs_ilan = $sql->fetchAll();

header('Content-Type: application/xml; charset=utf-8');

$data = '<?xml version="1.0" encoding="UTF-8" ?>';
$data .= '<rss version="2.0">';
$data .= '<channel>';
// GÜVENLİK: Tüm çıktılar htmlspecialchars ile filtrelendi
$data .= '<title>' . htmlspecialchars($SiteBilgi['siteadi'], ENT_XML1, 'UTF-8') . '</title>';
$data .= '<link>' . htmlspecialchars($SiteBilgi['siteurl'], ENT_XML1, 'UTF-8') . '</link>';
$data .= '<description>' . htmlspecialchars($SiteBilgi['meta'], ENT_XML1, 'UTF-8') . '</description>';
$data .= '<language>tr</language>';

foreach ($rs_ilan as $row) {
    $link = htmlspecialchars($site_url . '/ilan/' . $row['seo'] . '/' . $row['id'] . '.html', ENT_XML1, 'UTF-8');
    $data .= '<item>';
    $data .= '<title>' . htmlspecialchars($row['isim'], ENT_XML1, 'UTF-8') . '</title>';
    $data .= '<link>' . $link . '</link>';
    $data .= '<guid isPermaLink="true">' . $link . '</guid>';
    $data .= '<pubDate>' . date('r') . '</pubDate>';
    $data .= '<description><![CDATA[' . $row['meta'] . ']]></description>'; // CDATA daha güvenli
    $data .= '</item>';
}

$data .= '</channel>';
$data .= '</rss>';

echo $data;
?>