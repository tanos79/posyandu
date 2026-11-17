<?php
session_start();

// Mengecek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']);
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StuntGuard - Pantau Tumbuh Kembang Anak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-slate-900">

    <!-- Navbar -->
    <nav class="flex items-center justify-between px-10 py-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 p-2 rounded-lg text-white shadow-lg shadow-blue-200">
                <i class="fas fa-shield-heart text-xl"></i>
            </div>
            <span class="font-extrabold text-xl tracking-tight text-blue-900">StuntGuard</span>
        </div>

        <div class="hidden md:flex items-center gap-10 text-sm font-semibold text-slate-500">
            <a href="#" class="text-blue-600">Beranda</a>
            <a href="#deteksi" class="hover:text-blue-600 transition-colors">Deteksi Stunting</a>
            <a href="#imunisasi" class="hover:text-blue-600 transition-colors">Jadwal Imunisasi</a>
        </div>

        <div class="flex items-center gap-6">
            <?php if (!$is_logged_in): ?>
                <!-- Kondisi Belum Login: Tampilkan Tombol Masuk -->
                <a href="login.php" class="text-sm font-bold text-slate-700 hover:text-blue-600 transition-all flex items-center gap-2">
                    <i class="far fa-user"></i> Masuk
                </a>
            <?php else: ?>
                <!-- Kondisi Sudah Login: Tampilkan Role dan Nama -->
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-bold text-slate-400 uppercase leading-none">
                            <?= ($role == 'petugas') ? 'Petugas Kesehatan' : 'Orang Tua'; ?>
                        </p>
                        <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($username); ?></p>
                    </div>

                    <?php if ($role == 'petugas'): ?>
                        <a href="data_warga.php" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all">Kelola Data</a>
                    <?php else: ?>
                        <a href="dashboard_anak.php" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Dashboard</a>
                    <?php endif; ?>

                    <a href="logout.php" class="text-slate-400 hover:text-red-500 transition-colors" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-10 py-20 grid md:grid-cols-2 gap-16 items-center">
        <div>
            <span class="bg-blue-50 text-blue-600 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest mb-6 inline-block">
                ● PLATFORM KESEHATAN ANAK #1
            </span>
            <h1 class="text-6xl font-extrabold leading-[1.1] mb-6 text-slate-900">
                Pantau Tumbuh <br> Kembang <br> <span class="text-blue-600">Anak Tercinta</span>
            </h1>
            <p class="text-slate-500 text-lg leading-relaxed mb-10 max-w-md font-medium">
                Aplikasi pintar untuk deteksi dini stunting, manajemen jadwal imunisasi otomatis, dan panduan nutrisi harian.
            </p>

            <?php if (!$is_logged_in): ?>
                <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-start gap-3 max-w-sm">
                    <i class="fas fa-info-circle text-amber-500 mt-1"></i>
                    <p class="text-xs text-amber-800 font-medium leading-relaxed">Silahkan <b>Masuk</b> untuk mulai mencatat dan melihat grafik perkembangan tinggi badan anak Anda.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Side Mockup -->
        <div class="relative">
            <div class="bg-white p-8 rounded-[40px] shadow-2xl border border-slate-50 relative z-10">
                <div class="flex items-center gap-4 mb-8">
                    <img src="https://ui-avatars.com/api/?name=Anak+Sehat&background=4461F2&color=fff" class="w-14 h-14 rounded-full" alt="Avatar">
                    <div>
                        <h4 class="font-extrabold text-slate-800">Contoh Data Anak</h4>
                        <p class="text-slate-400 text-xs font-semibold">12 Bulan • Laki-laki</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50/30 p-5 rounded-2xl border border-blue-50 text-center">
                        <p class="text-slate-400 text-[10px] font-bold uppercase">Berat</p>
                        <h5 class="text-xl font-bold text-slate-800">9.5 kg</h5>
                    </div>
                    <div class="bg-blue-50/30 p-5 rounded-2xl border border-blue-50 text-center">
                        <p class="text-slate-400 text-[10px] font-bold uppercase">Tinggi</p>
                        <h5 class="text-xl font-bold text-slate-800">75 cm</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-10 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-10 text-center">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">© 2026 StuntGuard. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>