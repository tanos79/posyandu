<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otorisasi Pembuatan Akun - StuntGuard</title>
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

    <div class="bg-white rounded-[40px] p-10 max-w-sm w-full shadow-2xl border border-slate-50 transform scale-100 transition-all duration-300 text-center">

        @if ($insert_status === "sukses")
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Akun Dibuat</h3>
            <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
                Akses login akun untuk wali murid bernama <strong class="text-slate-600">{{ $nama }}</strong> berhasil didaftarkan ke sistem StuntGuard.
            </p>
            <button onclick="redirectUser()"
                class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-slate-900/10">
                Selesai & Kembali
            </button>
        @else
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Membuat Akun</h3>
            <p class="text-slate-400 text-xs font-medium mb-4 leading-relaxed px-4">
                Sistem pangkalan data menolak enkripsi data akun baru karena kendala teknis:
            </p>

            <div class="bg-rose-50 border border-rose-100 p-4 rounded-xl font-mono text-[11px] text-rose-600 text-left mb-6 break-all whitespace-pre-line shadow-inner">
                Error: {{ $error_message ?? 'Kesalahan tidak diketahui' }}
            </div>

            <button onclick="redirectUser()"
                class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
                Kembali ke Daftar
            </button>
        @endif

    </div>

    <script>
        function redirectUser() {
            window.location.href = "{{ route('kader.user.index') }}";
        }

        @if ($insert_status === "sukses")
            setTimeout(() => {
                redirectUser();
            }, 3000);
        @endif
    </script>
</body>
</html>
