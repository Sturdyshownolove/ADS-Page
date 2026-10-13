# 🌐 PHP Directory & Classifieds CMS Portal

A complete directory and classified advertising portal built with PHP (PDO), MySQL/MariaDB, Google AMP support, and an administrative control panel.

## 🚀 Features
- **Responsive Web Portal:** Categorized listing directory with search, tags, and detail views.
- **Google AMP Support:** Accelerated Mobile Pages endpoint (`/amp`) for ultrafast mobile browsing.
- **Admin Dashboard (`admin/`):** Full control over listings, categories, blog articles, SEO meta tags, and color schemes.
- **SEO & Automation:** Dynamic XML sitemap generator, RSS feeds, robots.txt, and blog cronjob automation.
- **Security:** PDO prepared statements, URL slugification, and input sanitization.

## ⚙️ Setup & Installation
1. Import `database_schema.sql` into your MySQL/MariaDB database:
   ```bash
   mysql -u root -p database_name < database_schema.sql
   ```
2. Configure database credentials in `include/connect.php` (or set environment variables `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).
3. Deploy to an Apache web server (XAMPP, WampServer, or Linux LAMP) with `mod_rewrite` enabled.
4. Access the admin dashboard at `/admin/` (Default credentials: username `admin`, password `admin123`).


## 📄 License
Copyright © 2026 Anıl Aygün. All rights reserved.
See [LICENSE](LICENSE) for terms of use.
