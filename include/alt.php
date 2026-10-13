<footer id="footer" style="margin-top:40px;">
    <div class="container text-center text-muted" style="padding: 20px 0; border-top: 1px solid rgba(255,255,255,0.08);">
        <?php
        $footer_linkler = $conn->query("SELECT * FROM linkler ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        if (count($footer_linkler) > 0):
        ?>
        <div style="margin-bottom:15px; font-size:12px;" class="footer-partner-links">
            <span style="color:#94a3b8; font-weight:bold; margin-right:8px;">Öne Çıkan Bağlantılar:</span>
            <?php foreach($footer_linkler as $index => $plink): ?>
                <a href="<?= htmlspecialchars($plink['url']); ?>" style="color:#38bdf8; text-decoration:none; margin:0 6px; font-weight:500;" target="_blank"><?= htmlspecialchars($plink['baslik']); ?></a>
                <?= ($index < count($footer_linkler) - 1) ? '<span style="color:#475569;">•</span>' : ''; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p style="margin:0; font-size:12px;"><?= htmlspecialchars($SiteBilgi['copyright'] ?? ''); ?></p>
    </div>
</footer>

<?= $SiteBilgi['footer'] ?? ''; // Panelden eklenen izleme kodları ?>