<?php
session_start();
include '../koneksi.php';

if (isset($_POST['username'])) {
    $id_user = $_SESSION['id'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // 1. Ambil data foto lama dari database untuk pembersihan bawaan asli kode Anda
    $query_lama = mysqli_query($koneksi, "SELECT foto_profil FROM users WHERE id = '$id_user'");
    $data_lama = mysqli_fetch_assoc($query_lama);
    $foto_lama = $data_lama['foto_profil'];

    // Variable untuk menyimpan nama foto yang akan masuk ke database
    $foto_final = $foto_lama;

    // 2. Proses upload otomatis jika ada file yang dipilih
    if (!empty($_FILES['foto']['name'])) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];

        // Ambil ekstensi file (jpg/png)
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        // Buat nama unik otomatis agar tidak bentrok (contoh: 65a123.jpg)
        $nama_baru = uniqid() . '.' . $ekstensi;
        $tujuan = '../assets/img/' . $nama_baru;

        // Pindahkan file dari folder sementara (tmp) ke folder assets secara otomatis
        if (move_uploaded_file($tmp_name, $tujuan)) {
            // Jika berhasil pindah, hapus foto lama dari folder (supaya penyimpanan tidak penuh)
            if ($foto_lama != 'default.png' && file_exists('../assets/img/' . $foto_lama)) {
                unlink('../assets/img/' . $foto_lama);
            }
            $foto_final = $nama_baru;
        }
    }

    // 3. Update Database secara otomatis sesuai kondisi isian password Anda
    if (!empty($password)) {
        $pass_hash = md5($password);
        $sql = "UPDATE users SET username='$username', password='$pass_hash', foto_profil='$foto_final' WHERE id='$id_user'";
    } else {
        $sql = "UPDATE users SET username='$username', foto_profil='$foto_final' WHERE id='$id_user'";
    }

    // Inisialisasi variabel status pop-up kustom
    $update_status = false;
    $error_message = "";

    if (mysqli_query($koneksi, $sql)) {
        $update_status = "sukses";
    } else {
        $update_status = "gagal";
        $error_message = mysqli_error($koneksi);
    }

    // Render Antarmuka Pop-up Sinkronisasi Denyut Desain StuntGuard
    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pembaruan Profil - StuntGuard</title>
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
                        <i class="fas fa-user-gear"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Profil Diperbarui</h3>
                <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
                    Informasi akun identitas mandiri dan berkas media visual Anda berhasil disinkronkan ke server StuntGuard.
                </p>
                <button onclick="redirectUser('berhasil')"
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
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Mengubah</h3>
                <p class="text-slate-400 text-xs font-medium mb-4 leading-relaxed px-4">
                    Sistem gagal mengamankan pembaharuan profil digital karena pengecualian pangkalan data:
                </p>

                <div
                    class="bg-rose-50 border border-rose-100 p-4 rounded-xl font-mono text-[11px] text-rose-600 text-left mb-6 break-all whitespace-pre-line shadow-inner">
                    Gagal: <?= htmlspecialchars($error_message); ?>
                </div>

                <button onclick="window.location.href='profil.php'"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
                    Kembali & Perbaiki
                </button>
            <?php endif; ?>

        </div>

        <script>
            function redirectUser(pesanString) {
                // Dialihkan menuju halaman profil.php persis bawaan logika awal sistem Anda
                window.location.href = 'profil.php?pesan=' + pesanString;
            }

            // Fallback: Jika sukses, otomatis memicu redirect halus dalam 3 detik tanpa klik tombol
            <?php if ($update_status === "sukses"): ?>
                setTimeout(() => {
                    redirectUser('berhasil');
                }, 3000);
            <?php endif; ?>
        </script>
    </body>

    </html>
    <?php
    exit;
}
?>