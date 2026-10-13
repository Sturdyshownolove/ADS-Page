<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['site_user'])) {
    header('Location: login.php');
    exit();
}

require_once(__DIR__ . '/../include/connect.php');
require_once(__DIR__ . '/fonksiyon.php');

// Tablo İstatistikleri
try {
    $stats = $conn->query("
        SELECT
            COUNT(id) AS total_ilan,
            COUNT(CASE WHEN seviye = 'mega' THEN 1 END) AS mega_count,
            COUNT(CASE WHEN seviye = 'vip' THEN 1 END) AS vip_count,
            COUNT(CASE WHEN seviye = 'gold' THEN 1 END) AS gold_count,
            COUNT(CASE WHEN seviye = 'elit' THEN 1 END) AS elit_count,
            COUNT(CASE WHEN durum = 'Yayında' THEN 1 END) AS aktif_count
        FROM uyeler
    ")->fetch(PDO::FETCH_ASSOC);

    $blog_count = $conn->query("SELECT COUNT(id) FROM blog")->fetchColumn();
    $kat_count = $conn->query("SELECT COUNT(id) FROM kategori")->fetchColumn();
} catch (Exception $e) {
    $stats = ['total_ilan' => 0, 'mega_count' => 0, 'vip_count' => 0, 'gold_count' => 0, 'elit_count' => 0, 'aktif_count' => 0];
    $blog_count = $kat_count = 0;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="tr" class="dark h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli | PortalEX</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            500: '#0284c7',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-panel { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .glass-card { background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.06); }
        .glass-card:hover { border-color: rgba(56, 189, 248, 0.3); }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0 transition-all duration-300 z-30">
        <!-- Logo -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800/80 bg-slate-900/50">
            <a href="index.php" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-800 p-1.5 flex items-center justify-center border border-slate-700 shadow-md">
                    <img src="../favicon.ico" alt="PortalEX" class="w-6 h-6 object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-base tracking-tight text-white leading-none">BUCA <span class="text-sky-400">EX</span></span>
                    <span class="text-[10px] text-slate-400 font-medium mt-1">PortalEX</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-6">
            
            <!-- Ana Menü -->
            <div>
                <div class="px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Genel</div>
                <nav class="space-y-1">
                    <a href="index.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'index.php') ? 'bg-sky-600/10 text-sky-400 border border-sky-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                            <span>Kontrol Paneli</span>
                        </div>
                    </a>
                    <a href="ilan-ekle.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'ilan-ekle.php') ? 'bg-emerald-600/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-plus w-4 text-center text-emerald-400"></i>
                            <span>Yeni İlan Ekle</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[10px] font-bold bg-emerald-500/20 text-emerald-400 rounded-md">YENİ</span>
                    </a>
                </nav>
            </div>

            <!-- İlan Yönetimi -->
            <div>
                <div class="px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">İlan Yönetimi</div>
                <nav class="space-y-1">
                    <a href="ilanlar.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'ilanlar.php' && !isset($_GET['seviye'])) ? 'bg-sky-600/10 text-sky-400 border border-sky-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-layer-group w-4 text-center"></i>
                            <span>Tüm İlanlar</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold bg-slate-800 text-slate-300 rounded-full"><?= $stats['total_ilan']; ?></span>
                    </a>

                    <!-- Mega VIP -->
                    <a href="ilanlar.php?seviye=mega" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= (isset($_GET['seviye']) && $_GET['seviye'] == 'mega') ? 'bg-rose-500/15 text-rose-400 border border-rose-500/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-crown text-rose-500 w-4 text-center"></i>
                            <span>Mega VIP (3'lü)</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-bold bg-rose-500/20 text-rose-400 rounded-full"><?= $stats['mega_count']; ?></span>
                    </a>

                    <!-- VIP İlanlar -->
                    <a href="ilanlar.php?seviye=vip" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= (isset($_GET['seviye']) && $_GET['seviye'] == 'vip') ? 'bg-amber-500/15 text-amber-400 border border-amber-500/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-wand-magic-sparkles text-amber-400 w-4 text-center"></i>
                            <span>VIP İlanlar (5'li)</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-bold bg-amber-500/20 text-amber-400 rounded-full"><?= $stats['vip_count']; ?></span>
                    </a>

                    <!-- Gold İlanlar -->
                    <a href="ilanlar.php?seviye=gold" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= (isset($_GET['seviye']) && $_GET['seviye'] == 'gold') ? 'bg-yellow-500/15 text-yellow-400 border border-yellow-500/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-star text-yellow-400 w-4 text-center"></i>
                            <span>Gold İlanlar (5'li)</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-bold bg-yellow-500/20 text-yellow-400 rounded-full"><?= $stats['gold_count']; ?></span>
                    </a>

                    <!-- Elit İlanlar -->
                    <a href="ilanlar.php?seviye=elit" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= (isset($_GET['seviye']) && $_GET['seviye'] == 'elit') ? 'bg-sky-500/15 text-sky-400 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-gem text-sky-400 w-4 text-center"></i>
                            <span>Elit İlanlar (5'li)</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-bold bg-sky-500/20 text-sky-400 rounded-full"><?= $stats['elit_count']; ?></span>
                    </a>
                </nav>
            </div>

            <!-- İçerik & Ayarlar -->
            <div>
                <div class="px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Sistem & İçerik</div>
                <nav class="space-y-1">
                    <a href="kategoriler.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'kategoriler.php') ? 'bg-sky-600/10 text-sky-400 border border-sky-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-folder-tree w-4 text-center"></i>
                            <span>Kategoriler</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold bg-slate-800 text-slate-300 rounded-full"><?= $kat_count; ?></span>
                    </a>
                    <a href="blog.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'blog.php') ? 'bg-sky-600/10 text-sky-400 border border-sky-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-newspaper w-4 text-center"></i>
                            <span>Blog / Makaleler</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold bg-slate-800 text-slate-300 rounded-full"><?= $blog_count; ?></span>
                    </a>
                    <a href="toplu-makale.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'toplu-makale.php') ? 'bg-sky-600/10 text-sky-400 border border-sky-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-bolt w-4 text-center text-amber-400"></i>
                            <span>Toplu Makale Yükle</span>
                        </div>
                    </a>
                    <a href="linkler.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'linkler.php') ? 'bg-sky-600/10 text-sky-400 border border-sky-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-link w-4 text-center"></i>
                            <span>Partner Linkler</span>
                        </div>
                    </a>
                    <a href="ayarlar.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'ayarlar.php') ? 'bg-sky-600/10 text-sky-400 border border-sky-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-sliders w-4 text-center"></i>
                            <span>Site & AMP Ayarları</span>
                        </div>
                    </a>
                    <a href="profil.php" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'profil.php') ? 'bg-sky-600/10 text-sky-400 border border-sky-500/20' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-user-shield w-4 text-center text-amber-400"></i>
                            <span>Şifre & Profil</span>
                        </div>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Bottom User Bar -->
        <div class="p-3 border-t border-slate-800/80 bg-slate-900/40">
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center font-bold text-sky-400 text-sm">
                        <?= strtoupper(substr($_SESSION['site_user'] ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-white truncate max-w-[100px]"><?= htmlspecialchars($_SESSION['site_user']); ?></span>
                        <span class="text-[10px] text-emerald-400 font-medium">● Çevrimiçi</span>
                    </div>
                </div>
                <a href="logout.php" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all" title="Güvenli Çıkış">
                    <i class="fa-solid fa-power-off text-xs"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- TOPBAR -->
        <header class="h-16 bg-slate-900/80 border-b border-slate-800/80 flex items-center justify-between px-6 backdrop-blur-md shrink-0">
            <div class="flex items-center gap-4">
                <h1 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                    <span class="text-slate-400">Yönetim /</span>
                    <?php 
                        if ($current_page == 'index.php') echo 'Kontrol Paneli';
                        elseif ($current_page == 'ilanlar.php') echo (isset($_GET['seviye']) ? strtoupper($_GET['seviye']) . ' İlanlar' : 'Tüm İlanlar');
                        elseif ($current_page == 'ilan-ekle.php') echo 'Yeni İlan Ekle';
                        elseif ($current_page == 'ilan-duzenle.php') echo 'İlan Düzenle';
                        elseif ($current_page == 'kategoriler.php') echo 'Kategori Yönetimi';
                        elseif ($current_page == 'blog.php') echo 'Makale Yönetimi';
                        elseif ($current_page == 'blog-duzenle.php') echo 'Makale Düzenle';
                        elseif ($current_page == 'toplu-makale.php') echo 'Otomatik Toplu Makale Yükleyici';
                        elseif ($current_page == 'ayarlar.php') echo 'Site ve AMP Ayarları';
                        else echo 'Panel';
                    ?>
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="../" target="_blank" class="flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/20 text-xs font-semibold transition-all">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>Canlı Siteyi Gör</span>
                </a>
                <a href="../amp/" target="_blank" class="flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/20 text-xs font-semibold transition-all">
                    <i class="fa-solid fa-bolt text-[10px]"></i>
                    <span>AMP Sürümü</span>
                </a>
            </div>
        </header>

        <!-- PAGE CONTENT CONTAINER -->
        <main class="flex-1 overflow-y-auto p-6 bg-slate-950">
