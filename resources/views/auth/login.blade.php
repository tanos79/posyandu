<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StuntGuard</title>
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
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6 text-slate-800">

    <div class="w-full max-w-[440px] bg-white rounded-[40px] shadow-2xl shadow-indigo-100/50 p-10 border border-slate-100">
        <div class="text-center mb-10">
            <div class="bg-indigo-600 text-white w-16 h-16 rounded-[22px] flex items-center justify-center text-3xl mx-auto mb-5 shadow-lg shadow-indigo-100">
                <i class="fas fa-shield-heart"></i>
            </div>
            <h1 class="text-3xl font-[800] text-slate-900 tracking-tight">StuntGuard</h1>
            <p class="text-slate-400 font-bold text-sm mt-1">Monitoring Gizi & Stunting</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Username</label>
                <div class="relative group">
                    <i class="far fa-user absolute left-5 top-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    <input type="text" name="username" placeholder="Masukkan username" class="w-full pl-12 pr-5 py-4 bg-slate-50/60 border border-slate-100 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 transition-all font-bold text-slate-700 text-sm" required>
                </div>
            </div>

            <div>
                <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Password</label>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-5 top-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    <input type="password" name="password" placeholder="••••••••" class="w-full pl-12 pr-5 py-4 bg-slate-50/60 border border-slate-100 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 transition-all font-bold text-slate-700 text-sm tracking-widest" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-extrabold text-xs uppercase tracking-widest py-5 rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                Masuk Sekarang
            </button>
        </form>

        <p class="text-center mt-10 text-xs sm:text-sm text-slate-400 font-bold">
            Lupa password? Hubungi <a href="#" class="text-indigo-600 font-extrabold hover:underline">Admin Posyandu</a>
        </p>
    </div>

    <!-- Error Modal Popup -->
    <div id="errorModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[32px] w-full max-w-sm p-8 border border-slate-100 shadow-2xl m-4 transform scale-95 transition-transform duration-300 text-center relative">
            <div class="w-20 h-20 bg-rose-500 text-white rounded-[24px] flex items-center justify-center text-3xl mx-auto mb-6 shadow-md shadow-rose-100">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-2">Gagal Masuk</h2>
            <p id="modalDesc" class="text-xs sm:text-sm text-slate-400 font-bold px-2 leading-relaxed mb-6"></p>
            <button onclick="closeErrorModal()" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs uppercase tracking-widest rounded-xl shadow-md transition-all">
                Coba Kembali
            </button>
        </div>
    </div>

    <script>
        function openErrorModal(errorMessage) {
            const modal = document.getElementById('errorModal');
            document.getElementById('modalDesc').innerText = errorMessage;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        window.onclick = function (event) {
            const modal = document.getElementById('errorModal');
            if (event.target == modal) {
                closeErrorModal();
            }
        }

        @if (session('error'))
            window.addEventListener('DOMContentLoaded', () => {
                openErrorModal("{{ session('error') }}");
            });
        @endif
    </script>
</body>
</html>
