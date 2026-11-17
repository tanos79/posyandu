<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "stuntguard_db");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$login_error = false; // Variabel untuk memicu modal

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] === 'orang_tua') {
            header("Location: dashboard_anak.php");
        } else if ($row['role'] === 'petugas' || $row['role'] === 'kader') {
            header("Location: data_warga.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $login_error = true; // Memicu tampilnya modal
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-50">

    <div id="errorModal"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 <?php echo $login_error ? '' : 'hidden'; ?>">
        <div class="bg-white rounded-[32px] w-full max-w-sm p-8 shadow-2xl text-center">
            <div class="w-16 h-16 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-times text-2xl"></i>
            </div>
            <h2 class="text-lg font-black text-slate-800 mb-2">Login Gagal!</h2>
            <p class="text-slate-500 text-sm font-medium mb-6">Username atau Password tidak sesuai dengan data
                terenkripsi.</p>
            <button onclick="document.getElementById('errorModal').classList.add('hidden')"
                class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition">
                Coba Lagi
            </button>
        </div>
    </div>

    <form action="" method="POST"
        class="max-w-sm mx-auto mt-20 bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <h1 class="text-2xl font-black mb-6">Login</h1>
        <input type="text" name="username" placeholder="Username"
            class="w-full p-3 mb-4 bg-slate-50 rounded-xl outline-none" required>
        <input type="password" name="password" placeholder="Password"
            class="w-full p-3 mb-6 bg-slate-50 rounded-xl outline-none" required>
        <button type="submit" name="login"
            class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl">Masuk</button>
    </form>

</body>

</html>