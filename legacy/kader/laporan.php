<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kader') {
    header("location:../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Laporan - StuntGuard</title>
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
                Digital Reporting
            </h1>
            <p class="text-slate-400 font-medium mt-2">Pilih periode dan format dokumen untuk mencetak laporan bulanan.
            </p>
        </div>

        <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-50 max-w-2xl">
            <form action="proses-cetak.php" method="GET" target="_blank" onsubmit="showReportingModal(event, this)">

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <label
                            class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Pilih
                            Bulan</label>
                        <select name="bulan"
                            class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 font-bold text-slate-700 outline-none transition-all">
                            <?php
                            $bulan_arr = [
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember'
                            ];
                            foreach ($bulan_arr as $m => $nama) {
                                $sel = ($m == date('m')) ? 'selected' : '';
                                echo "<option value='$m' $sel>$nama</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Pilih
                            Tahun</label>
                        <select name="tahun"
                            class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 font-bold text-slate-700 outline-none transition-all">
                            <?php
                            $now = date('Y');
                            for ($y = $now; $y >= $now - 5; $y--) {
                                echo "<option value='$y'>$y</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mb-10">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Pilih
                        Format Laporan</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="relative flex items-center p-5 border-2 border-slate-100 rounded-2xl cursor-pointer hover:bg-emerald-50/40 transition-all group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="format" value="excel" class="hidden" checked>
                            <i class="fas fa-file-excel text-2xl text-emerald-500 mr-3"></i>
                            <span class="font-bold text-slate-700">Format EXCEL</span>
                        </label>
                        <label
                            class="relative flex items-center p-5 border-2 border-slate-100 rounded-2xl cursor-pointer hover:bg-rose-50/40 transition-all group has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                            <input type="radio" name="format" value="pdf" class="hidden">
                            <i class="fas fa-file-pdf text-2xl text-rose-500 mr-3"></i>
                            <span class="font-bold text-slate-700">Format PDF</span>
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white p-5 rounded-2xl font-black shadow-xl shadow-slate-900/10 transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-3 active:scale-[0.98]">
                    <i class="fas fa-print"></i> Generate Laporan
                </button>
            </form>
        </div>
    </div>

    <div id="reportModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[40px] p-10 max-w-sm w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div class="flex justify-center mb-6">
                <div class="relative w-16 h-16 flex items-center justify-center">
                    <div class="absolute inset-0 rounded-2xl bg-indigo-50 animate-pulse"></div>
                    <i class="fas fa-cog text-2xl text-indigo-600 animate-spin"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Menyiapkan Dokumen</h3>
            <p class="text-slate-400 text-xs text-center font-medium px-4 leading-relaxed">
                Sistem sedang mengompilasi dan memformat data laporan bulanan StuntGuard Anda...
            </p>

            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-6 overflow-hidden">
                <div
                    class="bg-indigo-600 h-full w-full rounded-full origin-left animate-[loadingBar_2s_ease-in-out_infinite]">
                </div>
            </div>
        </div>
    </div>

    <script>
        function showReportingModal(event, formElement) {
            // Membuka Animasi Pop-up Modal
            const modal = document.getElementById('reportModal');
            const content = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);

            // Menutup pop up secara otomatis setelah jendela cetak baru terbuka
            setTimeout(() => {
                modal.classList.add('opacity-0');
                content.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }, 2500);
        }
    </script>

    <style>
        @keyframes loadingBar {
            0% {
                transform: scaleX(0);
            }

            50% {
                transform: scaleX(0.7);
            }

            100% {
                transform: scaleX(1);
            }
        }
    </style>
</body>

</html>