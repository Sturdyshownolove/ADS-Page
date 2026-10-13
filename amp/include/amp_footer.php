<?php
/**
 * AMP Footer Template
 * Fully compliant AMP footer with proper validation
 */
?>
        </main>
        
        <!-- Footer -->
        <footer class="amp-footer">
            <div class="amp-copyright">
                <?php echo isset($SiteBilgi["copyright"]) ? htmlspecialchars($SiteBilgi["copyright"], ENT_QUOTES, 'UTF-8') : '© 2025 İZMİR EX Elit Model. Tüm hakları saklıdır.'; ?>
            </div>
        </footer>
        
        <!-- Analytics -->
        <?php if (isset($SiteBilgi["analytics"]) && !empty($SiteBilgi["analytics"])): ?>
        <amp-analytics type="googleanalytics">
            <script type="application/json">
            {
                "vars": {
                    "account": "<?php echo htmlspecialchars($SiteBilgi["analytics"], ENT_QUOTES, 'UTF-8'); ?>"
                },
                "triggers": {
                    "trackPageview": {
                        "on": "visible",
                        "request": "pageview"
                    }
                }
            }
            </script>
        </amp-analytics>
        <?php endif; ?>
    </div>
</body>
</html>
