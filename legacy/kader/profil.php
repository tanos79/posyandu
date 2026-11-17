<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kader') {
    header("location:../login.php");
    exit;
}

// Ambil data terbaru dari database berdasarkan ID di session
$id_user = $_SESSION['id'];
$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id_user'");
$data = mysqli_fetch_assoc($query_user);

// Cek apakah data ditemukan untuk menghindari error Undefined Key
if (!$data) {
    die("Data user tidak ditemukan di database.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kader Professional | StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style_sidebar.css">
    <style>
        /* SINKRONISASI TYPOGRAPHY DENGAN HALAMAN UTAMA STUNTGUARD */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>

<body class="flex min-h-screen">
    <?php include 'sidebar.php'; ?>

    <div class="flex-1 ml-72 p-12">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Profil Kader</h1>
            <p class="text-slate-400 font-medium mt-2">Kelola informasi otorisasi dan identitas digital Anda.</p>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden relative">
                    <div class="h-36 bg-gradient-to-r from-indigo-500 to-blue-600"></div>
                    <div class="px-8 pb-10 text-center -mt-16">
                        <div class="relative inline-block">
                            <div
                                class="w-32 h-32 rounded-[35px] border-4 border-white shadow-xl overflow-hidden bg-slate-100 mx-auto">
                                <?php $foto = (!empty($data['foto_profil'])) ? $data['foto_profil'] : 'default.png'; ?>
                                <img id="avatarPreview" src="../assets/img/<?= $foto ?>"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                        <h2 class="mt-6 text-xl font-extrabold text-slate-800">
                            <?= htmlspecialchars($data['username']); ?></h2>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 mb-6">ID PETUGAS:
                            #<?= $data['id'] ?></p>

                        <div class="flex justify-center gap-6 border-t border-slate-50 pt-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-wider">Input Data</p>
                                <p class="text-xl font-extrabold text-blue-600 mt-0.5">0</p>
                            </div>
                            <div class="border-l border-slate-100 pl-6">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-wider">Status</p>
                                <p class="text-xl font-extrabold text-emerald-500 mt-0.5">Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-100">
                    <form id="profileForm" action="proses-update-profil.php" method="POST" enctype="multipart/form-data"
                        class="space-y-8" onsubmit="handleProfileSubmit(event)">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase ml-2 tracking-widest block mb-2">Username
                                    Login</label>
                                <input type="text" name="username" value="<?= htmlspecialchars($data['username']); ?>"
                                    class="w-full p-5 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 focus:bg-white transition-all font-bold text-slate-700 outline-none">
                            </div>
                            <div>
                                <label
                                    class="text-[11px] font-black text-slate-400 uppercase ml-2 tracking-widest block mb-2">Password
                                    Baru</label>
                                <input type="password" name="password" placeholder="Kosongkan jika tidak diganti"
                                    class="w-full p-5 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 focus:bg-white transition-all font-bold text-slate-700 outline-none placeholder:font-medium placeholder:text-slate-300">
                            </div>
                        </div>

                        <div>
                            <label
                                class="text-[11px] font-black text-slate-400 uppercase ml-2 tracking-widest block mb-2">Ganti
                                Foto Profil</label>
                            <div
                                class="mt-2 p-6 border-2 border-dashed border-slate-200 rounded-2xl flex items-center gap-4 bg-slate-50/50">
                                <input type="file" name="foto" onchange="previewFile(this)"
                                    class="text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 file:transition-colors cursor-pointer">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white p-5 rounded-2xl font-black uppercase text-xs tracking-[0.15em] shadow-xl shadow-indigo-100/50 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                            <i class="fas fa-fingerprint text-sm"></i>
                            Otorisasi & Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="authModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[40px] p-10 max-w-sm w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div id="modalLoadingState" class="hidden text-center py-4">
                <div class="relative w-16 h-16 flex items-center justify-center mx-auto mb-6">
                    <div class="absolute inset-0 rounded-2xl bg-indigo-50 animate-pulse"></div>
                    <i class="fas fa-shield-halved text-2xl text-indigo-600 animate-pulse"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Menyimpan Perubahan</h3>
                <p class="text-slate-400 text-xs font-medium px-4 leading-relaxed">Sistem keamanan sedang memperbarui
                    otorisasi hak akses kader Anda...</p>
            </div>

            <div id="modalConfirmState">
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Simpan Perubahan?</h3>
                <p class="text-slate-400 text-xs text-center font-medium mb-6 leading-relaxed px-2">
                    Apakah Anda yakin ingin memperbarui data kredensial profil identitas digital Anda pada sistem
                    StuntGuard?
                </p>

                <div class="flex gap-4">
                    <button onclick="closeAuthModal()"
                        class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors text-sm text-center">
                        Batal
                    </button>
                    <button onclick="confirmSubmit()"
                        class="flex-1 bg-indigo-600 text-white font-bold py-4 rounded-2xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-100 text-sm text-center">
                        Ya, Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewFile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function handleProfileSubmit(event) {
            // Intersep submit form untuk memunculkan modal kustom
            event.preventDefault();

            const modal = document.getElementById('authModal');
            const content = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function closeAuthModal() {
            const modal = document.getElementById('authModal');
            const content = document.getElementById('modalContent');

            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function confirmSubmit() {
            // Sembunyikan konfirmasi, tunjukkan efek loading state
            document.getElementById('modalConfirmState').classList.add('hidden');
            document.getElementById('modalLoadingState').classList.remove('hidden');

            // Berikan jeda efek visual loading sebelum eksekusi submit murni bawaan PHP dijalankan
            setTimeout(() => {
                document.getElementById('profileForm').submit();
            }, 1200);
        }
    </script>
</body>

</html>