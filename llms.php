<?php
// llms.txt — AI arama motorları ve LLM crawler'lar için site özeti
// (.htaccess: llms.txt -> llms.php şeklinde rewrite eklenebilir)
require_once("include/connect.php");
$site_url = rtrim($SiteBilgi['siteurl'] ?? '', '/');
$site_name = $SiteBilgi['siteadi'] ?? '';
$site_desc = $SiteBilgi['meta'] ?? '';

header("Content-Type: text/plain; charset=UTF-8");
?>
# <?= $site_name; ?>

> <?= $site_desc; ?>

## Site Structure

- Homepage: <?= $site_url; ?>/
- Blog: <?= $site_url; ?>/blog
- All Posts: <?= $site_url; ?>/tum-yazilar
- Sitemap: <?= $site_url; ?>/sitemap.xml
- RSS Feed: <?= $site_url; ?>/rss.xml

## Content Types

- Blog articles with categories and tags
- Listings with detail pages, categories, and gallery images
- Category archive pages with pagination

## Technical Details

- Language: Turkish (tr)
- CMS: Custom PHP
- Mobile: AMP support for mobile visitors
- Schema: WebSite, BlogPosting, BreadcrumbList, Person, CollectionPage (JSON-LD)
