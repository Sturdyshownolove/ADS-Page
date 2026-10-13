<?php
// Dinamik robots.txt — sitemap adresi ayarlardaki siteurl'den gelir,
// local/canlı ortam ayrımı kendiliğinden çözülür. (.htaccess: robots.txt -> robots.php)
require_once("include/connect.php");
$site_url = rtrim($SiteBilgi['siteurl'] ?? '', '/');

header("Content-Type: text/plain; charset=UTF-8");
?>
User-agent: *
Allow: /
Disallow: /ex0-1mil/
Disallow: /include/
Disallow: /vendor/
Disallow: /logs/
Disallow: /css/site.php

Sitemap: <?= $site_url; ?>/sitemap.xml
Sitemap: <?= $site_url; ?>/rss.xml

# AI Crawler Yönetimi
User-agent: GPTBot
Allow: /
Disallow: /ex0-1mil/

User-agent: ClaudeBot
Allow: /
Disallow: /ex0-1mil/

User-agent: PerplexityBot
Allow: /
Disallow: /ex0-1mil/

User-agent: Google-Extended
Allow: /

User-agent: Bytespider
Disallow: /
