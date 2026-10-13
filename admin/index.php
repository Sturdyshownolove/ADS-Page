<?php require_once('header.php'); ?>

<!-- DASHBOARD TOP HEADER -->
<div class="mb-8">
    <h2 class="text-2xl font-extrabold text-white tracking-tight">Hoş Geldiniz, <span class="text-sky-400"><?= htmlspecialchars($_SESSION['site_user']); ?></span> 👋</h2>
    <p class="text-xs text-slate-400 mt-1">PortalEX portalının güncel durum özeti ve hızlı yönetim araçları.</p>
</div>

<!-- KPI STATS GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <!-- 1. MEGA VIP -->
    <a href="ilanlar.php?seviye=mega" class="glass-card p-5 rounded-2xl flex items-center justify-between group transition-all">
        <div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Mega VIP İlanlar</span>
            <div class="text-3xl font-black text-white group-hover:text-rose-400 transition-colors"><?= $stats['mega_count']; ?> <span class="text-xs font-normal text-slate-500">adet</span></div>
            <span class="text-[11px] text-rose-400/90 font-medium inline-block mt-2"><i class="fa-solid fa-grid-2 text-[10px] mr-1"></i>3'lü Dikey Dizilim</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-crown"></i>
        </div>
    </a>

    <!-- 2. VIP İLANLAR -->
    <a href="ilanlar.php?seviye=vip" class="glass-card p-5 rounded-2xl flex items-center justify-between group transition-all">
        <div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">VIP İlanlar</span>
            <div class="text-3xl font-black text-white group-hover:text-amber-400 transition-colors"><?= $stats['vip_count']; ?> <span class="text-xs font-normal text-slate-500">adet</span></div>
            <span class="text-[11px] text-amber-400/90 font-medium inline-block mt-2"><i class="fa-solid fa-grid-2 text-[10px] mr-1"></i>5'li Dikey Dizilim</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
    </a>

    <!-- 3. GOLD İLANLAR -->
    <a href="ilanlar.php?seviye=gold" class="glass-card p-5 rounded-2xl flex items-center justify-between group transition-all">
        <div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Gold İlanlar</span>
            <div class="text-3xl font-black text-white group-hover:text-yellow-400 transition-colors"><?= $stats['gold_count']; ?> <span class="text-xs font-normal text-slate-500">adet</span></div>
            <span class="text-[11px] text-yellow-400/90 font-medium inline-block mt-2"><i class="fa-solid fa-grid-2 text-[10px] mr-1"></i>5'li Dikey Dizilim</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-star"></i>
        </div>
    </a>

    <!-- 4. ELİT İLANLAR -->
    <a href="ilanlar.php?seviye=elit" class="glass-card p-5 rounded-2xl flex items-center justify-between group transition-all">
        <div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Elit İlanlar</span>
            <div class="text-3xl font-black text-white group-hover:text-sky-400 transition-colors"><?= $stats['elit_count']; ?> <span class="text-xs font-normal text-slate-500">adet</span></div>
            <span class="text-[11px] text-sky-400/90 font-medium inline-block mt-2"><i class="fa-solid fa-grid-2 text-[10px] mr-1"></i>5'li Dikey Dizilim</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-gem"></i>
        </div>
    </a>

</div>

<!-- RECENT LISTINGS TABLE -->
<div class="glass-panel rounded-2xl p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-white tracking-tight">Son Eklenen İlanlar</h3>
            <p class="text-xs text-slate-400">Sistemde en son güncellenen 10 ilan ve detay durumu.</p>
        </div>
        <a href="ilanlar.php" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700 transition-all flex items-center gap-2">
            <span>Tümünü Gör</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <?php
    $recentQuery = $conn->query("
        SELECT u.id, u.seo, u.isim, u.resim, u.seviye, u.durum, u.btarih, k.baslik AS kategori_baslik
        FROM uyeler u
        LEFT JOIN kategori k ON u.kategori = k.id
        ORDER BY u.id DESC LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <th class="py-3 px-4">Görsel</th>
                    <th class="py-3 px-4">İlan Başlığı</th>
                    <th class="py-3 px-4">Kategori</th>
                    <th class="py-3 px-4">Seviye</th>
                    <th class="py-3 px-4">Durum</th>
                    <th class="py-3 px-4 text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-sm">
                <?php foreach($recentQuery as $row): ?>
                <tr class="hover:bg-slate-800/40 transition-colors group">
                    <td class="py-3 px-4">
                        <img src="../modelresim/<?= htmlspecialchars($row['resim'] ?: 'resimyok.png'); ?>" alt="" class="w-10 h-14 object-cover rounded-lg border border-slate-700 shadow-md">
                    </td>
                    <td class="py-3 px-4 font-semibold text-white group-hover:text-sky-400 transition-colors">
                        <?= htmlspecialchars($row['isim']); ?>
                    </td>
                    <td class="py-3 px-4 text-xs text-slate-400">
                        <?= htmlspecialchars($row['kategori_baslik'] ?? 'Genel'); ?>
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
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Yayında
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right space-x-1">
                        <a href="ilan-duzenle.php?id=<?= $row['id']; ?>" class="p-2 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/20 text-xs transition-all inline-flex items-center justify-center" title="Düzenle">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="../ilan/<?= htmlspecialchars($row['seo']); ?>/<?= $row['id']; ?>.html" target="_blank" class="p-2 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 text-xs transition-all inline-flex items-center justify-center" title="Sitede Gör">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('footer.php'); ?>
