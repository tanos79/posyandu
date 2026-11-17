<?php
session_start();
include '../koneksi.php';

// 1. Proteksi Halaman: Pastikan hanya kader yang bisa menghapus
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kader') {
    header("location:../login.php");
    exit;
}

// Inisialisasi variabel status pop-up kustom
$delete_status = false;
$error_message = "";

// 2. Cek apakah ada ID yang dikirim melalui URL (GET)
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 3. Eksekusi query hapus
    $query = "DELETE FROM users WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        // Status sukses untuk memicu pop-up kustom
        $delete_status = "sukses";
    } else {
        // Ambil pesan error asli tanpa mengubah fungsi penanganan error database
        $delete_status = "gagal";
        $error_message = mysqli_error($koneksi);
    }

    // Render Antarmuka Pop-up Sinkronisasi Denyut Desain StuntGuard
    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Otorisasi Penghapusan - StuntGuard</title>
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

    <body class="bg-slate-900/40 backdrop-blur-sm min-h-screen flex items-center justify-center p-4">

        <div
            class="bg-white rounded-[40px] p-10 max-w-sm w-full shadow-2xl border border-slate-50 transform scale-100 transition-all duration-300 text-center">

            <?php if ($delete_status === "sukses"): ?>
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i class="fas fa-trash-check"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Akun Dihapus</h3>
                <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
                    Data pengguna berhasil dibersihkan dan dihapus secara permanen dari sistem pangkalan data StuntGuard.
                </p>
                <button onclick="redirectUser('hapus_berhasil')"
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-slate-900/10">
                    Selesai & Kembali
                </button>

            <?php else: ?>
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Menghapus</h3>
                <p class="text-slate-400 text-xs font-medium mb-4 leading-relaxed px-4">
                    Data akun terikat dengan data posyandu lainnya atau terdapat masalah sistem:
                </p>

                <div
                    class="bg-rose-50 border border-rose-100 p-4 rounded-xl font-mono text-[11px] text-rose-600 text-left mb-6 break-all whitespace-pre-line shadow-inner">
                    <?= htmlspecialchars($error_message); ?>
                </div>

                <button onclick="window.location.href='data-pengguna.php'"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
                    Kembali ke Daftar
                </button>
            <?php endif; ?>

        </div>

        <script>
            function redirectUser(pesanString) {
                // Mengarahkan kembali dengan menyertakan parameter pesan sukses bawaan logika Anda
                window.location.href = 'data-pengguna.php?pesan=' + pesanString;
            }

            // Fallback: Jika sukses, otomatis redirect halus dalam 3 detik tanpa klik tombol
            <?php if ($delete_status === "sukses"): ?>
                setTimeout(() => {
                    redirectUser('hapus_berhasil');
                }, 3000);
            <?php endif; ?>
        </script>
    </body>

    </html>
    <?php
    exit;
} else {
    // Jika mencoba akses file ini langsung tanpa ID
    header("location:data-pengguna.php");
    exit;
}
?>