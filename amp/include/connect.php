<?php
$DB_HOST = "localhost";
$DB_NAME = "izmirex";
$DB_USER = "root";
$DB_PASS = "izmirex";

try {
    // Veritabanı ile aynı karakter setini kullanıyoruz: utf8mb4
    $conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

$SiteBilgi = $conn->query("SELECT * FROM ayarlar")->fetch(PDO::FETCH_ASSOC);
$AmpBilgi = $conn->query("SELECT * FROM ampayar")->fetch(PDO::FETCH_ASSOC);

if (!function_exists('formatWhatsappNumber')) {
    /**
     * WhatsApp / wa.me linkleri için telefon numarasını uygun formata getirir.
     * Türkiye (05xx, 5xx, +90xx) ve uluslararası (+40xx, 40xx vb.) numaraları destekler.
     */
    function formatWhatsappNumber($phone) {
        $clean = preg_replace('/\D/', '', (string)$phone);
        if (empty($clean)) {
            return '';
        }
        if (strpos($clean, '00') === 0) {
            $clean = substr($clean, 2);
        }
        if (strlen($clean) === 11 && strpos($clean, '0') === 0) {
            $clean = '9' . $clean;
        } elseif (strlen($clean) === 10 && strpos($clean, '5') === 0) {
            $clean = '90' . $clean;
        }
        return $clean;
    }
}
?>