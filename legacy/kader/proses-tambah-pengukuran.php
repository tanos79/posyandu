<?php
session_start();
include '../koneksi.php';

// Supaya error MySQL muncul jelas sesuai bawaan codingan Anda
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kader') {
    header("location:../login.php");
    exit;
}

if (isset($_POST['simpan_ukur'])) {
    // Inisialisasi variabel status pop-up kustom
    $insert_status = false;
    $error_message = "";

    try {
        $kader_id = $_SESSION['id'];
        $anak_id = mysqli_real_escape_string($koneksi, $_POST['anak_id']);
        $tgl_pengukuran = mysqli_real_escape_string($koneksi, $_POST['tgl_pengukuran']);
        $berat_badan = mysqli_real_escape_string($koneksi, $_POST['berat_badan']);
        $tinggi_badan = mysqli_real_escape_string($koneksi, $_POST['tinggi_badan']);
        $lingkar_kepala = mysqli_real_escape_string($koneksi, $_POST['lingkar_kepala']);
        $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);
        $vitamin_a = mysqli_real_escape_string($koneksi, $_POST['vitamin_a']);
        $imunisasi = mysqli_real_escape_string($koneksi, $_POST['imunisasi']);

        // Logika Penentuan Status Stunting (Sederhana) bawaan codingan Anda
        if ($tinggi_badan < 60) {
            $status_stunting = 'stunted';
        } else {
            $status_stunting = 'normal';
        }

        // Query Insert asli
        $query = "INSERT INTO pengukuran (anak_id, kader_id, tgl_pengukuran, berat_badan, tinggi_badan, lingkar_kepala, catatan, status_stunting, vitamin_a, imunisasi) 
                  VALUES ('$anak_id', '$kader_id', '$tgl_pengukuran', '$berat_badan', '$tinggi_badan', '$lingkar_kepala', '$catatan', '$status_stunting', '$vitamin_a', '$imunisasi')";

        if (mysqli_query($koneksi, $query)) {
            $insert_status = "sukses";
        }
    } catch (Exception $e) {
        $insert_status = "gagal";
        $error_message = $e->getMessage();
    }

    // Render Antarmuka Pop-up Sinkronisasi Denyut Desain StuntGuard
    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Otorisasi Pencatatan Ukur - StuntGuard</title>
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
                        <i class="fas fa-file-medical-check"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Pengukuran Disimpan</h3>
                <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
                    Metrik tumbuh kembang balita berhasil dihitung dan diarsipkan ke dalam sistem deteksi dini StuntGuard.
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
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Menyimpan Data</h3>
                <p class="text-slate-400 text-xs font-medium mb-4 leading-relaxed px-4">
                    Pangkalan data menolak kompilasi berkas pengukuran karena pengecualian sistem:
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
                // Mengalihkan halaman kembali ke data-pengukuran.php persis rute rancangan awal Anda
                window.location.href = 'data-pengukuran.php';
            }

            // Fallback: Jika sukses, otomatis memicu rute pengalihan dalam 3 detik tanpa klik tombol
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
    header("location:data-pengukuran.php");
    exit;
}
?>