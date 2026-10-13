-- Database Schema Template
-- Clean structure for Classifieds / Directory CMS Portal

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;



-- Default Configuration Rows (Clean Placeholders)
LOCK TABLES yarlar WRITE;
INSERT INTO yarlar (id, siteadi, logoyazi, meta, nahtar, 	elefon, 	elefonyazi, siteurl, mpurl, nasayfa, yanpanel, copyright, yanpanelbaslik, ustalanyazisi, head, ooter, mapsapi, nalytics, 
enk_arka, 
enk_headerarka, 
enk_headeryazi, 
enk_paketbaslik, 
enk_paketyazi) VALUES
(1, 'Portal Demo', 'Portal', 'Demo Portal Meta Description', 'portal, directory, demo', '0500 000 00 00', 'Contact Us', 'http://localhost/', 'http://localhost/amp', '<p>Welcome to the portal demo.</p>', '', 'Copyright © 2026 Portal. All Rights Reserved.', '', 'Demo Directory Portal', '', '', 'YOUR_GOOGLE_MAPS_API_KEY', 'UA-XXXXXXXXX-X', '#ffffff', '#222222', '#ffffff', '#333333', '#ffffff');
UNLOCK TABLES;

LOCK TABLES mpayar WRITE;
INSERT INTO mpayar (mp_id, mp_domain, mp_sub, mp_baslik, mp_iletisim, mp_headerarkarenk, mp_headeryazi, mp_baslikarkarenk, mp_baslikyazirenk, mp_ilanarkarenk, mp_ilanyazirenk, mp_telegram) VALUES
(1, 'http://localhost/amp', '', 'AMP Portal Demo', 'https://api.whatsapp.com/send?phone=905000000000', '#222222', '#ffffff', '#222222', '#ffffff', '#222222', '#ffffff', 'https://t.me/yourchannel');
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS = 1;
