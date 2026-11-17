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
    <title>Panduan Gizi - StuntGuard</title>
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
                <i class="fas fa-apple-whole text-rose-500"></i> Panduan Gizi & Nutrisi
            </h1>
            <p class="text-slate-400 font-medium mt-2 text-base">Informasi edukasi untuk pencegahan stunting melalui
                pola makan sehat.</p>
        </div>

        <div class="bg-blue-50/70 border-l-4 border-blue-500 p-6 rounded-r-[24px] mb-8 shadow-sm shadow-blue-100/10">
            <h4 class="text-base font-extrabold text-blue-900 mb-1 flex items-center gap-2">
                <i class="fas fa-star text-blue-500"></i> Pentingnya 1000 HPK
            </h4>
            <p class="text-sm font-semibold text-blue-700/90 leading-relaxed">
                1000 Hari Pertama Kehidupan adalah masa emas pencegahan stunting dimulai dari awal masa kehamilan hingga
                anak genap berusia 2 tahun.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <div
                class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-md group">
                <div
                    class="w-full h-48 bg-slate-50 flex items-center justify-center text-slate-300 group-hover:text-amber-500 group-hover:bg-amber-50/40 transition-colors duration-300">
                    <i class="fas fa-baby fa-4x transition-transform group-hover:scale-110"></i>
                </div>
                <div class="p-6">
                    <h3
                        class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">
                        MPASI Bergizi Kaya Protein</h3>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed mb-6">Panduan memberikan makanan
                        pendamping ASI yang mengandung protein hewani untuk pertumbuhan tulang anak.</p>
                    <button
                        onclick="openGuideModal('MPASI Bergizi Kaya Protein', 'Setelah anak berusia 6 bulan, ASI saja tidak cukup untuk memenuhi kebutuhan gizinya. Berikan MPASI yang kaya akan protein hewani seperti telur, ayam, ikan, atau daging.\n\nProtein hewani mengandung asam amino esensial lengkap yang sangat dibutuhkan untuk memicu pertumbuhan sel dan tulang anak, serta mencegah risiko terjadinya stunting.')"
                        class="w-full text-center py-3 bg-slate-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-2xl text-xs font-bold transition-all active:scale-95">
                        Baca Selengkapnya
                    </button>
                </div>
            </div>

            <div
                class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-md group">
                <div
                    class="w-full h-48 bg-slate-50 flex items-center justify-center text-slate-300 group-hover:text-emerald-500 group-hover:bg-emerald-50/40 transition-colors duration-300">
                    <i class="fas fa-hand-holding-droplet fa-4x transition-transform group-hover:scale-110"></i>
                </div>
                <div class="p-6">
                    <h3
                        class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">
                        Pentingnya ASI Eksklusif</h3>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed mb-6">Manfaat memberikan ASI saja
                        selama 6 bulan pertama tanpa tambahan cairan atau makanan lain.</p>
                    <button
                        onclick="openGuideModal('Pentingnya ASI Eksklusif', 'ASI Eksklusif adalah pemberian air susu ibu tanpa makanan atau minuman tambahan lain sejak bayi lahir sampai usia 6 bulan.\n\nASI merupakan nutrisi terbaik yang kaya akan zat antibodi untuk menjaga bayi dari infeksi penyakit, membentuk sistem imun yang kuat, sekaligus meletakkan fondasi kognitif dasar yang prima pada awal kehidupan anak.')"
                        class="w-full text-center py-3 bg-slate-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-2xl text-xs font-bold transition-all active:scale-95">
                        Baca Selengkapnya
                    </button>
                </div>
            </div>

            <div
                class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-md group">
                <div
                    class="w-full h-48 bg-slate-50 flex items-center justify-center text-slate-300 group-hover:text-cyan-500 group-hover:bg-cyan-50/40 transition-colors duration-300">
                    <i class="fas fa-faucet-detergent fa-4x transition-transform group-hover:scale-110"></i>
                </div>
                <div class="p-6">
                    <h3
                        class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">
                        Sanitasi & Air Bersih</h3>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed mb-6">Hubungan antara kebersihan
                        lingkungan dengan penyerapan nutrisi yang maksimal pada tubuh balita.</p>
                    <button
                        onclick="openGuideModal('Sanitasi & Air Bersih', 'Kondisi sanitasi lingkungan yang buruk dan ketiadaan air bersih bisa memicu penyakit infeksi berulang pada balita seperti diare kronis.\n\nSaat anak sering mengalami infeksi gastrointestinal, energi dan nutrisi yang seharusnya diserap tubuh untuk tumbuh kembang justru terbuang sia-sia melawan penyakit. Oleh karena itu, jaga kebersihan lingkungan rumah dan cuci tangan pakai sabun.')"
                        class="w-full text-center py-3 bg-slate-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-2xl text-xs font-bold transition-all active:scale-95">
                        Baca Selengkapnya
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div id="guideModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[40px] p-10 max-w-lg w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div class="flex justify-between items-start mb-6">
                <div class="bg-indigo-50 text-indigo-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl">
                    <i class="fas fa-book-open"></i>
                </div>
                <button onclick="closeGuideModal()"
                    class="text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <h3 class="text-2xl font-extrabold text-slate-800 mb-4" id="modalTitle">Judul Artikel</h3>
            <div class="bg-slate-50 border border-slate-100 p-6 rounded-2xl font-normal text-sm text-slate-600 leading-relaxed whitespace-pre-line mb-8 shadow-inner"
                id="modalBody">
                Isi artikel panduan kesehatan...
            </div>

            <button onclick="closeGuideModal()"
                class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-2xl transition-colors text-sm text-center shadow-lg shadow-slate-900/10">
                Selesai Membaca
            </button>
        </div>
    </div>

    <script>
        function openGuideModal(title, contentText) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalBody').innerText = contentText;

            const modal = document.getElementById('guideModal');
            const content = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function closeGuideModal() {
            const modal = document.getElementById('guideModal');
            const content = document.getElementById('modalContent');

            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Set menu panduan aktif di sidebar bawaan rancangan Anda
        const navPanduan = document.getElementById('nav-panduan');
        if (navPanduan) navPanduan.classList.add('active');
    </script>
</body>

</html>