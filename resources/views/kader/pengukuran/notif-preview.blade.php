<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Notifikasi - StuntGuard</title>
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

    <div class="bg-white rounded-[40px] p-10 max-w-md w-full shadow-2xl border border-slate-50 transform scale-100 transition-all duration-300">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-syringe"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Kirim Pengingat Imunisasi</h3>
        <p class="text-slate-400 text-xs text-center font-medium mb-4">Pratinjau pesan jadwal imunisasi terdekat yang siap dikirim:</p>

        <div class="bg-[#e5ddd5] p-4 rounded-2xl border border-slate-200 text-xs text-slate-800 shadow-inner mb-6 whitespace-pre-line leading-relaxed relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.06] pointer-events-none"
                style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');">
            </div>
            <div class="bg-white p-3 rounded-xl shadow-sm relative max-w-[95%] font-sans">
                <span>{!! str_replace(['*', '*'], ['<strong>', '</strong>'], $pesan_teks) !!}</span>
                <span class="block text-[9px] text-slate-400 text-right mt-1.5">Selesai Pratinjau</span>
            </div>
        </div>

        <div class="flex gap-4">
            <button onclick="window.location.href='{{ route('kader.pengukuran.index') }}'"
                class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors text-sm text-center">
                Batal
            </button>
            <button onclick="bukaWhatsApp()"
                class="flex-1 bg-emerald-600 text-white font-bold py-4 rounded-2xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100 text-sm text-center">
                Buka WhatsApp
            </button>
        </div>
    </div>

    <script>
        function bukaWhatsApp() {
            window.location.href = '{!! $link_wa !!}';
        }
    </script>
</body>
</html>
