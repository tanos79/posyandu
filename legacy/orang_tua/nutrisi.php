<?php
session_start();
include '../koneksi.php';

// Proteksi Halaman (Murni Bawaan Asli Anda)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] !== 'orang_tua') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id'];
$query_user = mysqli_query($koneksi, "SELECT id_anak FROM users WHERE id = '$id_user'");
$data_user = mysqli_fetch_assoc($query_user);
$id_anak_linked = $data_user['id_anak'] ?? 0;

$query_anak = mysqli_query($koneksi, "SELECT * FROM anak WHERE id = '$id_anak_linked'");
$data_anak = mysqli_fetch_assoc($query_anak);

if (!$data_anak) {
    echo "<script>alert('Data anak belum ditautkan!'); window.location='dashboard_anak.php';</script>";
    exit;
}

// LOGIKA REAL-TIME USIA (SESUAI DATABASE)
$tgl_lahir = new DateTime($data_anak['tanggal_lahir']);
$sekarang = new DateTime();
$usia_bulan = ($sekarang->diff($tgl_lahir)->y * 12) + $sekarang->diff($tgl_lahir)->m;

$nama_panggilan = explode(' ', $data_anak['nama_lengkap'])[0];

// Logika Konten Berdasarkan Usia (Murni Bawaan Asli Anda)
if ($usia_bulan < 6) {
    $fase = "ASI Eksklusif";
    $pesan_fase = "Hanya ASI hingga usia 6 bulan.";
} elseif ($usia_bulan >= 6 && $usia_bulan < 9) {
    $fase = "MPASI Tekstur Lumat";
    $pesan_fase = "Mulai perkenalan makanan halus.";
} elseif ($usia_bulan >= 9 && $usia_bulan < 12) {
    $fase = "MPASI Tekstur Cincang";
    $pesan_fase = "Naikkan tekstur perlahan.";
} else {
    $fase = "Makanan Keluarga";
    $pesan_fase = "Sudah bisa makan menu keluarga.";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrisi Harian - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }

        .bento-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(15, 23, 42, 0.04);
        }

        .bento-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.04);
        }
    </style>
</head>

