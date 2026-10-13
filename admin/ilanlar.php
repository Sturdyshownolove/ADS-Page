<?php
require_once('header.php');

$seviye_filter = $_GET['seviye'] ?? '';
$search_query = trim($_GET['q'] ?? '');

$where = [];
$params = [];

if (!empty($seviye_filter)) {
    $where[] = "u.seviye = :seviye";
    $params[':seviye'] = $seviye_filter;
}

if (!empty($search_query)) {
    $where[] = "(u.isim LIKE :q OR u.sehir LIKE :q OR u.semt LIKE :q)";
    $params[':q'] = "%$search_query%";
}

$where_sql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

// Sayfalama
$gosterilecek = 30;
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
$baslangic = ($gosterilecek * $sayfa) - $gosterilecek;

$countStmt = $conn->prepare("SELECT COUNT(u.id) FROM uyeler u $where_sql");
$countStmt->execute($params);
$verisayisi = $countStmt->fetchColumn();
$sayfasayisi = ceil($verisayisi / $gosterilecek);

$query = $conn->prepare("
    SELECT u.id, u.seo, u.isim, u.resim, u.seviye, u.durum, u.sira, u.btarih, k.baslik AS kategori_baslik
    FROM uyeler u
    LEFT JOIN kategori k ON u.kategori = k.id
    $where_sql
    ORDER BY u.durum ASC, u.sira ASC, u.id DESC
    LIMIT $baslangic, $gosterilecek
");
$query->execute($params);
$ilanlar = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-white tracking-tight">İlan Yönetimi</h2>
        <p class="text-xs text-slate-400 mt-1">Tüm vitrin seviyelerindeki ilanları filtreleyin, sıralayın ve yönetin.</p>
    </div>
    <a href="ilan-ekle.php" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition-all shrink-0">
        <i class="fa-solid fa-plus text-xs"></i>
        <span>Yeni İlan Ekle</span>
    </a>
</div>

<!-- FILTER TABS & SEARCH BAR -->
<div class="glass-panel p-4 rounded-2xl mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <!-- Level Filter Buttons -->
    <div class="flex items-center gap-1.5 overflow-x-auto pb-2 md:pb-0">
        <a href="ilanlar.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?= empty($seviye_filter) ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/25' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'; ?>">
            Tümü (<?= $stats['total_ilan']; ?>)
        </a>
        <a href="ilanlar.php?seviye=mega" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?= $seviye_filter == 'mega' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/25' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'; ?>">
            👑 Mega VIP (<?= $stats['mega_count']; ?>)
        </a>
        <a href="ilanlar.php?seviye=vip" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?= $seviye_filter == 'vip' ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/25' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'; ?>">
            ✨ VIP (<?= $stats['vip_count']; ?>)
        </a>
        <a href="ilanlar.php?seviye=gold" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?= $seviye_filter == 'gold' ? 'bg-yellow-500 text-slate-950 shadow-lg shadow-yellow-500/25' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'; ?>">
            ⭐ Gold (<?= $stats['gold_count']; ?>)
        </a>
        <a href="ilanlar.php?seviye=elit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?= $seviye_filter == 'elit' ? 'bg-sky-400 text-slate-950 shadow-lg shadow-sky-400/25' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'; ?>">
            💎 Elit (<?= $stats['elit_count']; ?>)
        </a>
    </div>

    <!-- Search Form -->
    <form method="GET" action="ilanlar.php" class="relative w-full md:w-64">
        <?php if ($seviye_filter): ?>
            <input type="hidden" name="seviye" value="<?= htmlspecialchars($seviye_filter); ?>">
        <?php endif; ?>
        <input type="text" name="q" value="<?= htmlspecialchars($search_query); ?>" placeholder="İlan ara..." class="w-full pl-9 pr-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-sky-500">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-500"></i>
    </form>
</div>

<!-- LISTINGS TABLE CONTAINER -->
<div class="glass-panel rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <th class="py-3 px-4 w-12">Sıra</th>
                    <th class="py-3 px-4">Görsel</th>
                    <th class="py-3 px-4">İlan Adı</th>
                    <th class="py-3 px-4">Kategori</th>
                    <th class="py-3 px-4">Seviye</th>
                    <th class="py-3 px-4">Durum</th>
                    <th class="py-3 px-4 text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-sm">
                <?php if (count($ilanlar) > 0): ?>
                    <?php foreach($ilanlar as $row): ?>
                    <tr class="hover:bg-slate-800/40 transition-colors group" id="tr-<?= $row['id']; ?>">
                        <td class="py-3 px-4 font-bold text-slate-500 text-xs">
                            #<?= intval($row['sira']); ?>
                        </td>
                        <td class="py-3 px-4">
                            <img src="../modelresim/<?= htmlspecialchars($row['resim'] ?: 'resimyok.png'); ?>" alt="" class="w-10 h-14 object-cover rounded-lg border border-slate-700 shadow-md">
                        </td>
                        <td class="py-3 px-4 font-semibold text-white group-hover:text-sky-400 transition-colors">
                            <?= htmlspecialchars($row['isim']); ?>
                        </td>
                        <td class="py-3 px-4 text-xs text-slate-400">
                            <?= htmlspecialchars($row['kategori_baslik'] ?? 'Belirtilmemiş'); ?>
                        </td>
                        <td class="py-3 px-4">
                            <?php if ($row['seviye'] == 'mega'): ?>
                                <span class="px-2.5 py-1 text-[11px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-lg">👑 Mega VIP</span>
                            <?php elseif ($row['seviye'] == 'vip'): ?>
                                <span class="px-2.5 py-1 text-[11px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-lg">✨ VIP</span>
                            <?php elseif ($row['seviye'] == 'gold'): ?>
                                <span class="px-2.5 py-1 text-[11px] font-bold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 rounded-lg">⭐ Gold</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-[11px] font-bold bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-lg">💎 Elit</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Yayında
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-1">
                            <a href="galeri.php?id=<?= $row['id']; ?>" class="p-2 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/20 text-xs transition-all inline-flex items-center justify-center" title="Galeri Fotoğrafları">
                                <i class="fa-solid fa-images"></i>
                            </a>
                            <a href="ilan-duzenle.php?id=<?= $row['id']; ?>" class="p-2 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/20 text-xs transition-all inline-flex items-center justify-center" title="Düzenle">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="../ilan/<?= htmlspecialchars($row['seo']); ?>/<?= $row['id']; ?>.html" target="_blank" class="p-2 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 text-xs transition-all inline-flex items-center justify-center" title="Sitede Gör">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button onclick="deleteIlan(<?= $row['id']; ?>)" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 text-xs transition-all inline-flex items-center justify-center" title="Sil">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500 text-xs">
                            Kayıtlı ilan bulunamadı.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Sayfalama -->
    <?php if ($sayfasayisi > 1): ?>
    <div class="mt-6 flex items-center justify-center gap-2">
        <?php for($i = 1; $i <= $sayfasayisi; $i++): ?>
            <a href="ilanlar.php?sayfa=<?= $i; ?><?= $seviye_filter ? '&seviye='.$seviye_filter : ''; ?><?= $search_query ? '&q='.urlencode($search_query) : ''; ?>" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?= ($i == $sayfa) ? 'bg-sky-500 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'; ?>">
                <?= $i; ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteIlan(id) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: "Bu ilan kalıcı olarak silinecektir!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#334155',
        confirmButtonText: 'Evet, Sil!',
        cancelButtonText: 'Vazgeç',
        background: '#0f172a',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('ilansil.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            }).then(() => {
                document.getElementById('tr-' + id).remove();
                showNotification('success', 'İlan başarıyla silindi.');
            });
        }
    });
}
</script>

<?php require_once('footer.php'); ?>
