<?php
session_start();
include '../koneksi.php';

// 1. PROTEKSI HALAMAN (Murni Bawaan Asli Anda)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] !== 'orang_tua') {
    header("Location: ../login.php");
    exit;
}

// 2. AMBIL DATA USER
$id_user = $_SESSION['id'];
$nama_user = $_SESSION['nama'];

// Ambil id_anak dari tabel users
$query_user = mysqli_query($koneksi, "SELECT id_anak FROM users WHERE id = '$id_user'");
$data_user = mysqli_fetch_assoc($query_user);
$id_anak_linked = $data_user['id_anak'] ?? 0;

// 3. AMBIL DETAIL ANAK
$query_anak = mysqli_query($koneksi, "SELECT * FROM anak WHERE id = '$id_anak_linked'");
$data_anak = mysqli_fetch_assoc($query_anak);

if ($data_anak) {
    $nama_anak = $data_anak['nama_lengkap'];
    $id_anak = $data_anak['id'];
    $jk = ($data_anak['jenis_kelamin'] == 'L') ? 'Laki-Laki' : 'Perempuan';
} else {
    $nama_anak = "Data Anak Tidak Ditemukan";
    $id_anak = 0;
    $jk = "-";
}

// 4. AMBIL DATA PENGUKURAN TERAKHIR
$berat_terakhir = "0.00";
$tinggi_terakhir = "0.00";
$status_stunting = "Terpantau";

