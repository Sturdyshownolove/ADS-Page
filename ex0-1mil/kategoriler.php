<?php
require_once('header.php');

$msg = '';
$msg_type = '';

// Kategori Ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ekle'])) {
    $baslik = trim($_POST['baslik'] ?? '');
    $odak = trim($_POST['odak'] ?? 'İzmir Buca');
    $meta = trim($_POST['meta'] ?? '');
    $anahtar = trim($_POST['anahtar'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');

    if (!empty($baslik)) {
        $seo = seo($baslik);
        $stmt = $conn->prepare("INSERT INTO kategori (baslik, odak, meta, anahtar, aciklama, seo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$baslik, $odak, $meta, $anahtar, $aciklama, $seo]);
        $msg = 'Yeni kategori eklendi!';
        $msg_type = 'success';
    }
}

// Kategori Silme
if (isset($_GET['sil'])) {
    $sil_id = intval($_GET['sil']);
    $conn->prepare("DELETE FROM kategori WHERE id = ?")->execute([$sil_id]);
    $msg = 'Kategori silindi!';
    $msg_type = 'success';
}

$kategoriler = $conn->query("SELECT * FROM kategori ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-white tracking-tight">Kategori Yönetimi</h2>
        <p class="text-xs text-slate-400 mt-1">İzmir ve Buca ilçelerine özel SEO destekli kategorileri yönetin.</p>
    </div>
</div>

<?php if ($msg): ?>
<div class="p-4 mb-6 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
    <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
    <span><?= htmlspecialchars($msg); ?></span>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Yeni Kategori Ekle Formu -->
    <div class="lg:col-span-1">
        <form method="POST" action="" class="glass-panel p-6 rounded-2xl space-y-4">
            <h3 class="text-base font-bold text-white mb-2 pb-2 border-b border-slate-800">Hızlı Kategori Ekle</h3>
            
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Kategori Adı (*)</label>
                <input type="text" name="baslik" required placeholder="Örn: BUCA MODEL" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Odak Şehir / Semt</label>
                <input type="text" name="odak" value="İzmir Buca" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Meta Açıklaması</label>
                <textarea name="meta" rows="2" placeholder="SEO meta açıklaması..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Anahtar Kelimeler</label>
                <input type="text" name="anahtar" placeholder="buca model, izmir model..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
            </div>

            <button type="submit" name="ekle" class="w-full py-3 bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-500/20 transition-all">
                <i class="fa-solid fa-plus mr-1"></i> Kategoriyi Kaydet
            </button>
        </form>
    </div>

    <!-- Mevcut Kategoriler Tablosu -->
    <div class="lg:col-span-2">
        <div class="glass-panel p-6 rounded-2xl">
            <h3 class="text-base font-bold text-white mb-4">Aktif Kategoriler (<?= count($kategoriler); ?>)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-3"># ID</th>
                            <th class="py-3 px-3">Kategori Adı</th>
                            <th class="py-3 px-3">Odak Noktası</th>
                            <th class="py-3 px-3">SEO Slug</th>
                            <th class="py-3 px-3 text-right">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-xs">
                        <?php foreach($kategoriler as $kat): ?>
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-3 font-bold text-slate-500">#<?= $kat['id']; ?></td>
                            <td class="py-3 px-3 font-bold text-white"><?= htmlspecialchars($kat['baslik']); ?></td>
                            <td class="py-3 px-3 text-slate-400"><?= htmlspecialchars($kat['odak'] ?: 'İzmir Buca'); ?></td>
                            <td class="py-3 px-3 text-sky-400 font-mono text-[11px]"><?= htmlspecialchars($kat['seo']); ?></td>
                            <td class="py-3 px-3 text-right">
                                <a href="kategoriler.php?sil=<?= $kat['id']; ?>" onclick="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all inline-flex items-center" title="Sil">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php require_once('footer.php'); ?>
