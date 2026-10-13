<?php
function XzamanOnce($zaman) {
    try {
        $tarih = new DateTime($zaman);
        $simdi = new DateTime();
        $fark = $simdi->diff($tarih);

        if ($fark->y > 0) return $fark->y . ' yıl önce';
        if ($fark->m > 0) return $fark->m . ' ay önce';
        if ($fark->d > 0) {
            if ($fark->d >= 7) return floor($fark->d / 7) . ' hafta önce';
            return $fark->d . ' gün önce';
        }
        if ($fark->h > 0) return $fark->h . ' saat önce';
        if ($fark->i > 0) return $fark->i . ' dakika önce';
        if ($fark->s > 0) return $fark->s . ' saniye önce';
        return 'az önce';
    } catch (Exception $e) {
        return 'geçersiz tarih';
    }
}
?>