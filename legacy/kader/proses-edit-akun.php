<?php
session_start();
include '../koneksi.php';

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $password = md5($_POST['password']);

    $query = "UPDATE users SET password = '$password' WHERE id = '$id'";

    // Siapkan variabel untuk penanganan Pop-up Modal Kustom
    $update_status = false;

    if (mysqli_query($koneksi, $query)) {
        $update_status = "sukses";
    } else {
        $update_status = "gagal";
    }

    // Render Antarmuka Pop-up Sinkronisasi Denyut Desain StuntGuard
    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Otorisasi Keamanan - StuntGuard</title>
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

            <?php if ($update_status === "sukses"): ?>
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i class="fas fa-shield-check"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Sandi Diperbarui</h3>
                <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
                    Keamanan akun berhasil diperketat. Password baru telah dienkripsi dan disimpan ke pangkalan data StuntGuard.
                </p>
                <button onclick="redirectUser('update_berhasil')"
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-slate-900/10">
                    Selesai & Kembali
                </button>

            <?php else: ?>
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Memperbarui</h3>
                <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-4">
                    Terjadi kesalahan teknis pada sistem enkripsi atau koneksi database posyandu gagal terhubung.
                </p>
                <button onclick="redirectUser('update_gagal')"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
                    Coba Lagi
                </button>
            <?php endif; ?>

        </div>

        <script>
            function redirectUser(pesanString) {
                // Mengarahkan kembali ke kelola akun dengan menyertakan parameter pesan asli bawaan codingan Anda
                window.location.href = 'kelola-akun.php?pesan=' + pesanString;
            }

            // Fallback: Otomatis redirect halus dalam 3.5 detik jika kader tidak menekan tombol
            setTimeout(() => {
                redirectUser('<?= $update_status === "sukses" ? "update_berhasil" : "update_gagal"; ?>');
            }, 3500);
        </script>
    </body>

    </html>
    <?php
    exit;
}
?>