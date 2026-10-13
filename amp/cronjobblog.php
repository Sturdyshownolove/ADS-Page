<?php
// Bu dosyanın, sitenin ana dizininde olduğu varsayılıyor.
// NOT: Kök dizindeki cronjobblog.php ile aynı işi yapar — cron'u tek dosyaya bağlayıp bunu silmek daha temiz.

// --- GÜVENLİK: Sadece CLI veya doğru token ile çalışır ---
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
    // Bir hata olursa logla
    error_log("Cronjob hatası: " . $e->getMessage());
}

?>