<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">En Çok Okunanlar</h3></div>
    <div class="panel-body">
        <ul class="media-list">
            <?php
            // Sorgu optimize edildi ve yeni tarih sütunu kullanıldı
            $populerSor = $conn->query("SELECT id, seo, baslik, resim, yayinlanma_tarihi FROM blog WHERE durum='Aktif' ORDER BY hit DESC LIMIT 5");
            foreach($populerSor as $populer):
            ?>
            <li class="media">
                <a class="media-left" href="yazi/<?= htmlspecialchars($populer['seo']); ?>/<?= intval($populer['id']); ?>.html">
                    <img src="blogresim/<?= htmlspecialchars($populer['resim']); ?>" alt="<?= htmlspecialchars($populer['baslik']); ?>" width="60" height="60">
                </a>
                <div class="media-body">
                    <h5 class="media-heading"><a href="yazi/<?= htmlspecialchars($populer['seo']); ?>/<?= intval($populer['id']); ?>.html"><?= htmlspecialchars($populer['baslik']); ?></a></h5>
                    <small><?= turkcetarih('d MMMM yyyy', $populer['yayinlanma_tarihi']); ?></small>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>