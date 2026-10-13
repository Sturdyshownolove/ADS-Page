<?php
/**
 * SEO Dostu URL Slug Üretici ve WebP Görsel Dönüştürücü
 */
function seo($str, $options = array()) {
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_detect_encoding((string)$str));
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => true,
    );
    $options = array_merge($defaults, $options);
    $char_map = array(
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
    );
    $str = strtr($str, $char_map);
    if ($options['transliterate']) {
        $str = str_replace(array_keys($options['replacements']), $options['replacements'], $str);
    }
    $str = preg_replace('~[^\pL\d]+~u', $options['delimiter'], $str);
    $str = preg_replace('~[^-\w]+~', '', $str);
    $str = trim($str, $options['delimiter']);
    if ($options['lowercase']) {
        $str = mb_strtolower($str, 'UTF-8');
    }
    return empty($str) ? 'n-a' : $str;
}

function webpyeCevir($kaynakPath, $hedefPath, $kalite = 85) {
    if (!file_exists($kaynakPath)) return false;
    $info = getimagesize($kaynakPath);
    if (!$info) return false;
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($kaynakPath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($kaynakPath);
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            break;
        case 'image/webp':
            return copy($kaynakPath, $hedefPath);
        default:
            return false;
    }

    if ($image !== false) {
        $result = imagewebp($image, $hedefPath, $kalite);
        imagedestroy($image);
        return $result;
    }
    return false;
}
