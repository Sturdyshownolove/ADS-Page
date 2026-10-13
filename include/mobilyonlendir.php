<?php
/**
 * Cihaz bazlı AMP yönlendirmesi.
 * header.php tarafından, $amp_page_url tanımlıysa otomatik dahil edilir.
 *
 * Kurallar:
 * - Mobil User-Agent -> ilgili AMP sayfasına 302 (geçici) yönlendirme.
 * - ?desktop=1 parametresi varsa yönlendirme YAPILMAZ (AMP'deki "Masaüstü Sürümü"
 *   linki bu parametreyi kullanır; yoksa mobil kullanıcı masaüstünü hiç açamaz).
 * - Googlebot dahil hiçbir bota istisna uygulanmaz (cloaking/sneaky redirect yasak).
 *   Mobil Googlebot da yönlendirilir; index, AMP sayfadaki canonical ve buradaki
 *   rel=alternate/amphtml eşleşmesi sayesinde masaüstü URL'de kalır.
 * - Vary: User-Agent her durumda gönderilir (önbellekler cihaza göre ayrım yapsın).
 */

header('Vary: User-Agent');

$current_host = $_SERVER['HTTP_HOST'] ?? '';

if (!isset($_GET['desktop']) && !empty($amp_page_url)) {
    $amp_host = parse_url($amp_page_url, PHP_URL_HOST);
    if ($amp_host && strcasecmp($current_host, $amp_host) !== 0) {
        header('Location: ' . $amp_page_url, true, 302);
        exit;
    }
}
