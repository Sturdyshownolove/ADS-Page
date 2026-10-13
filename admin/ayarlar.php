<?php
require_once('header.php');

$msg = '';
$msg_type = '';

// Tüm Ayarları Güncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kaydet'])) {
    try {
        // 1. ayarlar tablosu
        $stmt_ayarlar = $conn->prepare("
            UPDATE ayarlar SET 
                siteadi = ?, 
                logoyazi = ?, 
                meta = ?, 
                anahtar = ?, 
                telefon = ?, 
                telefonyazi = ?, 
                siteurl = ?, 
                ampurl = ?, 
                anasayfa = ?, 
                yanpanel = ?, 
                copyright = ?, 
                yanpanelbaslik = ?, 
                ustalanyazisi = ?, 
                head = ?, 
                footer = ?,
                mapsapi = ?, 
                analytics = ?, 
                renk_arka = ?, 
                renk_headerarka = ?, 
                renk_headeryazi = ?, 
                renk_paketbaslik = ?, 
                renk_paketyazi = ?
            WHERE id = 1
        ");

        $stmt_ayarlar->execute([
            trim($_POST['siteadi'] ?? ''),
            trim($_POST['logoyazi'] ?? ''),
            trim($_POST['meta'] ?? ''),
            trim($_POST['anahtar'] ?? ''),
            trim($_POST['telefon'] ?? ''),
            trim($_POST['telefonyazi'] ?? ''),
            trim($_POST['siteurl'] ?? ''),
            trim($_POST['ampurl'] ?? ''),
            $_POST['anasayfa'] ?? '',
            $_POST['yanpanel'] ?? '',
            trim($_POST['copyright'] ?? ''),
            trim($_POST['yanpanelbaslik'] ?? ''),
            $_POST['ustalanyazisi'] ?? '',
            $_POST['head'] ?? '',
            $_POST['footer'] ?? '',
            trim($_POST['mapsapi'] ?? ''),
            trim($_POST['analytics'] ?? ''),
            trim($_POST['renk_arka'] ?? '#ffffff'),
            trim($_POST['renk_headerarka'] ?? '#be00cc'),
            trim($_POST['renk_headeryazi'] ?? '#ffffff'),
            trim($_POST['renk_paketbaslik'] ?? '#a000cc'),
            trim($_POST['renk_paketyazi'] ?? '#ffffff')
        ]);

        // 2. ampayar tablosu
        $amp_url_val = trim($_POST['amp_domain'] ?? '');

        $stmt_amp = $conn->prepare("
            UPDATE ampayar SET 
                amp_domain = ?, 
                amp_sub = ?, 
                amp_baslik = ?, 
                amp_iletisim = ?, 
                amp_telegram = ?, 
                amp_headerarkarenk = ?, 
                amp_headeryazirenk = ?, 
                amp_baslikarkarenk = ?, 
                amp_baslikyazirenk = ?, 
                amp_ilanarkarenk = ?, 
                amp_ilanyazirenk = ?
            WHERE amp_id = 1
        ");

        $stmt_amp->execute([
            $amp_url_val,
            trim($_POST['amp_sub'] ?? ''),
            trim($_POST['amp_baslik'] ?? ''),
            trim($_POST['amp_iletisim'] ?? ''),
            trim($_POST['amp_telegram'] ?? 'https://t.me/exampleportal'),
            trim($_POST['amp_headerarkarenk'] ?? '#ce0056'),
            trim($_POST['amp_headeryazirenk'] ?? '#ffffff'),
            trim($_POST['amp_baslikarkarenk'] ?? '#ce0056'),
            trim($_POST['amp_baslikyazirenk'] ?? '#ffffff'),
            trim($_POST['amp_ilanarkarenk'] ?? '#ce0056'),
            trim($_POST['amp_ilanyazirenk'] ?? '#ffffff')
        ]);

        // ayarlar tablosundaki ampurl alanını da aynı tek değere eşitle
        $conn->prepare("UPDATE ayarlar SET ampurl = ? WHERE id = 1")->execute([$amp_url_val]);

        $msg = 'Tüm site ve AMP ayarları başarıyla kaydedildi!';
        $msg_type = 'success';

    } catch (Exception $e) {
        $msg = 'Ayarlar güncellenirken hata oluştu: ' . $e->getMessage();
        $msg_type = 'error';
    }
}

// Verileri Yükle
$SiteBilgi = $conn->query("SELECT * FROM ayarlar WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$AmpBilgi = $conn->query("SELECT * FROM ampayar WHERE amp_id = 1")->fetch(PDO::FETCH_ASSOC);
?>

<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Tüm Site ve AMP Ayarları</h2>
            <p class="text-xs text-slate-400 mt-1">Sitenin URL, Telegram, SEO, renk ve teknik ayarlarının tamamını buradan düzenleyin.</p>
        </div>
    </div>

    <?php if ($msg): ?>
    <div class="p-4 mb-6 rounded-2xl border text-sm font-semibold flex items-center gap-3 <?= $msg_type == 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'; ?>">
        <i class="fa-solid <?= $msg_type == 'success' ? 'fa-circle-check text-lg' : 'fa-circle-exclamation text-lg'; ?>"></i>
        <span><?= htmlspecialchars($msg); ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-6">

        <!-- 1. BÖLÜM: TELEGRAM VE AMP AYARLARI -->
        <div class="glass-panel p-6 rounded-2xl space-y-4">
            <h3 class="text-base font-bold text-sky-400 flex items-center gap-2 pb-3 border-b border-slate-800">
                <i class="fa-solid fa-paper-plane"></i> <span>Telegram ve AMP Ayarları</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">✈ Telegram Reklam Yönlendirme Linki (*)</label>
                    <input type="url" name="amp_telegram" value="<?= htmlspecialchars($AmpBilgi['amp_telegram'] ?? 'https://t.me/exampleportal'); ?>" required placeholder="https://t.me/exampleportal" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono">
                    <p class="text-[10px] text-slate-400 mt-1">Boş reklam kartlarına basılınca doğrudan bu Telegram adresine gidilir.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">AMP Domain / URL (*)</label>
                    <input type="text" name="amp_domain" value="<?= htmlspecialchars($AmpBilgi['amp_domain'] ?? ''); ?>" placeholder="http://localhost/site/amp" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">AMP Site Başlığı</label>
                    <input type="text" name="amp_baslik" value="<?= htmlspecialchars($AmpBilgi['amp_baslik'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">AMP İletişim / WhatsApp Linki</label>
                    <input type="text" name="amp_iletisim" value="<?= htmlspecialchars($AmpBilgi['amp_iletisim'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono">
                </div>
            </div>
        </div>

        <!-- 2. BÖLÜM: GENEL SİTE URL VE İLETİŞİM BİLGİLERİ -->
        <div class="glass-panel p-6 rounded-2xl space-y-4">
            <h3 class="text-base font-bold text-emerald-400 flex items-center gap-2 pb-3 border-b border-slate-800">
                <i class="fa-solid fa-globe"></i> <span>Genel Site ve URL Ayarları</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Ana Site URL (SiteURL) (*)</label>
                    <input type="text" name="siteurl" value="<?= htmlspecialchars($SiteBilgi['siteurl'] ?? ''); ?>" placeholder="http://localhost/site/" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Site Adı / Başlığı</label>
                    <input type="text" name="siteadi" value="<?= htmlspecialchars($SiteBilgi['siteadi'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Logo Yazısı / Marka</label>
                    <input type="text" name="logoyazi" value="<?= htmlspecialchars($SiteBilgi['logoyazi'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">İletişim Telefonu</label>
                    <input type="text" name="telefon" value="<?= htmlspecialchars($SiteBilgi['telefon'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Telefon Buton Yazısı</label>
                    <input type="text" name="telefonyazi" value="<?= htmlspecialchars($SiteBilgi['telefonyazi'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Telif Hakkı (Copyright)</label>
                    <input type="text" name="copyright" value="<?= htmlspecialchars($SiteBilgi['copyright'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500">
                </div>
            </div>
        </div>

        <!-- 3. BÖLÜM: SEO VE İÇERİK METİNLERİ -->
        <div class="glass-panel p-6 rounded-2xl space-y-4">
            <h3 class="text-base font-bold text-amber-400 flex items-center gap-2 pb-3 border-b border-slate-800">
                <i class="fa-solid fa-magnifying-glass"></i> <span>SEO ve İçerik Metinleri</span>
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Ana Sayfa Meta Açıklaması</label>
                    <textarea name="meta" rows="2" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500"><?= htmlspecialchars($SiteBilgi['meta'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Anahtar Kelimeler (Keywords)</label>
                    <textarea name="anahtar" rows="2" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500"><?= htmlspecialchars($SiteBilgi['anahtar'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Üst Banner Duyuru Yazısı (Hero Text)</label>
                    <textarea name="ustalanyazisi" rows="2" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500"><?= htmlspecialchars($SiteBilgi['ustalanyazisi'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Ana Sayfa SEO Tanıtım Yazısı (HTML)</label>
                    <textarea name="anasayfa" rows="4" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500"><?= htmlspecialchars($SiteBilgi['anasayfa'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- 4. BÖLÜM: GELİŞMİŞ & KOD ENTEGRASYONLARI -->
        <div class="glass-panel p-6 rounded-2xl space-y-4">
            <h3 class="text-base font-bold text-purple-400 flex items-center gap-2 pb-3 border-b border-slate-800">
                <i class="fa-solid fa-code"></i> <span>Gelişmiş Kod & Entegrasyonlar</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Google Analytics Kodu / ID</label>
                    <input type="text" name="analytics" value="<?= htmlspecialchars($SiteBilgi['analytics'] ?? ''); ?>" placeholder="UA-XXXXX-X veya G-XXXXXX" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Google Maps API Key</label>
                    <input type="text" name="mapsapi" value="<?= htmlspecialchars($SiteBilgi['mapsapi'] ?? ''); ?>" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Özel Head Kodları (&lt;head&gt; içine basılır)</label>
                    <textarea name="head" rows="3" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono"><?= htmlspecialchars($SiteBilgi['head'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Özel Footer Kodları (Sayfa sonuna basılır)</label>
                    <textarea name="footer" rows="3" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-sky-500 font-mono"><?= htmlspecialchars($SiteBilgi['footer'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Kaydet Butonu -->
        <button type="submit" name="kaydet" class="w-full py-4 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-400 hover:to-sky-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all">
            <i class="fa-solid fa-floppy-disk mr-2"></i> Tüm Site ve AMP Ayarlarını Kaydet
        </button>

    </form>
</div>

<?php require_once('footer.php'); ?>
