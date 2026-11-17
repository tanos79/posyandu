<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kader') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Balita - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }

        .input-active {
            transition: all 0.3s ease;
        }

        .input-active:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>

<body class="flex min-h-screen">

    <?php include 'sidebar.php'; ?>

    <main class="flex-1 ml-72 p-12">
        <header class="mb-10 flex justify-between items-end">
            <div>
                <span class="text-indigo-600 font-extrabold text-xs uppercase tracking-widest">Langkah 1:
                    Identitas</span>
                <h1 class="text-4xl font-extrabold text-slate-800 mt-1 tracking-tight">Registrasi Balita</h1>
                <p class="text-slate-400 mt-2 text-sm font-medium">Lengkapi data awal untuk memulai pemantauan tumbuh
                    kembang.</p>
            </div>
            <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner">
                    <i class="fas fa-baby text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Input</p>
                    <p id="form-status" class="text-sm font-extrabold text-amber-500">Menunggu Data...</p>
                </div>
            </div>
        </header>

        <form action="proses-tambah-anak.php" method="POST" id="regForm">
            <div class="bg-white rounded-[40px] p-12 shadow-sm border border-slate-100/60 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-[100px] -z-0 opacity-40"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <span
                            class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-extrabold">1</span>
                        <h2 class="text-base font-extrabold text-slate-800 tracking-tight">Informasi Kelahiran</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div class="space-y-2">
                            <label class="text-xs font-extrabold text-slate-700 ml-1 uppercase tracking-wider">NIK
                                Anak</label>
                            <input type="text" name="nik_anak" maxlength="16"
                                class="input-active w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm"
                                placeholder="Masukkan 16 digit NIK" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-extrabold text-slate-700 ml-1 uppercase tracking-wider">Nama
                                Lengkap Balita</label>
                            <input type="text" name="nama_lengkap"
                                class="input-active w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm"
                                placeholder="Nama sesuai akta lahir" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-8 mb-8">
                        <div class="space-y-2">
                            <label class="text-xs font-extrabold text-slate-700 ml-1 uppercase tracking-wider">Jenis
                                Kelamin</label>
                            <select name="jenis_kelamin"
                                class="input-active w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm appearance-none"
                                required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-extrabold text-slate-700 ml-1 uppercase tracking-wider">Tempat
                                Lahir</label>
                            <input type="text" name="tempat_lahir"
                                class="input-active w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm"
                                placeholder="Kota lahir" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-extrabold text-slate-700 ml-1 uppercase tracking-wider">Tanggal
                                Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                class="input-active w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm text-slate-600"
                                required>
                        </div>
                    </div>

                    <hr class="my-10 border-slate-100">

                    <div class="flex items-center gap-4 mb-8">
                        <span
                            class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-xs font-extrabold">2</span>
                        <h2 class="text-base font-extrabold text-slate-800 tracking-tight">Kondisi Saat Lahir</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div class="group">
                            <label
                                class="text-xs font-extrabold text-slate-700 ml-1 mb-2 block uppercase tracking-wider">Berat
                                Badan Lahir</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="berat_lahir"
                                    class="input-active w-full pl-6 pr-16 py-4 bg-emerald-50/30 border border-emerald-100 rounded-2xl outline-none focus:ring-2 focus:ring-emerald-500 font-extrabold text-emerald-700 text-sm"
                                    placeholder="0.00" required>
                                <span class="absolute right-6 top-4 font-bold text-emerald-400 text-sm">kg</span>
                            </div>
                        </div>
                        <div class="group">
                            <label
                                class="text-xs font-extrabold text-slate-700 ml-1 mb-2 block uppercase tracking-wider">Tinggi
                                Badan Lahir</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="tinggi_lahir"
                                    class="input-active w-full pl-6 pr-16 py-4 bg-emerald-50/30 border border-emerald-100 rounded-2xl outline-none focus:ring-2 focus:ring-emerald-500 font-extrabold text-emerald-700 text-sm"
                                    placeholder="0.0" required>
                                <span class="absolute right-6 top-4 font-bold text-emerald-400 text-sm">cm</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-10 border-slate-100">

                    <div class="flex items-center gap-4 mb-8">
                        <span
                            class="w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-extrabold">3</span>
                        <h2 class="text-base font-extrabold text-slate-800 tracking-tight">Kontak Keluarga</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-10">
                        <div class="space-y-2">
                            <label class="text-xs font-extrabold text-slate-700 ml-1 uppercase tracking-wider">Nama Ibu
                                Kandung</label>
                            <input type="text" name="nama_ibu"
                                class="input-active w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-purple-500 font-medium text-sm"
                                placeholder="Nama lengkap ibu" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-extrabold text-slate-700 ml-1 uppercase tracking-wider">WhatsApp
                                Orang Tua</label>
                            <div class="relative">
                                <span class="absolute left-6 top-4 text-slate-400 font-bold text-sm">+62</span>
                                <input type="text" name="no_hp"
                                    class="input-active w-full pl-16 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-purple-500 font-medium text-sm"
                                    placeholder="8123456xxx" required>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex justify-between items-center bg-slate-50 p-6 rounded-[32px] border border-slate-100/50">
                        <p class="text-xs text-slate-400 font-medium max-w-xs ml-4 leading-relaxed">
                            Pastikan semua data sudah sesuai dengan buku KIA sebelum menekan tombol simpan.
                        </p>
                        <div class="flex gap-4">
                            <button type="reset" id="resetBtn"
                                class="px-8 py-4 text-slate-400 font-bold hover:text-rose-500 transition-colors text-sm rounded-2xl">Batalkan</button>
                            <button type="submit" name="Daftarkan_Balita" id="submitBtn"
                                class="group px-10 py-4 bg-slate-800 text-white font-bold rounded-2xl shadow-xl shadow-slate-900/10 hover:bg-slate-900 transition-all flex items-center gap-3 text-sm">
                                <span>Simpan Data Balita</span>
                                <i
                                    class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <div id="loadingModal"
        class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
        <div
            class="bg-white rounded-[40px] p-10 max-w-sm w-full shadow-2xl border border-slate-50 text-center transform scale-100 transition-all">
            <div class="flex justify-center mb-6">
                <div
                    class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner animate-pulse">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Memproses Data</h3>
            <p class="text-slate-400 text-xs font-medium leading-relaxed px-4">
                Sistem sedang mengonstruksi entitas data balita baru dan mengamankan basis rekam medis StuntGuard...
            </p>
        </div>
    </div>

    <script>
        const form = document.getElementById('regForm');
        const statusText = document.getElementById('form-status');
        const inputs = form.querySelectorAll('input, select');
        const loadingModal = document.getElementById('loadingModal');

        // 1. Update Status Text saat input diisi (Bawaan Logika Form Anda)
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                const filled = Array.from(inputs).filter(i => i.value !== "").length;
                const total = inputs.length;

                if (filled > 0 && filled < total) {
                    statusText.innerText = "Sedang Melengkapi...";
                    statusText.className = "text-sm font-bold text-indigo-500";
                } else if (filled === total) {
                    statusText.innerText = "Siap Disimpan!";
                    statusText.className = "text-sm font-bold text-emerald-500";
                }
            });
        });

        // 2. Trigger Pop-up Modal Saat Proses Submit Dikirimkan
        form.addEventListener('submit', function (e) {
            // Tampilkan modal pop-up kustom loading state
            loadingModal.classList.remove('hidden');

            const btn = document.getElementById('submitBtn');
            const resetBtn = document.getElementById('resetBtn');

            // Menonaktifkan kontrol interaksi tombol selama transmisi
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-xs"></i> Memproses...';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            resetBtn.classList.add('hidden');
        });
    </script>
</body>

</html>