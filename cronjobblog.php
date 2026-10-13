<?php
// Bu dosyanın, sitenin ana dizininde olduğu varsayılıyor.
// Eğer /panel gibi bir alt klasördeyse, 'include' yolunu düzenlemeniz gerekebilir.

// --- GÜVENLİK: Sadece CLI veya doğru token ile çalışır ---
// Cron kurulumu: */5 * * * * php /path/cronjobblog.php
// veya URL ile:  curl "https://site.com/cronjobblog.php?token=nUFl5a11rmmQiUTjV9dyI7AhecSsfTXz3KcaVMyx"
define('CRON_TOKEN', 'nUFl5a11rmmQiUTjV9dyI7AhecSsfTXz3KcaVMyx');
if (php_sapi_name() !== 'cli' && !hash_equals(CRON_TOKEN, $_GET['token'] ?? '')) {
    http_response_code(403);
    exit('Erişim engellendi.');
}

require_once('include/connect.php');

/**
 * Bu script, zamanı gelmiş (paylasim <= şimdiki zaman) ve durumu 'Cron' olan
 * tüm blog yazılarını tek bir SQL sorgusu ile 'Aktif' durumuna getirir ve
 * yayinlanma_tarihi alanını o anki zamanla günceller.
 * Bu, yeni ve optimize edilmiş veritabanı yapısıyla tam uyumludur.
 */

try {
    // Tek bir SQL sorgusu ile hem verimli hem de güvenli güncelleme
    $guncelle = $conn->prepare("
        UPDATE blog 
        SET 
            durum = 'Aktif', 
            yayinlanma_tarihi = NOW()
        WHERE 
            durum = 'Cron' AND paylasim <= NOW()
    ");

    // Sorguyu çalıştır
    $guncelle->execute();

    // Etkilenen (yayına alınan) satır sayısını al
    $etkilenen_satir_sayisi = $guncelle->rowCount();

    // Cron job logları için bilgilendirme çıktısı (opsiyonel)
    echo "[" . date("Y-m-d H:i:s") . "] Zamanı gelen " . $etkilenen_satir_sayisi . " adet blog yazısı başarıyla yayınlandı.\n";

} catch (PDOException $e) {
    // Bir hata olursa logla (opsiyonel, ama önerilir)
    error_log("Cronjob hatası: " . $e->getMessage());
}

?>