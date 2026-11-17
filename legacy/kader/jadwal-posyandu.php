<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kader') {
    header("location:../login.php");
    exit;
}

// Ambil data anak dan nomor telepon orang tua (pastikan ada kolom no_hp di tabel anak)
$query = mysqli_query($koneksi, "SELECT * FROM anak ORDER BY nama_lengkap ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Posyandu - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style_sidebar.css">
    <style>
        /* SINKRONISASI TYPOGRAPHY DENGAN HALAMAN UTAMA STUNTGUARD */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>

<body class="flex">
    <?php include 'sidebar.php'; ?>

    <div class="flex-1 ml-72 p-12">

        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fab fa-whatsapp text-emerald-500"></i> Pengingat Jadwal Posyandu
            </h1>
            <p class="text-slate-400 font-medium mt-2">Kirimkan undangan digital kepada orang tua melalui WhatsApp.</p>
        </div>

        <div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-50">

            <div
                class="bg-slate-50 rounded-[28px] p-6 mb-8 border border-slate-100 flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="flex-1 w-full">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Set
                        Tanggal Posyandu Berikutnya</label>
                    <input type="date" id="tgl_posyandu"
                        class="w-full p-4 rounded-2xl bg-white border border-slate-200 focus:border-indigo-500 text-slate-700 font-bold outline-none transition-all"
                        value="<?= date('Y-m-d'); ?>">
                </div>
                <div class="flex-1 w-full">
                    <label
                        class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Lokasi
                        Kegiatan</label>
                    <input type="text" id="lokasi"
                        class="w-full p-4 rounded-2xl bg-white border border-slate-200 focus:border-indigo-500 text-slate-700 font-bold outline-none transition-all"
                        placeholder="Contoh: Balai Desa RW 02">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th
                                class="text-left py-4 px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest w-16 text-center">
                                No</th>
                            <th
                                class="text-left py-4 px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                Nama Anak</th>
                            <th
                                class="text-left py-4 px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                Orang Tua (Ibu)</th>
                            <th
                                class="text-left py-4 px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                No. WhatsApp</th>
                            <th
                                class="text-left py-4 px-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center w-48">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php
                        $no = 1;
                        while ($d = mysqli_fetch_array($query)) {
                            $hp = $d['no_hp'] ?? '';
                            $wa_number = preg_replace('/^0/', '62', $hp);
                            ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-5 px-4 font-bold text-slate-400 text-center text-sm"><?= $no++; ?></td>
                                <td class="py-5 px-4">
                                    <span
                                        class="font-extrabold text-slate-800 text-base group-hover:text-indigo-600 transition-colors"><?= $d['nama_lengkap']; ?></span>
                                </td>
                                <td class="py-5 px-4 font-semibold text-slate-500 text-sm"><?= $d['nama_ibu'] ?? '-'; ?>
                                </td>
                                <td class="py-5 px-4 font-bold text-slate-600 text-sm">
                                    <?= $hp ? '<span class="bg-slate-100 px-3 py-1.5 rounded-xl text-xs text-slate-600 font-mono tracking-wide">' . $hp . '</span>' : '<span class="text-rose-500 bg-rose-50 px-3 py-1.5 rounded-xl text-xs font-bold">Belum diisi</span>'; ?>
                                </td>
                                <td class="py-5 px-4 text-center">
                                    <?php if ($hp): ?>
                                        <button onclick="kirimWA('<?= $wa_number ?>', '<?= addslashes($d['nama_lengkap']) ?>')"
                                            class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-2xl text-xs font-bold shadow-sm shadow-emerald-100/10 transition-all active:scale-95">
                                            <i class="fab fa-whatsapp text-sm"></i> Kirim Undangan
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div id="waModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div class="flex justify-center mb-6">
                <div
                    class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    <i class="fab fa-whatsapp"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Pratinjau Undangan Digital</h3>
            <p class="text-slate-400 text-xs text-center font-medium mb-4">Pesan berikut akan diteruskan ke aplikasi
                WhatsApp Web/Mobile orang tua:</p>

            <div
                class="bg-[#e5ddd5] p-4 rounded-2xl border border-slate-200 font-sans text-xs text-slate-800 shadow-inner mb-6 whitespace-pre-line leading-relaxed relative overflow-hidden">
                <div class="absolute inset-0 opacity-[0.06] pointer-events-none"
                    style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');">
                </div>
                <div class="bg-white p-3 rounded-xl shadow-sm relative max-w-[90%]">
                    <span id="previewText"></span>
                    <span class="block text-[9px] text-slate-400 text-right mt-1">Selesai Pratinjau</span>
                </div>
            </div>

            <div class="flex gap-4">
                <button onclick="closeModal()"
                    class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors text-sm">
                    Kembali
                </button>
                <button onclick="executeKirimWA()"
                    class="flex-1 bg-emerald-600 text-white font-bold py-4 rounded-2xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100 text-sm">
                    Buka WhatsApp
                </button>
            </div>
        </div>
    </div>

    <script>
        // Variabel penampung eksekusi data global
        let activePayload = { no_hp: '', url: '' };

        function kirimWA(no_hp, nama_anak) {
            const tglRaw = document.getElementById('tgl_posyandu').value;
            const lokasi = document.getElementById('lokasi').value || "Posyandu StuntGuard";

            // Format format tanggal ramah dibaca (d-m-Y)
            let tglFormatted = tglRaw;
            if (tglRaw) {
                const parts = tglRaw.split('-');
                if (parts.length === 3) tglFormatted = `${parts[2]}-${parts[1]}-${parts[0]}`;
            }

            // Format Pesan (Sesuai fungsionalitas aseli Anda)
            const teks = `Halo Bunda, mengingatkan kembali jadwal Posyandu untuk Ananda *${nama_anak}* pada:\n\n` +
                `📅 Tanggal: *${tglFormatted}*\n` +
                `📍 Lokasi: *${lokasi}*\n\n` +
                `Mohon kehadirannya untuk pemantauan tumbuh kembang (berat & tinggi badan). Terima kasih!`;

            const encodeTeks = encodeURIComponent(teks);
            const urlFinal = `https://wa.me/${no_hp}?text=${encodeTeks}`;

            // Simpan payload untuk eksekusi tombol konfirmasi di dalam modal
            activePayload.no_hp = no_hp;
            activePayload.url = urlFinal;

            // Injeksi teks ke balon chat pratinjau (mengubah format bintang bawaan WA menjadi bold HTML untuk dibaca manusia di modal)
            const htmlPreview = teks.replace(/\*(.*?)\*/g, '<strong>$1</strong>');
            document.getElementById('previewText').innerHTML = htmlPreview;

            // Membuka Animasi Pop-up Modal Kustom
            const modal = document.getElementById('waModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('waModal');
            const content = document.getElementById('modalContent');
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                activePayload = { no_hp: '', url: '' };
            }, 300);
        }

        function executeKirimWA() {
            if (activePayload.url) {
                window.open(activePayload.url, '_blank');
                closeModal();
            }
        }

        // Penanda Menu Navigasi Aktif bawaan rancangan Anda
        document.getElementById('nav-jadwal').classList.add('active');
    </script>
</body>

</html>