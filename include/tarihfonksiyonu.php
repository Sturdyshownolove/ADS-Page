<?php

/**
 * Verilen bir tarih/zaman bilgisini, belirtilen formata göre Türkçe olarak döndürür.
 * Dahili IntlDateFormatter sınıfını veya fallback date formatlama kullanır.
 *
 * @param string $format İstenen tarih formatı (ICU formatında, örn: 'd MMMM yyyy').
 * @param string|int|DateTime $zaman_damgasi Formatlanacak tarih.
 * @return string Türkçe formatlanmış tarih.
 */
function turkcetarih($format, $zaman_damgasi = 'now')
{
    if (empty($zaman_damgasi) || $zaman_damgasi === '0000-00-00' || $zaman_damgasi === '-' || $zaman_damgasi === '0000-00-00 00:00:00') {
        $zaman_damgasi = 'now';
    }

    try {
        if (is_numeric($zaman_damgasi)) {
            $tarih_objesi = new DateTime("@$zaman_damgasi");
            $tarih_objesi->setTimezone(new DateTimeZone('Europe/Istanbul'));
        } else {
            $tarih_objesi = new DateTime($zaman_damgasi);
        }
    } catch (Throwable $e) {
        $tarih_objesi = new DateTime();
    }

    try {
        if (class_exists('IntlDateFormatter')) {
            $fmt = new IntlDateFormatter(
                'tr_TR',
                IntlDateFormatter::FULL,
                IntlDateFormatter::FULL,
                'Europe/Istanbul',
                IntlDateFormatter::GREGORIAN,
                $format
            );
            $result = $fmt->format($tarih_objesi);
            if ($result !== false) {
                return $result;
            }
        }
    } catch (Throwable $e) {
        // Fallback
    }

    return $tarih_objesi->format('d.m.Y');
}
?>