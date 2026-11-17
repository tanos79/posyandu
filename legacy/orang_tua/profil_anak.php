<?php
session_start();
include '../koneksi.php';

// Proteksi Halaman (Murni Bawaan Asli Anda)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] !== 'orang_tua') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id'];

// Get data relasi id_anak dari tabel users
$query_user = mysqli_query($koneksi, "SELECT id_anak FROM users WHERE id = '$id_user'");
$data_user = mysqli_fetch_assoc($query_user);
$id_anak_linked = $data_user['id_anak'] ?? 0;

// Get data lengkap dari tabel anak
$query_anak = mysqli_query($koneksi, "SELECT * FROM anak WHERE id = '$id_anak_linked'");
$data_anak = mysqli_fetch_assoc($query_anak);

if (!$data_anak) {
    echo "<script>alert('Data anak belum ditautkan ke akun Anda!'); window.location='dashboard_anak.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Anak - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                <i class="fas fa-th-large text-lg"></i> Dashboard
            </a>

            <a href="profil_anak.php"
                class="flex items-center gap-4 px-4 py-4 bg-indigo-50/60 text-indigo-600 rounded-2xl font-extrabold text-sm transition-all">
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
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Profil Si Kecil</h1>
                <p class="text-slate-400 font-medium text-sm mt-1">Informasi identitas lengkap berdasarkan data
                    Posyandu.</p>
            </div>
            <a href="dashboard_anak.php"
                class="px-6 py-3 bg-white border border-slate-100 rounded-2xl text-slate-600 font-bold text-xs hover:bg-slate-50 hover:text-slate-800 transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </header>

        <div class="w-full bg-white rounded-[40px] border border-slate-100/60 shadow-sm overflow-hidden">

            <div
                class="bg-gradient-to-r from-indigo-600 to-indigo-500 p-12 text-white flex items-center gap-10 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div
                        class="w-36 h-36 bg-white/20 backdrop-blur-xl rounded-[38px] flex items-center justify-center text-6xl border border-white/30 shadow-2xl">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div
                        class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-9 h-9 rounded-xl flex items-center justify-center border-4 border-white shadow-lg">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black tracking-tight mb-3">
                        <?= htmlspecialchars($data_anak['nama_lengkap']); ?></h2>
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="bg-white/20 px-5 py-2 rounded-xl text-xs font-extrabold tracking-wider uppercase backdrop-blur-md">
                            NIK: <?= htmlspecialchars($data_anak['nik_anak']); ?>
                        </span>
                        <span
                            class="bg-emerald-400/20 px-5 py-2 rounded-xl text-xs font-extrabold tracking-wider uppercase backdrop-blur-md border border-emerald-400/30">
                            Status: Terverifikasi
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">

                <div class="space-y-10">
                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-indigo-50/60 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tempat
                                Lahir</p>
                            <p class="text-base font-extrabold text-slate-800 tracking-tight">
                                <?= htmlspecialchars($data_anak['tempat_lahir'] ?: '-'); ?></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-indigo-50/60 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <i class="fas fa-calendar-day text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">
                                Tanggal Lahir</p>
                            <p class="text-base font-extrabold text-slate-800 tracking-tight">
                                <?= date('d F Y', strtotime($data_anak['tanggal_lahir'])); ?></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-10">
                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <i class="fas fa-venus-mars text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Jenis
                                Kelamin</p>
                            <p class="text-base font-extrabold text-slate-800 tracking-tight">
                                <?= ($data_anak['jenis_kelamin'] == 'L') ? 'Laki-Laki' : 'Perempuan'; ?></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <i class="fas fa-person-breastfeeding text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Nama
                                Ibu Kandung</p>
                            <p class="text-base font-extrabold text-slate-800 tracking-tight">
                                <?= htmlspecialchars($data_anak['nama_ibu'] ?: '-'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-10">
                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <i class="fas fa-weight-scale text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Berat
                                Saat Lahir</p>
                            <p class="text-base font-extrabold text-slate-800 tracking-tight">
                                <?= $data_anak['berat_lahir']; ?> <span
                                    class="text-xs font-bold text-slate-400 uppercase">kg</span></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                            <i class="fas fa-ruler-vertical text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tinggi
                                Saat Lahir</p>
                            <p class="text-base font-extrabold text-slate-800 tracking-tight">
                                <?= $data_anak['tinggi_lahir']; ?> <span
                                    class="text-xs font-bold text-slate-400 uppercase">cm</span></p>
                        </div>
                    </div>
                </div>

            </div>

            <div
                class="mx-16 mb-16 p-8 bg-slate-50/60 rounded-[28px] border border-slate-100/80 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-5">
                    <div
                        class="w-14 h-14 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-orange-500 shadow-sm flex-shrink-0">
                        <i class="fas fa-phone-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Nomor HP
                            Terdaftar</p>
                        <p class="text-xl font-black text-slate-800 tracking-tight">
                            <?= htmlspecialchars($data_anak['no_hp'] ?: 'Belum Ada'); ?></p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-xs text-slate-400 font-medium italic">*Data ini disinkronkan langsung dari sistem
                        database pusat Posyandu.</p>
                </div>
            </div>

            <div class="px-16 py-8 bg-amber-50/60 border-t border-amber-100/80 flex items-center gap-4">
                <div
                    class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-amber-100">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                </div>
                <p class="text-xs text-amber-800 font-bold leading-relaxed">
                    Menemukan kesalahan data? <span class="text-slate-500 font-medium">Demi keamanan medis, perubahan
                        data identitas hanya dapat dilakukan melalui petugas kesehatan atau Kader Posyandu saat sesi
                        penimbangan.</span>
                </p>
            </div>
        </div>
    </main>

</body>

</html>