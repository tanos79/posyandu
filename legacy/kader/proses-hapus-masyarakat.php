<?php
session_start();
include '../koneksi.php';

$id = $_GET['id'];

// Eksekusi query hapus bawaan asli codingan Anda (role orang_tua/masyarakat)
$hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id='$id' AND role='orang_tua'");

// Siapkan variabel penanda status untuk memicu Pop-up Modal Kustom
$delete_status = false;
if ($hapus) {
    $delete_status = "sukses";
} else {
    $delete_status = "gagal";
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
                    <i class="fas fa-user-minus"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Data Terhapus</h3>
            <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
                Otorisasi berhasil. Data masyarakat/orang tua telah dibersihkan secara permanen dari sistem StuntGuard.
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
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Menghapus</h3>
            <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-4">
                Terjadi kesalahan internal koneksi database posyandu saat memproses penghapusan data masyarakat.
            </p>
            <button onclick="redirectUser()"
                class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
                Kembali
            </button>
        <?php endif; ?>

    </div>

    <script>
        function redirectUser() {
            // Mengarahkan kembali ke kelola akun persis seperti target header() bawaan Anda
            window.location.href = 'kelola-akun.php';
        }

        // Fallback: Otomatis kembali secara halus dalam 3 detik tanpa harus klik tombol jika sukses
        <?php if ($delete_status === "sukses"): ?>
            setTimeout(() => {
                redirectUser();
            }, 3000);
        <?php endif; ?>
    </script>
</body>

</html>
<?php
exit;
?>