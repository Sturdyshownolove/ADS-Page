<?php
/**
 * Temel Yapılandırma ve Veritabanı Bağlantısı
 */

// --- Temel Ayarlar ---
// Hata raporlamayı ayarla (Canlı sunucuda hataları gizle, log dosyasına yaz)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error_log.txt'); // logs/ klasörü .htaccess ile dışarıya kapalı

// Zaman dilimini ayarla
date_default_timezone_set('Europe/Istanbul');

// --- Veritabanı Bilgileri ---
$DB_HOST = getenv('DB_HOST') ?: "localhost";
$DB_NAME = getenv('DB_NAME') ?: "database_name";
$DB_USER = getenv('DB_USER') ?: "database_user";
$DB_PASS = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
$charset = 'utf8mb4';

// --- PDO Bağlantısı ---
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$charset";

try {
    $conn = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
    die("Sistemde bir sorun oluştu. Lütfen daha sonra tekrar deneyin.");
}

// --- Global Site Bilgilerini Çek ---
// Bu bilgiler her sayfada gerektiği için burada bir kez çekilir.
try {
    $SiteBilgi = $conn->query("SELECT * FROM ayarlar WHERE id = 1 LIMIT 1")->fetch();
    $AmpBilgi = $conn->query("SELECT * FROM ampayar WHERE amp_id = 1 LIMIT 1")->fetch();
} catch (PDOException $e) {
    error_log("Site ayarları çekilemedi: " . $e->getMessage());
    // Bilgiler çekilemezse sitenin çökmesini engelle
    $SiteBilgi = [];
    $AmpBilgi = [];
}

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