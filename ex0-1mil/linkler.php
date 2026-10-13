<?php
require_once('header.php');

$msg = '';
$msg_type = '';

// Yeni Link Ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ekle'])) {
    $baslik = trim($_POST['baslik'] ?? '');
    $url = trim($_POST['url'] ?? '');

    if (empty($baslik) || empty($url)) {
        $msg = 'Başlık ve URL alanları boş bırakılamaz!';
        $msg_type = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO linkler (baslik, url) VALUES (?, ?)");
        $stmt->execute([$baslik, $url]);
        $msg = 'Yeni partner linki eklendi!';
        $msg_type = 'success';
    }
}

// Link Silme
if (isset($_GET['sil'])) {
    $sil_id = intval($_GET['sil']);
    $conn->prepare("DELETE FROM linkler WHERE id = ?")->execute([$sil_id]);
    $msg = 'Link silindi!';
    $msg_type = 'success';
}

$linkler = $conn->query("SELECT * FROM linkler ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-white tracking-tight">Partner & Bağlantı Yönetimi</h2>
        <p class="text-xs text-slate-400 mt-1">Site tabanındaki (footer) veya partner sitelerdeki dış bağlantıları yönetin.</p>
    </div>
</div>

<?php if ($msg): ?>
<div class="p-4 mb-6 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
    <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
    <span><?= htmlspecialchars($msg); ?></span>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Yeni Link Ekle Formu -->
    <div class="lg:col-span-1">
        <form method="POST" action="" class="glass-panel p-6 rounded-2xl space-y-4">
            <h3 class="text-base font-bold text-white mb-2 pb-2 border-b border-slate-800">Yeni Bağlantı Ekle</h3>
            
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Bağlantı Başlığı (*)</label>
                <input type="text" name="baslik" required placeholder="Örn: İzmir Model Rehberi" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Hedef URL (*)</label>
                <input type="url" name="url" required placeholder="https://..." class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono">
            </div>

            <button type="submit" name="ekle" class="w-full py-3 bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-500/20 transition-all">
                <i class="fa-solid fa-plus mr-1"></i> Bağlantıyı Kaydet
            </button>
        </form>
    </div>

    <!-- Mevcut Bağlantılar Tablosu -->
    <div class="lg:col-span-2">
        <div class="glass-panel p-6 rounded-2xl">
            <h3 class="text-base font-bold text-white mb-4">Aktif Bağlantılar (<?= count($linkler); ?>)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-3"># ID</th>
                            <th class="py-3 px-3">Başlık</th>
                            <th class="py-3 px-3">Hedef URL</th>
                            <th class="py-3 px-3 text-right">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-xs">
                        <?php foreach($linkler as $l): ?>
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-3 font-bold text-slate-500">#<?= $l['id']; ?></td>
                            <td class="py-3 px-3 font-bold text-white"><?= htmlspecialchars($l['baslik']); ?></td>
                            <td class="py-3 px-3 text-sky-400 font-mono text-[11px] truncate max-w-[200px]"><?= htmlspecialchars($l['url']); ?></td>
                            <td class="py-3 px-3 text-right space-x-1">
                                <a href="<?= htmlspecialchars($l['url']); ?>" target="_blank" class="p-2 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 transition-all inline-flex items-center" title="Git">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </a>
                                <a href="linkler.php?sil=<?= $l['id']; ?>" onclick="return confirm('Bu bağlantıyı silmek istediğinizden emin misiniz?')" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all inline-flex items-center" title="Sil">
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