if ($id_anak != 0) {
    $query_ukur = mysqli_query($koneksi, "SELECT * FROM pengukuran WHERE anak_id = '$id_anak' ORDER BY created_at DESC LIMIT 1");
    if ($query_ukur && mysqli_num_rows($query_ukur) > 0) {
        $ukur = mysqli_fetch_assoc($query_ukur);
        $berat_terakhir = $ukur['berat_badan'];
        $tinggi_terakhir = $ukur['tinggi_badan'];
        $status_stunting = $ukur['status_stunting'] ?? "Normal";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>

<body class="flex min-h-screen text-slate-800">

    <aside class="w-72 bg-white border-r border-slate-100 flex flex-col fixed h-full shadow-sm z-20">
        <div class="p-8 flex items-center gap-3">
            <div class="bg-indigo-600 p-2.5 rounded-xl text-white shadow-lg shadow-indigo-100">
                <i class="fas fa-shield-heart text-xl"></i>
            </div>
            <span class="font-extrabold text-xl text-slate-800 tracking-tight">StuntGuard</span>
        </div>

        <nav class="flex-1 px-6 space-y-1.5">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-4 mb-4">Menu Utama</p>

            <a href="dashboard_anak.php"
                class="flex items-center gap-4 px-4 py-4 bg-indigo-50/60 text-indigo-600 rounded-2xl font-extrabold text-sm transition-all">
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
                class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
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

        <header class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Selamat Datang,
                    <?= htmlspecialchars($nama_user); ?>!</h1>
                <p class="text-slate-400 font-medium text-sm mt-1">Monitoring gizi si kecil secara berkala.</p>
            </div>
            <div class="flex items-center gap-4 bg-white p-2.5 pr-6 rounded-full border border-slate-100 shadow-sm">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_user); ?>&background=6366F1&color=fff"
                    class="w-10 h-10 rounded-full shadow-inner">
                <div>
                    <p class="text-xs font-extrabold text-slate-800 leading-tight"><?= htmlspecialchars($nama_user); ?>
                    </p>
                    <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider mt-0.5">Orang Tua</p>
                </div>
            </div>
        </header>

        <div
            class="bg-white p-10 rounded-[40px] border border-slate-100/60 shadow-sm mb-12 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="flex items-center gap-8">
                <div class="relative">
                    <div
                        class="w-24 h-24 bg-indigo-50/60 rounded-[32px] flex items-center justify-center text-indigo-600 text-4xl shadow-inner">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div
                        class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-8 h-8 rounded-xl flex items-center justify-center border-4 border-white shadow-md">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-3 mb-2.5">
                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                            <?= htmlspecialchars($nama_anak); ?></h2>
                        <span
                            class="bg-emerald-50 text-emerald-600 text-[10px] font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider border border-emerald-100">
                            Status: <?= htmlspecialchars($status_stunting); ?>
                        </span>
                    </div>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-venus-mars text-indigo-400 text-sm"></i> <?= $jk; ?>
                        <span class="text-slate-200 font-normal">|</span>
                        <i class="fas fa-calendar-alt text-indigo-400 text-sm"></i> Data Realtime Posyandu
                    </p>
                </div>
            </div>

            <div class="flex gap-6">
                <div
                    class="bg-slate-50/60 px-10 py-6 rounded-[28px] border border-slate-100/80 text-center min-w-[150px]">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Berat Terakhir
                    </p>
                    <p class="text-3xl font-extrabold text-slate-800 tracking-tight"><?= $berat_terakhir; ?> <span
                            class="text-xs font-bold text-slate-400 uppercase">kg</span></p>
                </div>
                <div
                    class="bg-slate-50/60 px-10 py-6 rounded-[28px] border border-slate-100/80 text-center min-w-[150px]">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tinggi
                        Terakhir</p>
                    <p class="text-3xl font-extrabold text-slate-800 tracking-tight"><?= $tinggi_terakhir; ?> <span
                            class="text-xs font-bold text-slate-400 uppercase">cm</span></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <div
                class="lg:col-span-2 bg-white p-10 rounded-[40px] border border-slate-100/60 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="font-extrabold text-slate-800 text-base tracking-tight">Grafik Pertumbuhan Si Kecil</h3>
                    <div
                        class="px-4 py-1.5 bg-indigo-600 text-white text-[10px] font-extrabold rounded-xl shadow-md shadow-indigo-100 uppercase tracking-wider">
                        BB (Kilogram)</div>
                </div>
                <div class="relative w-full h-[240px]">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <div class="space-y-8">
                <div
                    class="bg-gradient-to-br from-indigo-600 to-indigo-700 p-10 rounded-[40px] shadow-xl shadow-indigo-100 text-white relative overflow-hidden">
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                    <h4 class="font-extrabold text-lg mb-2.5 tracking-tight">Tips Hari Ini ✨</h4>
                    <p class="text-indigo-100 text-xs font-medium leading-relaxed mb-6">Penuhi kebutuhan MPASI dengan
                        gizi seimbang untuk optimalkan tumbuh kembang si kecil.</p>
                    <a href="nutrisi.php"
                        class="inline-block bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl text-xs font-bold backdrop-blur-md transition-all tracking-wide">
                        Cek Menu Makan
                    </a>
                </div>

                <div class="bg-white p-10 rounded-[40px] border border-slate-100/60 shadow-sm">
                    <h4 class="font-extrabold text-slate-800 mb-6 text-base tracking-tight">Kunjungan Berikutnya</h4>
                    <div class="flex items-center gap-5 p-5 bg-amber-50/60 rounded-[24px] border border-amber-100/60">
                        <div
                            class="bg-amber-500 text-white w-12 h-12 rounded-xl flex items-center justify-center shadow-md shadow-amber-100 text-lg">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-amber-600 uppercase tracking-widest mb-0.5">
                                Posyandu</p>
                            <p class="text-sm font-extrabold text-slate-800">15 Mei 2026</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        const ctx = document.getElementById('growthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
                datasets: [{
                    data: [8.0, 8.2, 8.5, 8.8, <?= floatval($berat_terakhir); ?>],
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.04)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 4,
                    pointRadius: 5,
                    pointHitRadius: 10,
                    pointBackgroundColor: '#4361ee',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        grid: { color: '#F1F5F9', drawBorder: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' }, color: '#94A3B8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' }, color: '#94A3B8' }
                    }
                }
            }
        });
    </script>
</body>

</html>