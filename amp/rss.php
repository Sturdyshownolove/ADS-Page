<?php
require_once ("include/connect.php");
$SiteBilgi = $conn->query("SELECT * FROM ayarlar")->fetch(PDO::FETCH_ASSOC);

$sql = $conn->prepare('SELECT * FROM uyeler where seo!=? ORDER BY id DESC');
$sql->execute(array("ilan-ver"));
$rs_ilan = $sql->fetchAll();

$data = '<?xml version="1.0" encoding="UTF-8" ?>';
$data .= '<rss version="2.0">';
$data .= '<channel>';
$data .= '<title>'.$SiteBilgi['siteadi'].'</title>';
$data .= '<link>'.$SiteBilgi['siteurl'].'</link>';
$data .= '<description>'.$SiteBilgi['meta'].'</description>';

foreach ($rs_ilan as $row) {
    $data .= '<item>';
    $data .= '<title>'.$row['isim'].'</title>';
    $data .= '<link>'.$SiteBilgi['siteurl'].'ilan/'.$row['seo'].'/'.$row['id'].'.html</link>';
    $data .= '<description>'.$row['meta'].'</description>';
    $data .= '</item>';
}

$data .= '</channel>';
$data .= '</rss> ';

header('Content-Type: application/xml');
echo $data;
?>