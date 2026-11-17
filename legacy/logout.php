<?php
session_start();

// Cek apakah user sedang login agar popup hanya muncul jika user benar-benar keluar
$is_logged_in = isset($_SESSION['status']) && $_SESSION['status'] == "login";

// Hapus semua data sesi
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div id="logoutModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[32px] w-full max-w-sm p-8 shadow-2xl text-center border border-slate-100">
            <div class="w-16 h-16 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-sign-out-alt text-2xl"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 mb-2">Sampai Jumpa!</h2>
            <p class="text-slate-500 text-sm font-medium mb-6">Anda telah berhasil keluar dari akun StuntGuard Anda.</p>
            <div class="animate-pulse text-xs font-bold text-slate-400 uppercase tracking-widest">
                Mengalihkan...
            </div>
        </div>
    </div>

    <script>
        // Redirect ke login setelah 2 detik
        setTimeout(function() {
            window.location.href = "login.php";
        }, 2000);
    </script>
</body>
</html>