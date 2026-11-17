<?php
session_start();
include '../koneksi.php';

if (isset($_POST['simpan_jadwal'])) {
    $id_anak = mysqli_real_escape_string($koneksi, $_POST['id_anak']);
    $jenis_vaksin = mysqli_real_escape_string($koneksi, $_POST['jenis_vaksin']);
    $tgl_jadwal = mysqli_real_escape_string($koneksi, $_POST['tgl_jadwal']);
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);

    // Status default 'Belum' sesuai struktur database asli Anda
    $status = "Belum";

    $query = "INSERT INTO imunisasi (id_anak, jenis_vaksin, tgl_jadwal, status) 
              VALUES ('$id_anak', '$jenis_vaksin', '$tgl_jadwal', '$status')";

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
        <title>Otorisasi Jadwal Imunisasi - StuntGuard</title>
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
                        class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i class="fas fa-syringe"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Jadwal Disimpan</h3>
                <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
                    Agenda imunisasi <strong class="text-indigo-600"><?= htmlspecialchars($jenis_vaksin); ?></strong> berhasil
                    dijadwalkan dan disinkronkan ke sistem StuntGuard.
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
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Menyimpan</h3>
                <p class="text-slate-400 text-xs font-medium mb-4 leading-relaxed px-4">
                    Sistem menolak penyimpanan agenda imunisasi baru karena kendala teknis pangkalan data:
                </p>

                <div
                    class="bg-rose-50 border border-rose-100 p-4 rounded-xl font-mono text-[11px] text-rose-600 text-left mb-6 break-all whitespace-pre-line shadow-inner">
                    Error: <?= htmlspecialchars($error_message); ?>
                </div>

                <button onclick="redirectUser()"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
                    Kembali ke Daftar
                </button>
            <?php endif; ?>

        </div>

        <script>
            function redirectUser() {
                // Dialihkan menuju halaman data-pengukuran.php persis seperti rute awal rancangan Anda
                window.location.href = 'data-pengukuran.php';
            }

            // Fallback: Otomatis memicu rute pengalihan dalam 3 detik tanpa klik tombol jika proses sukses
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
}
?>