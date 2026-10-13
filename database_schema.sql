-- Database Schema for Classifieds & Service Directory Portal (PortalEX)
-- Complete structure with table definitions and clean seed data.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- 1. Table structure for `ayarlar` (Site Configuration)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `ayarlar`;
CREATE TABLE `ayarlar` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `siteadi` VARCHAR(255) DEFAULT 'Hizmet ve İlan Rehberi',
  `logoyazi` VARCHAR(255) DEFAULT 'PortalEX',
  `meta` TEXT,
  `anahtar` TEXT,
  `telefon` VARCHAR(50) DEFAULT '0555 000 00 00',
  `telefonyazi` VARCHAR(255) DEFAULT 'İletişim & Destek',
  `siteurl` VARCHAR(255) DEFAULT 'http://localhost/',
  `ampurl` VARCHAR(255) DEFAULT 'http://localhost/amp',
  `anasayfa` MEDIUMTEXT,
  `yanpanel` MEDIUMTEXT,
  `copyright` VARCHAR(255) DEFAULT 'Copyright © 2026 PortalEX. All Rights Reserved.',
  `yanpanelbaslik` VARCHAR(255) DEFAULT 'Hızlı Menü',
  `ustalanyazisi` VARCHAR(255) DEFAULT 'Profesyonel Hizmet ve İlan Rehberi',
  `head` TEXT,
  `footer` TEXT,
  `mapsapi` VARCHAR(255) DEFAULT '',
  `analytics` VARCHAR(255) DEFAULT '',
  `renk_arka` VARCHAR(20) DEFAULT '#0f172a',
  `renk_headerarka` VARCHAR(20) DEFAULT '#1e293b',
  `renk_headeryazi` VARCHAR(20) DEFAULT '#ffffff',
  `renk_paketbaslik` VARCHAR(20) DEFAULT '#38bdf8',
  `renk_paketyazi` VARCHAR(20) DEFAULT '#ffffff',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ayarlar` (`id`, `siteadi`, `logoyazi`, `meta`, `anahtar`, `telefon`, `telefonyazi`, `siteurl`, `ampurl`, `anasayfa`, `yanpanel`, `copyright`, `yanpanelbaslik`, `ustalanyazisi`, `head`, `footer`, `mapsapi`, `analytics`, `renk_arka`, `renk_headerarka`, `renk_headeryazi`, `renk_paketbaslik`, `renk_paketyazi`) VALUES
(1, 'Hizmet ve İlan Rehberi', 'PortalEX', 'İzmir ve Ege bölgesinde profesyonel hizmet sağlayıcıları, danışmanlık ve ilan rehberi.', 'ilan, rehber, hizmet, danışmanlık, izmir, yazılım, tasarım', '0555 000 00 00', 'Müşteri Hizmetleri', 'http://localhost/', 'http://localhost/amp', '<p>PortalEX hizmet ve profesyonel ilan platformuna hoş geldiniz.</p>', '', 'Copyright © 2026 PortalEX. Tüm Hakları Saklıdır.', 'Kategoriler', 'Güvenilir Hizmet Rehberi', '', '', '', '', '#0f172a', '#1e293b', '#ffffff', '#38bdf8', '#ffffff');

-- -------------------------------------------------------------
-- 2. Table structure for `ampayar` (AMP Mobile Configuration)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `ampayar`;
CREATE TABLE `ampayar` (
  `amp_id` INT NOT NULL AUTO_INCREMENT,
  `amp_domain` VARCHAR(255) DEFAULT 'http://localhost/amp',
  `amp_sub` VARCHAR(255) DEFAULT '',
  `amp_baslik` VARCHAR(255) DEFAULT 'PortalEX Mobil',
  `amp_iletisim` VARCHAR(255) DEFAULT 'https://api.whatsapp.com/send?phone=905550000000',
  `amp_headerarkarenk` VARCHAR(20) DEFAULT '#1e293b',
  `amp_headeryazi` VARCHAR(20) DEFAULT '#ffffff',
  `amp_baslikarkarenk` VARCHAR(20) DEFAULT '#0f172a',
  `amp_baslikyazirenk` VARCHAR(20) DEFAULT '#ffffff',
  `amp_ilanarkarenk` VARCHAR(20) DEFAULT '#1e293b',
  `amp_ilanyazirenk` VARCHAR(20) DEFAULT '#ffffff',
  `amp_telegram` VARCHAR(255) DEFAULT 'https://t.me/exampleportal',
  PRIMARY KEY (`amp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ampayar` (`amp_id`, `amp_domain`, `amp_sub`, `amp_baslik`, `amp_iletisim`, `amp_headerarkarenk`, `amp_headeryazi`, `amp_baslikarkarenk`, `amp_baslikyazirenk`, `amp_ilanarkarenk`, `amp_ilanyazirenk`, `amp_telegram`) VALUES
(1, 'http://localhost/amp', '', 'PortalEX Hızlı Mobil', 'https://api.whatsapp.com/send?phone=905550000000', '#1e293b', '#ffffff', '#0f172a', '#ffffff', '#1e293b', '#ffffff', 'https://t.me/exampleportal');

-- -------------------------------------------------------------
-- 3. Table structure for `asistan` (Admin Authentication)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `asistan`;
CREATE TABLE `asistan` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `site_user` VARCHAR(100) NOT NULL,
  `site_pass` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_site_user` (`site_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default password is 'admin123' hashed with password_hash(PASSWORD_BCRYPT)
INSERT INTO `asistan` (`id`, `site_user`, `site_pass`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- -------------------------------------------------------------
-- 4. Table structure for `kategori` (Listing Categories)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `baslik` VARCHAR(255) NOT NULL,
  `seo` VARCHAR(255) NOT NULL,
  `odak` VARCHAR(255) DEFAULT 'İzmir',
  `meta` TEXT,
  `anahtar` TEXT,
  `aciklama` TEXT,
  PRIMARY KEY (`id`),
  KEY `idx_kat_seo` (`seo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kategori` (`id`, `baslik`, `seo`, `odak`, `meta`, `anahtar`, `aciklama`) VALUES
(1, 'Yazılım & Web Geliştirme', 'yazilim-web-gelistirme', 'İzmir Yazılım', 'Web yazılım, mobil uygulama ve otomasyon hizmetleri.', 'yazılım, web geliştirme, mobil uygulama', 'Yazılım çözümleri ve teknoloji danışmanlığı sunan uzmanlar.'),
(2, 'Grafik Tasarım & Medya', 'grafik-tasarim-medya', 'İzmir Tasarım', 'Kurumsal kimlik, UI/UX tasarımı ve dijital medya hizmetleri.', 'tasarım, grafik, ui, ux, logo', 'Grafik tasarımcılar ve dijital medya uzmanları.'),
(3, 'Danışmanlık & Eğitim', 'danismanlik-egitim', 'İzmir Danışmanlık', 'Kurumsal iş geliştirme ve profesyonel eğitim rehberi.', 'danışmanlık, eğitim, mentorluk', 'İşletme danışmanlığı ve bireysel gelişim uzmanları.'),
(4, 'Fotoğrafçılık & Video Prodüksiyon', 'fotografcilik-video', 'İzmir Prodüksiyon', 'Etkinlik, ürün çekimi ve video prodüksiyon hizmetleri.', 'fotoğraf, video, prodüksiyon', 'Fotoğrafçılar ve prodüksiyon ekipleri.');

-- -------------------------------------------------------------
-- 5. Table structure for `uyeler` (Directory Listings / Profiles)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `uyeler`;
CREATE TABLE `uyeler` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `isim` VARCHAR(255) NOT NULL,
  `telefon` VARCHAR(50) DEFAULT '',
  `sehir` VARCHAR(100) DEFAULT 'İzmir',
  `semt` VARCHAR(100) DEFAULT 'Merkez',
  `kategori` INT DEFAULT 1,
  `resim` VARCHAR(255) DEFAULT 'placeholder.jpg',
  `aciklama` TEXT,
  `seviye` VARCHAR(50) DEFAULT 'Standart',
  `sira` INT DEFAULT 0,
  `durum` VARCHAR(50) DEFAULT 'Yayında',
  `seo` VARCHAR(255) NOT NULL,
  `tarih` VARCHAR(50) DEFAULT '',
  `btarih` VARCHAR(50) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uyeler_durum` (`durum`),
  KEY `idx_uyeler_seo` (`seo`),
  KEY `idx_uyeler_kat` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `uyeler` (`id`, `isim`, `telefon`, `sehir`, `semt`, `kategori`, `resim`, `aciklama`, `seviye`, `sira`, `durum`, `seo`, `tarih`, `btarih`) VALUES
(1, 'Ege Bilişim Danışmanlık', '0555 111 22 33', 'İzmir', 'Konak', 1, 'placeholder.jpg', 'Kurumsal web projeleri, e-ticaret altyapıları ve API entegrasyon hizmetleri sunmaktayız.', 'VIP', 1, 'Yayında', 'ege-bilisim-danismanlik', '13.10.2026', '13.10.2027'),
(2, 'Piksel Stüdyo Tasarım', '0555 222 33 44', 'İzmir', 'Bornova', 2, 'placeholder.jpg', 'Modern UI/UX arayüz tasarımları, marka kimliği ve reklam materyalleri üretimi.', 'Standart', 2, 'Yayında', 'piksel-studyo-tasarim', '13.10.2026', '13.10.2027'),
(3, 'Akademi Kariyer Mentorluğu', '0555 333 44 55', 'İzmir', 'Alsancak', 3, 'placeholder.jpg', 'Bilişim ve yazılım kariyerine geçiş yapan profesyonellere birebir danışmanlık.', 'Standart', 3, 'Yayında', 'akademi-kariyer-mentorlugu', '13.10.2026', '13.10.2027');

-- -------------------------------------------------------------
-- 6. Table structure for `blog` (Articles & Content)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `kategori` INT DEFAULT 0,
  `baslik` VARCHAR(255) NOT NULL,
  `aciklama` LONGTEXT,
  `resim` VARCHAR(255) DEFAULT 'resimyok.png',
  `meta` TEXT,
  `anahtar` TEXT,
  `durum` VARCHAR(50) DEFAULT 'Aktif',
  `odak_kelime` VARCHAR(255) DEFAULT '',
  `seo` VARCHAR(255) NOT NULL,
  `paylasim` VARCHAR(50) DEFAULT 'Normal',
  `tarih` VARCHAR(50) DEFAULT '',
  `yayinlanma_tarihi` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `yil` INT DEFAULT 2026,
  `hit` INT DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_blog_durum` (`durum`),
  KEY `idx_blog_seo` (`seo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog` (`id`, `kategori`, `baslik`, `aciklama`, `resim`, `meta`, `anahtar`, `durum`, `odak_kelime`, `seo`, `paylasim`, `tarih`, `yayinlanma_tarihi`, `yil`, `hit`) VALUES
(1, 1, '2026 Yılında Web Geliştirme Trendleri', '<p>Modern web projelerinde performans, SEO optimizasyonu ve hafif mimariler büyük önem taşıyor.</p>', 'resimyok.png', 'Modern web geliştirme teknolojileri ve performans optimizasyon ipuçları.', 'web, yazılım, teknoloji, seo', 'Aktif', 'web geliştirme', '2026-yilinda-web-gelistirme-trendleri', 'Normal', '13.10.2026', NOW(), 2026, 12);

-- -------------------------------------------------------------
-- 7. Table structure for `linkler` (Footer / Partner Links)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `linkler`;
CREATE TABLE `linkler` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `baslik` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `linkler` (`id`, `baslik`, `url`) VALUES
(1, 'Hizmet Rehberi Ana Sayfa', 'http://localhost/'),
(2, 'İzmir Bilişim Portalı', 'http://localhost/kategoridetay.php?id=1');

-- -------------------------------------------------------------
-- 8. Table structure for `resimler` (Listing Photo Gallery)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `resimler`;
CREATE TABLE `resimler` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uye_id` INT NOT NULL,
  `resim` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_resim_uye` (`uye_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 9. Table structure for `etiketler` (Blog Tags)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `etiketler`;
CREATE TABLE `etiketler` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `konu` INT NOT NULL,
  `baslik` VARCHAR(255) NOT NULL,
  `seo` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_etiket_seo` (`seo`),
  KEY `idx_etiket_konu` (`konu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `etiketler` (`id`, `konu`, `baslik`, `seo`) VALUES
(1, 1, 'Yazılım', 'yazilim'),
(2, 1, 'Teknoloji', 'teknoloji');

-- -------------------------------------------------------------
-- 10. Table structure for `uyeetiketler` (Listing Tags)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `uyeetiketler`;
CREATE TABLE `uyeetiketler` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `konu` INT NOT NULL,
  `baslik` VARCHAR(255) NOT NULL,
  `seo` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uyeetiket_seo` (`seo`),
  KEY `idx_uyeetiket_konu` (`konu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `uyeetiketler` (`id`, `konu`, `baslik`, `seo`) VALUES
(1, 1, 'Web Danışmanlık', 'web-danismanlik'),
(2, 2, 'Tasarım Ofisi', 'tasarim-ofisi');

SET FOREIGN_KEY_CHECKS = 1;
