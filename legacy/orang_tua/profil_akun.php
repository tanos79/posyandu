<?php
session_start();
include '../koneksi.php';

// 1. PROTEKSI HALAMAN (Murni Bawaan Asli Anda)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] !== 'orang_tua') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id'];
$pesan = "";

// 2. PROSES UPDATE PROFIL & PASSWORD (Murni Bawaan Asli Anda)
if (isset($_POST['update_profil'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_baru = $_POST['password'];

    if (!empty($password_baru)) {
        // Jika password diisi, update semua termasuk password
        $query = "UPDATE users SET nama='$nama', username='$username', password='$password_baru' WHERE id='$id_user'";
    } else {
        // Jika password kosong, hanya update nama dan username
        $query = "UPDATE users SET nama='$nama', username='$username' WHERE id='$id_user'";
    }

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['nama'] = $nama; // Update nama di session agar header ikut berubah
        $pesan = "berhasil";
    } else {
        $pesan = "gagal";
    }
}

// 3. AMBIL DATA USER TERBARU (Murni Bawaan Asli Anda)
$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id_user'");
$user = mysqli_fetch_assoc($query_user);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Akun - StuntGuard</title>
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
                    class="flex items-center gap-4 px-4 py-4 bg-indigo-50/60 text-indigo-600 rounded-2xl font-extrabold text-sm transition-all">
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
        <header class="mb-12">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-1.5">Pengaturan Profil</h1>
            <p class="text-slate-400 font-medium text-sm">Kelola informasi kredensial akun dan keamanan password
                penunjang Anda.</p>
        </header>

        <div class="max-w-3xl bg-white rounded-[40px] border border-slate-100/70 shadow-sm overflow-hidden">
            <form action="" method="POST" class="p-12 space-y-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Nama
                            Lengkap</label>
                        <div class="relative group">
                            <i
                                class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>"
                                class="w-full pl-12 pr-6 py-4 bg-slate-50/60 border border-slate-100/80 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all font-bold text-slate-700 text-sm"
                                required>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Username</label>
                        <div class="relative group">
                            <i
                                class="fas fa-at absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                            <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>"
                                class="w-full pl-12 pr-6 py-4 bg-slate-50/60 border border-slate-100/80 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all font-bold text-slate-700 text-sm"
                                required>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Password
                        Baru (Kosongkan jika tidak ingin ganti)</label>
                    <div class="relative group">
                        <i
                            class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full pl-12 pr-6 py-4 bg-slate-50/60 border border-slate-100/80 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all font-bold text-slate-700 text-sm tracking-widest">
                    </div>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <p class="text-xs text-slate-400 font-bold max-w-sm leading-relaxed">
                        Pastikan data akun yang Anda masukkan sudah benar & valid sebelum menekan tombol simpan
                        perubahan.
                    </p>
                    <button type="submit" name="update_profil"
                        class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-extrabold text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all self-end sm:self-center">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </main>

    <div id="statusModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div
            class="bg-white rounded-[32px] w-full max-w-sm p-8 border border-slate-100 shadow-2xl m-4 transform scale-95 transition-transform duration-300 text-center relative">

            <div id="modalIconBg"
                class="w-20 h-20 rounded-[24px] flex items-center justify-center text-3xl mx-auto mb-6 shadow-md">
                <i id="modalIcon"></i>
            </div>

            <h2 id="modalTitle" class="text-xl font-black text-slate-900 tracking-tight mb-2"></h2>
            <p id="modalDesc" class="text-xs sm:text-sm text-slate-400 font-bold px-2 leading-relaxed mb-6"></p>

            <button onclick="closeStatusModal()"
                class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs uppercase tracking-widest rounded-xl shadow-md transition-all">
                Mengerti & Lanjutkan
            </button>
        </div>
    </div>

    <script>
        function openStatusModal(status) {
            const modal = document.getElementById('statusModal');
            const iconBg = document.getElementById('modalIconBg');
            const icon = document.getElementById('modalIcon');
            const title = document.getElementById('modalTitle');
            const desc = document.getElementById('modalDesc');

            // Reset Kelas Desain Statis Sebelumnya
            iconBg.className = "w-20 h-20 rounded-[24px] flex items-center justify-center text-3xl mx-auto mb-6 shadow-md ";
            icon.className = "fas ";

            if (status === 'berhasil') {
                iconBg.classList.add('bg-emerald-500', 'text-white', 'shadow-emerald-100');
                icon.classList.add('fa-check-circle');
                title.innerText = "Pembaruan Berhasil";
                desc.innerText = "Data kredensial profil akun StuntGuard Anda berhasil disimpan dan diperbarui ke sistem database.";
            } else if (status === 'gagal') {
                iconBg.classList.add('bg-rose-500', 'text-white', 'shadow-rose-100');
                icon.classList.add('fa-times-circle');
                title.innerText = "Pembaruan Gagal";
                desc.innerText = "Terjadi kendala sistem interkoneksi database internal. Gagal memperbarui data profil akun Anda.";
            }

            // Animasi Fade In Smooth
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('statusModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Trigger Popup Berdasarkan Variabel Pesan PHP
        <?php if ($pesan == "berhasil"): ?>
            window.addEventListener('DOMContentLoaded', () => openStatusModal('berhasil'));
        <?php elseif ($pesan == "gagal"): ?>
            window.addEventListener('DOMContentLoaded', () => openStatusModal('gagal'));
        <?php endif; ?>
    </script>
</body>

</html>