<?php
session_start();
include '../koneksi.php';

// Keamanan: Cek Login
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kader') {
    header("location:../login.php");
    exit;
}

$bulan_ini = date('m');
$tahun_ini = date('Y');
$nama_bulan = date('F');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jemput Bola - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style_sidebar.css">
    <style>
        /* SINKRONISASI TYPOGRAPHY DENGAN EKOSISTEM UTAMA STUNTGUARD */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="flex">

    <?php include 'sidebar.php'; ?>

    <div class="flex-1 ml-72 p-12">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fas fa-running text-amber-500"></i> Target Jemput Bola
            </h1>
            <p class="text-slate-400 font-medium mt-2 text-base">
                Daftar balita yang <span class="text-rose-500 font-bold underline decoration-wavy decoration-1">belum
                    melakukan timbang rutin</span> di bulan <?= $nama_bulan; ?>.
            </p>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm border border-slate-50 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Balita &
                            Orang Tua</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Kontak
                            WhatsApp</th>
                        <th
                            class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">
                            Status Absensi</th>
                        <th
                            class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center w-52">
                            Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php
                    // QUERY SMART: Cari anak yang ID-nya TIDAK ADA di tabel pengukuran bulan ini
                    $sql = "SELECT * FROM anak WHERE id NOT IN (
                                SELECT anak_id FROM pengukuran 
                                WHERE MONTH(tgl_pengukuran) = '$bulan_ini' 
                                AND YEAR(tgl_pengukuran) = '$tahun_ini'
                            ) ORDER BY nama_lengkap ASC";

                    $query = mysqli_query($koneksi, $sql);
                    $cek = mysqli_num_rows($query);

                    if ($cek > 0) {
                        while ($d = mysqli_fetch_array($query)) {
                            // Siapkan Pesan WA Otomatis asli bawaan sistem Anda
                            $pesan = "Halo Bunda " . $d['nama_ibu'] . ", kami dari Posyandu ingin mengingatkan bahwa Ananda " . $d['nama_lengkap'] . " belum melakukan penimbangan rutin untuk bulan " . $nama_bulan . ". Ditunggu kehadirannya ya Bun!";

                            // Pembersihan format nomor hp (mengganti awalan 0 menjadi 62 jika diperlukan oleh API WA)
                            $hp_raw = $d['no_hp'] ?? '';
                            $wa_formatted = preg_replace('/^0/', '62', $hp_raw);

                            $wa_link = "https://api.whatsapp.com/send?phone=" . $wa_formatted . "&text=" . urlencode($pesan);
                            ?>
                            <tr class="hover:bg-amber-50/20 transition-colors group">
                                <td class="px-8 py-5">
                                    <div
                                        class="font-extrabold text-slate-800 text-base group-hover:text-amber-600 transition-colors">
                                        <?= $d['nama_lengkap']; ?></div>
                                    <div class="text-xs text-slate-400 font-semibold mt-0.5">Ibu: <?= $d['nama_ibu']; ?></div>
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 font-mono text-xs font-bold text-slate-600 tracking-wide">
                                        <i class="fab fa-whatsapp text-emerald-500 text-sm"></i> <?= $hp_raw; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span
                                        class="bg-rose-50 text-rose-600 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border border-rose-100/50">
                                        Belum Timbang
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-center">
                                        <button
                                            onclick="previewWhatsApp('<?= addslashes($wa_link); ?>', '<?= addslashes($pesan); ?>')"
                                            class="bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-5 py-3 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm shadow-emerald-100/10 transition-all active:scale-95">
                                            <i class="fab fa-whatsapp text-base"></i> Ingatkan Bunda
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' class='p-12 text-center font-extrabold text-emerald-600 bg-emerald-50/50 rounded-[40px] text-sm'>Semua balita sudah melakukan penimbangan bulan ini! 🌟</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="jemputModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div class="flex justify-center mb-6">
                <div
                    class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-paper-plane"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Kirim Pengingat Timbang</h3>
            <p class="text-slate-400 text-xs text-center font-medium mb-4">Pratinjau pesan teks WhatsApp yang akan
                ditujukan ke orang tua:</p>

            <div
                class="bg-[#e5ddd5] p-4 rounded-2xl border border-slate-200 font-sans text-xs text-slate-800 shadow-inner mb-6 whitespace-pre-line leading-relaxed relative overflow-hidden">
                <div class="absolute inset-0 opacity-[0.06] pointer-events-none"
                    style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');">
                </div>
                <div class="bg-white p-3 rounded-xl shadow-sm relative max-w-[95%]">
                    <span id="previewMessageText"></span>
                    <span class="block text-[9px] text-slate-400 text-right mt-1.5">Selesai Pratinjau</span>
                </div>
            </div>

            <div class="flex gap-4">
                <button onclick="closeJemputModal()"
                    class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors text-sm">
                    Batal
                </button>
                <button onclick="dispatchWhatsApp()"
                    class="flex-1 bg-emerald-600 text-white font-bold py-4 rounded-2xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100 text-sm">
                    Kirim Sekarang
                </button>
            </div>
        </div>
    </div>

    <script>
        let pendingTargetUrl = '';

        function previewWhatsApp(linkUrl, textMessage) {
            pendingTargetUrl = linkUrl;

            // Render pratinjau teks ke balon pesan di dalam modal
            document.getElementById('previewMessageText').innerText = textMessage;

            // Tampilkan animasi pop-up modal
            const modal = document.getElementById('jemputModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function cancelJemputModal() {
            closeJemputModal();
        }

        function closeJemputModal() {
            const modal = document.getElementById('jemputModal');
            const content = document.getElementById('modalContent');
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                pendingTargetUrl = '';
            }, 300);
        }

        function dispatchWhatsApp() {
            if (pendingTargetUrl) {
                window.open(pendingTargetUrl, '_blank');
                closeJemputModal();
            }
        }
    </script>
</body>

</html>