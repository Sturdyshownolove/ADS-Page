<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-menu" aria-expanded="false">
                <span class="sr-only">Menü</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/" class="navbar-brand"><?= htmlspecialchars($SiteBilgi['logoyazi'] ?? ''); ?></a>
        </div>
        <div id="navbar-menu" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/">ANASAYFA</a></li>
                <li class="dropdown">
                    <a href="kategori.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">KATEGORİLER <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        $katSor = $conn->query("SELECT id, seo, baslik FROM kategori ORDER BY baslik ASC");
                        foreach($katSor as $kategori): ?>
                        <li><a href="kategori/<?= htmlspecialchars($kategori['seo']); ?>/<?= intval($kategori['id']); ?>.html"><?= htmlspecialchars($kategori['baslik']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li><a href="blog">BLOG</a></li>
            </ul>
        </div>
    </div>
</nav>