<body class="flex min-h-screen text-slate-800">

    <aside class="w-72 bg-white border-r border-slate-100 flex flex-col fixed h-full z-20 shadow-sm">
        <div class="p-8 flex items-center gap-3">
            <div class="bg-indigo-600 p-2.5 rounded-xl text-white shadow-lg shadow-indigo-100">
                <i class="fas fa-shield-heart text-xl"></i>
            </div>
            <span class="font-extrabold text-xl tracking-tight text-slate-800">StuntGuard</span>
        </div>

        <nav class="flex-1 px-6 space-y-1.5">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-4 mb-4">Menu Utama</p>
            <a href="dashboard_anak.php"
                class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                <i class="fas fa-th-large text-lg"></i> Dashboard
            </a>
            <a href="profil_anak.php"
                class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                <i class="fas fa-child text-lg"></i> Profil Anak
            </a>
            <a href="jadwal_imunisasi.php"
                class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                <i class="fas fa-calendar-check text-lg"></i> Jadwal Imunisasi
            </a>
            <a href="nutrisi.php"
                class="flex items-center gap-4 px-4 py-4 bg-indigo-50/60 text-indigo-600 rounded-2xl font-extrabold text-sm transition-all">
                <i class="fas fa-apple-whole text-lg"></i> Nutrisi Harian
            </a>
            <div class="pt-6 mt-4 border-t border-slate-50">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-4 mb-4">Pengaturan Akun
                </p>
                <a href="profil_akun.php"
                    class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                    <i class="fas fa-user-gear text-lg"></i> Profil Akun
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-slate-50">
            <a href="../logout.php"
                class="flex items-center gap-4 px-4 py-4 text-rose-500 hover:bg-rose-50 rounded-2xl font-extrabold text-sm transition-all">
                <i class="fas fa-sign-out-alt text-lg"></i> Keluar
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-72 p-12">
        <header class="flex justify-between items-end mb-12">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold mb-1.5 text-slate-900 tracking-tight">Halo Bunda! 👋</h1>
                <h2 class="text-2xl sm:text-3xl font-black text-indigo-600 tracking-tight">Nutrisi
                    <?= htmlspecialchars($nama_panggilan); ?></h2>
                <p class="text-slate-400 font-medium text-sm mt-1">Panduan gizi cerdas untuk usia <b
                        class="text-slate-700 font-extrabold"><?= $usia_bulan; ?> Bulan</b>.</p>
            </div>
            <div class="text-right hidden lg:block">
                <span
                    class="inline-block bg-emerald-50 text-emerald-600 border border-emerald-100 px-4 py-2 rounded-full text-[10px] font-extrabold uppercase tracking-widest">●
                    Database Terhubung</span>
                <p class="text-xs font-bold text-slate-400 italic mt-2.5">"Gizi tepat, anak sehat!"</p>
            </div>
        </header>

        <div class="grid grid-cols-12 gap-8">

            <div
                class="col-span-12 lg:col-span-8 bg-slate-900 rounded-[40px] p-12 text-white relative overflow-hidden shadow-xl">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-8">
                        <span
                            class="bg-indigo-600 px-4 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-wider">Target:
                            <?= $usia_bulan; ?> Bulan</span>
                        <span
                            class="bg-white/10 px-4 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-sm border border-white/5">Fase:
                            <?= $fase; ?></span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black mb-3 leading-tight tracking-tight">Porsi Ideal <br>Usia
                        <?= $usia_bulan; ?> Bulan 🍽️</h2>
                    <p class="text-indigo-200/90 text-sm font-bold italic mb-10"><?= $pesan_fase; ?></p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div onclick="openNutriModal('Protein Hewani', '🥚', 'Sangat disarankan memberikan protein hewani seperti telur, ayam, ikan, atau daging sapi setiap hari untuk merangsang pertumbuhan linear tulang si kecil dan mencegah stunting.', '2 Sendok Makan Penuh')"
                            class="bg-white/10 p-6 rounded-[24px] backdrop-blur-md border border-white/10 text-center cursor-pointer hover:bg-white/[0.15] transition-all group">
                            <p class="text-3xl mb-2 group-hover:scale-110 transition-transform">🥚</p>
                            <p class="font-extrabold text-sm tracking-tight">Protein</p>
                            <p class="text-[10px] text-indigo-200/60 font-medium mt-0.5">2 Sdm Penuh</p>
                        </div>
                        <div onclick="openNutriModal('Karbohidrat Dasar', '🍚', 'Sebagai sumber energi utama untuk menunjang aktivitas fisik dan perkembangan motorik kasar si kecil. Pastikan dimasak dengan kelembutan yang sesuai.', '3 - 4 Kali Sehari')"
                            class="bg-white/10 p-6 rounded-[24px] backdrop-blur-md border border-white/10 text-center cursor-pointer hover:bg-white/[0.15] transition-all group">
                            <p class="text-3xl mb-2 group-hover:scale-110 transition-transform">🍚</p>
                            <p class="font-extrabold text-sm tracking-tight">Karbo</p>
                            <p class="text-[10px] text-indigo-200/60 font-medium mt-0.5">3-4 Kali Sehari</p>
                        </div>
                        <div onclick="openNutriModal('Lemak Tambahan', '🥥', 'Lemak tambahan seperti santan murni, mentega, atau minyak kelapa sangat penting sebagai booster kalori demi pencapaian berat badan anak yang optimal.', 'Santan murni / Minyak sehat')"
                            class="bg-white/10 p-6 rounded-[24px] backdrop-blur-md border border-white/10 text-center cursor-pointer hover:bg-white/[0.15] transition-all group">
                            <p class="text-3xl mb-2 group-hover:scale-110 transition-transform">🥥</p>
                            <p class="font-extrabold text-sm tracking-tight">Lemak</p>
                            <p class="text-[10px] text-indigo-200/60 font-medium mt-0.5">Santan/Minyak</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 flex flex-col gap-6">
                <div class="bento-card bg-orange-50/70 p-8 rounded-[36px] border border-orange-100/70 flex-1">
                    <div
                        class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-orange-500 shadow-sm mb-6 border border-orange-100/30">
                        <i class="fas fa-lightbulb"></i></div>
                    <h3 class="text-lg font-black text-orange-900 tracking-tight mb-2">Tips Usia <?= $usia_bulan; ?> Bln
                    </h3>
                    <p class="text-xs text-orange-700/80 font-bold leading-relaxed">Berikan protein hewani setiap hari
                        secara bervariasi untuk membantu mencegah gejala stunting secara optimal.</p>
                </div>
                <div
                    class="bg-indigo-600 p-8 rounded-[36px] text-white flex-1 relative overflow-hidden shadow-lg shadow-indigo-50">
                    <h3 class="text-lg font-extrabold mb-1.5 tracking-tight">Lapor Gizi 📈</h3>
                    <p class="text-xs text-indigo-100/80 leading-relaxed font-bold">Berdasarkan sinkronisasi berkala
                        database Posyandu, <?= htmlspecialchars($nama_panggilan); ?> berada di dalam fase usia
                        pertumbuhan emas.</p>
                    <div class="absolute -right-4 -bottom-4 text-8xl opacity-10"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>

            <div class="col-span-12 mt-6">
                <h3 class="text-xl font-black text-slate-900 mb-6 tracking-tight">Timeline Makan Si Kecil ⏰</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

                    <div onclick="openNutriModal('Sarapan Pagi', '🌅', 'Waktu sarapan pagi ideal untuk mengisi energi awal anak. Berikan porsi menu utama yang kaya nutrisi hewani dan mudah dicerna.', 'Jam 08:00 WIB')"
                        class="bento-card bg-white p-7 rounded-[28px] border border-slate-100 cursor-pointer group">
                        <div
                            class="w-10 h-10 bg-amber-50 text-amber-500 border border-amber-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-amber-500 group-hover:text-white transition-all">
                            <i class="fas fa-sun"></i></div>
                        <p class="text-amber-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">08:00</p>
                        <p
                            class="text-base font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">
                            Sarapan Pagi</p>
                    </div>

                    <div onclick="openNutriModal('Makan Siang', '☀️', 'Menu utama siang hari harus seimbang dan memenuhi standar gizi makro (karbohidrat, lemak, protein) untuk mengiringi masa aktifnya.', 'Jam 12:00 WIB')"
                        class="bento-card bg-white p-7 rounded-[28px] border border-slate-100 cursor-pointer group">
                        <div
                            class="w-10 h-10 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                            <i class="fas fa-utensils"></i></div>
                        <p class="text-indigo-600 text-[10px] font-extrabold uppercase tracking-widest mb-1">12:00</p>
                        <p
                            class="text-base font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">
                            Makan Siang</p>
                    </div>

                    <div onclick="openNutriModal('Makanan Selingan', '🍪', 'Makanan selingan atau camilan sehat sore bisa berupa buah lumat, biskuit bayi, atau bubur buah murni untuk menambah kalori harian.', 'Jam 15:00 WIB')"
                        class="bento-card bg-white p-7 rounded-[28px] border border-slate-100 cursor-pointer group">
                        <div
                            class="w-10 h-10 bg-rose-50 text-rose-500 border border-rose-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-rose-500 group-hover:text-white transition-all">
                            <i class="fas fa-cookie-bite"></i></div>
                        <p class="text-rose-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">15:00</p>
                        <p
                            class="text-base font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">
                            Selingan</p>
                    </div>

                    <div onclick="openNutriModal('Makan Malam', '🌙', 'Makan malam sebaiknya diberikan tidak terlalu dekat dengan waktu tidur malam anak, agar penyerapan nutrisi berjalan maksimal tanpa mengganggu sistem pencernaan.', 'Jam 18:00 WIB')"
                        class="bento-card bg-white p-7 rounded-[28px] border border-slate-100 cursor-pointer group">
                        <div
                            class="w-10 h-10 bg-emerald-50 text-emerald-500 border border-emerald-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="fas fa-moon"></i></div>
                        <p class="text-emerald-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">18:00</p>
                        <p
                            class="text-base font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">
                            Makan Malam</p>
                    </div>

                </div>
            </div>
        </div>

        <footer class="mt-16 p-8 bg-slate-50/80 rounded-[28px] border border-slate-100/70 flex items-center gap-5">
            <div
                class="w-12 h-12 bg-white border border-slate-100 rounded-xl shadow-sm flex items-center justify-center text-indigo-600 flex-shrink-0">
                <i class="fas fa-info-circle text-lg"></i></div>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">
                <span class="text-slate-800 font-extrabold">Akurasi Sinkronisasi:</span> Perhitungan usia otomatis
                berdasarkan tanggal lahir database posyandu:
                <?= date('d F Y', strtotime($data_anak['tanggal_lahir'])); ?>
            </p>
        </footer>
    </main>

    <div id="nutriModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div
            class="bg-white rounded-[32px] w-full max-w-md p-10 border border-slate-100 shadow-2xl m-4 transform scale-95 transition-transform duration-300 relative">

            <button onclick="closeNutriModal()"
                class="absolute top-6 right-6 w-10 h-10 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-full flex items-center justify-center transition-all">
                <i class="fas fa-times"></i>
            </button>

            <div class="text-center mt-3">
                <div id="modalEmoji" class="text-5xl mb-4">🍽️</div>
                <h3 id="modalTitle" class="text-xl font-black text-slate-900 tracking-tight">Detail Nutrisi</h3>
                <span id="modalTargetLabel"
                    class="inline-block mt-2 bg-indigo-50 text-indigo-600 border border-indigo-100 px-4 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest"></span>
            </div>

            <hr class="border-slate-100 my-6">

            <div class="space-y-4">
                <div>
                    <label
                        class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Panduan
                        Gizi & Edukasi</label>
                    <p id="modalDesc"
                        class="text-xs sm:text-sm text-slate-600 font-bold leading-relaxed text-justify bg-slate-50/80 p-4 border border-slate-100 rounded-2xl">
                    </p>
                </div>
            </div>

            <div class="mt-8">
                <button onclick="closeNutriModal()"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs uppercase tracking-widest py-4 rounded-xl shadow-md transition-all">
                    Selesai & Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        function openNutriModal(title, emoji, desc, target) {
            const modal = document.getElementById('nutriModal');

            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalEmoji').innerText = emoji;
            document.getElementById('modalDesc').innerText = desc;
            document.getElementById('modalTargetLabel').innerText = "Anjuran: " + target;

            // Animasi Fade In Smooth
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function closeNutriModal() {
            const modal = document.getElementById('nutriModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Tutup Modal ketika area luar modal diklik luar
        window.onclick = function (event) {
            const modal = document.getElementById('nutriModal');
            if (event.target == modal) {
                closeNutriModal();
            }
        }
    </script>
</body>

</html>