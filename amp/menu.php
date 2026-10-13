<?php
/**
 * AMP Menu Template
 * Fully compliant AMP menu with proper validation
 */
?>

<!-- Sidebar -->
<amp-sidebar id="sidebar" layout="nodisplay" side="left">
    <div class="sidebar-content">
        <button class="close-sidebar" on="tap:sidebar.close" aria-label="Close sidebar">×</button>
        <h3>Menü</h3>
        <ul>
            <li><a href="<?php echo isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/"; ?>">Ana Sayfa</a></li>
            <li><a href="<?php echo isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/"; ?>blog.php">Blog</a></li>
            <li><a href="<?php echo isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/"; ?>kategori.php">Kategoriler</a></li>
            <li><a href="<?php echo isset($SiteBilgi["siteurl"]) ? $SiteBilgi["siteurl"] : "http://localhost/"; ?>etiket.php">Etiketler</a></li>
            <?php if (isset($SiteBilgi['telefon']) && !empty($SiteBilgi['telefon'])): ?>
            <li><a href="tel:<?php echo htmlspecialchars($SiteBilgi['telefon'], ENT_QUOTES, 'UTF-8'); ?>">Ara</a></li>
            <?php endif; ?>
        </ul>
    </div>
</amp-sidebar>

<!-- Note: The following styles should be added to the host page's <style amp-custom> block -->