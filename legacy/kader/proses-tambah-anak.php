<?php
session_start();
include '../koneksi.php';

if (isset($_POST['Daftarkan_Balita'])) {
    // Tangkap data dari form bawaan asli codingan Anda
    $nik_anak = mysqli_real_escape_string($koneksi, $_POST['nik_anak']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $berat_lahir = mysqli_real_escape_string($koneksi, $_POST['berat_lahir']);
    $tinggi_lahir = mysqli_real_escape_string($koneksi, $_POST['tinggi_lahir']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // Query INSERT asli tanpa mengubah susunan atau nama kolom tabel anak Anda
    $query = "INSERT INTO anak (nik_anak, nama_lengkap, tempat_lahir, jenis_kelamin, tanggal_lahir, berat_lahir, tinggi_lahir, nama_ibu, no_hp) 
              VALUES ('$nik_anak', '$nama_lengkap', '$tempat_lahir', '$jenis_kelamin', '$tanggal_lahir', '$berat_lahir', '$tinggi_lahir', '$nama_ibu', '$no_hp')";

    // Inisialisasi variabel status pop-up kustom
    $insert_status = false;
    $error_message = "";

    if (mysqli_query($koneksi, $query)) {
        $insert_status = "sukses";
    } else {
        $insert_status = "gagal";
        $error_message = mysqli_error($koneksi);
    }

    // Render Antarmuka Pop-up Sinkronisasi Denyut Desain StuntGuard
    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Otorisasi Data Balita - StuntGuard</title>
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

            <?php if ($insert_status === "sukses"): ?>
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i class="fas fa-baby-carriage"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Balita Terdaftar</h3>
                <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
                    Profil digital anak bernama <strong class="text-slate-600"><?= htmlspecialchars($nama_lengkap); ?></strong>
                    berhasil ditambahkan ke database posyandu StuntGuard.
                </p>
                <button onclick="redirectUser()"
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
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Mendaftarkan</h3>
                <p class="text-slate-400 text-xs font-medium mb-4 leading-relaxed px-4">
                    Sistem pangkalan data menolak enkripsi data berkas balita baru karena kendala teknis:
                </p>

                <div
                    class="bg-rose-50 border border-rose-100 p-4 rounded-xl font-mono text-[11px] text-rose-600 text-left mb-6 break-all whitespace-pre-line shadow-inner">
                    Gagal mendaftarkan data: <?= htmlspecialchars($error_message); ?>
                </div>

                <button onclick="redirectUser()"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
                    Kembali ke Daftar
                </button>
            <?php endif; ?>

        </div>

        <script>
            function redirectUser() {
                // Dialihkan menuju halaman data-anak.php persis seperti logika awal sistem Anda
                window.location.href = 'data-anak.php';
            }

            // Fallback: Jika sukses, sistem otomatis memicu redirect halus dalam 3 detik tanpa klik tombol
            <?php if ($insert_status === "sukses"): ?>
                setTimeout(() => {
                    redirectUser();
                }, 3000);
            <?php endif; ?>
        </script>
    </body>

    </html>
    <?php
    exit;
} else {
    // Proteksi akses langsung dari tautan URL luar tanpa kiriman form POST
    header("Location: tambah-anak.php");
    exit;
}
?>