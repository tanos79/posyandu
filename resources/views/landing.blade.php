<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StuntGuard - Pantau Tumbuh Kembang Anak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-white text-slate-900">

    <!-- Navbar -->
    <nav class="flex items-center justify-between px-10 py-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 p-2 rounded-lg text-white shadow-lg shadow-blue-200">
                <i class="fas fa-shield-heart text-xl"></i>
            </div>
            <span class="font-extrabold text-xl tracking-tight text-blue-900">StuntGuard</span>
        </div>

        <div class="hidden md:flex items-center gap-10 text-sm font-semibold text-slate-500">
            <a href="#" class="text-blue-600">Beranda</a>
            <a href="#deteksi" class="hover:text-blue-600 transition-colors">Deteksi Stunting</a>
            <a href="#imunisasi" class="hover:text-blue-600 transition-colors">Jadwal Imunisasi</a>
        </div>

        <div class="flex items-center gap-6">
            @if (!$is_logged_in)
                <!-- Kondisi Belum Login: Tampilkan Tombol Masuk -->
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-blue-600 transition-all flex items-center gap-2">
                    <i class="far fa-user"></i> Masuk
                </a>
            @else
                <!-- Kondisi Sudah Login: Tampilkan Role dan Nama -->
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-bold text-slate-400 uppercase leading-none">
                            {{ $role === 'kader' ? 'Kader Kesehatan' : 'Orang Tua' }}
                        </p>
                        <p class="text-sm font-bold text-slate-800">{{ $username }}</p>
                    </div>

                    @if ($role === 'kader')
                        <a href="{{ route('kader.dashboard') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all">Kelola Data</a>
                    @else
                        <a href="{{ route('orang-tua.dashboard') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Dashboard</a>
                    @endif

                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="text-slate-400 hover:text-red-500 transition-colors" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-10 py-20 grid md:grid-cols-2 gap-16 items-center">
        <div>
            <span class="bg-blue-50 text-blue-600 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest mb-6 inline-block">
                ● PLATFORM KESEHATAN ANAK #1
            </span>
            <h1 class="text-6xl font-extrabold leading-[1.1] mb-6 text-slate-900">
                Pantau Tumbuh <br> Kembang <br> <span class="text-blue-600">Anak Tercinta</span>
            </h1>
            <p class="text-slate-500 text-lg leading-relaxed mb-10 max-w-md font-medium">
                Aplikasi pintar untuk deteksi dini stunting, manajemen jadwal imunisasi otomatis, dan panduan nutrisi harian.
            </p>

            @if (!$is_logged_in)
                <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-start gap-3 max-w-sm">
                    <i class="fas fa-info-circle text-amber-500 mt-1"></i>
                    <p class="text-xs text-amber-800 font-medium leading-relaxed">Silahkan <b>Masuk</b> untuk mulai mencatat dan melihat grafik perkembangan tinggi badan anak Anda.</p>
                </div>
            @endif
        </div>

        <!-- Right Side Mockup -->
        <div class="relative">
            <div class="bg-white p-8 rounded-[40px] shadow-2xl border border-slate-50 relative z-10">
                <div class="flex items-center gap-4 mb-8">
                    <img src="https://ui-avatars.com/api/?name=Anak+Sehat&background=4461F2&color=fff" class="w-14 h-14 rounded-full" alt="Avatar">
                    <div>
                        <h4 class="font-extrabold text-slate-800">Contoh Data Anak</h4>
                        <p class="text-slate-400 text-xs font-semibold">12 Bulan • Laki-laki</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50/30 p-5 rounded-2xl border border-blue-50 text-center">
                        <p class="text-slate-400 text-[10px] font-bold uppercase">Berat</p>
                        <h5 class="text-xl font-bold text-slate-800">9.5 kg</h5>
                    </div>
                    <div class="bg-blue-50/30 p-5 rounded-2xl border border-blue-50 text-center">
                        <p class="text-slate-400 text-[10px] font-bold uppercase">Tinggi</p>
                        <h5 class="text-xl font-bold text-slate-800">75 cm</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Deteksi Stunting Section -->
    <section id="deteksi" class="bg-slate-50 py-24 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-10">
            <div class="text-center mb-16">
                <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest mb-4 inline-block">Sistem Diagnosa</span>
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Deteksi Dini Stunting</h2>
                <p class="text-slate-500 mt-2 max-w-xl mx-auto font-medium">StuntGuard menganalisis metrik tumbuh kembang anak secara real-time berdasarkan standar deviasi (Z-Score) Kementerian Kesehatan.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:translate-y-[-4px] transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-inner mb-6">
                        <i class="fas fa-weight-scale"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Metrik Antropometri</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Pengukuran tinggi badan berdasarkan umur (TB/U) dan berat badan berdasarkan umur (BB/U) diinput rutin setiap bulan.</p>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:translate-y-[-4px] transition-all duration-300">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-xl shadow-inner mb-6">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Evaluasi Status Gizi</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Sistem mendeteksi secara otomatis status gizi anak: <span class="text-rose-500 font-bold">Stunted</span> (tinggi badan &lt; 60 cm) atau <span class="text-emerald-500 font-bold">Normal</span>.</p>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:translate-y-[-4px] transition-all duration-300">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl shadow-inner mb-6">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Intervensi Cepat</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Kader kesehatan memberikan notifikasi WhatsApp langsung kepada orang tua untuk tindakan preventif dan panduan nutrisi terarah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Jadwal Imunisasi Section -->
    <section id="imunisasi" class="bg-white py-24 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-10">
            <div class="text-center mb-16">
                <span class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest mb-4 inline-block">Agenda Imunisasi</span>
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Jadwal Imunisasi Wajib</h2>
                <p class="text-slate-500 mt-2 max-w-xl mx-auto font-medium">Lindungi buah hati dari penyakit berbahaya dengan melengkapi imunisasi wajib nasional sesuai rentang usia emas.</p>
            </div>

            <div class="bg-slate-50 p-10 rounded-[40px] border border-slate-100/60 max-w-4xl mx-auto">
                <div class="relative pl-10 border-l-2 border-indigo-200 space-y-12">
                    <div class="relative">
                        <div class="absolute -left-[51px] top-1.5 w-6 h-6 rounded-full bg-indigo-600 border-4 border-white shadow-md"></div>
                        <h4 class="font-extrabold text-slate-800">Usia 0 - 1 Bulan</h4>
                        <p class="text-slate-500 text-sm font-medium mt-1">Hepatitis B (0 Bulan) dan BCG & Polio 1 (1 Bulan) untuk mencegah infeksi TBC dan Hepatitis.</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[51px] top-1.5 w-6 h-6 rounded-full bg-indigo-600 border-4 border-white shadow-md"></div>
                        <h4 class="font-extrabold text-slate-800">Usia 2 - 4 Bulan</h4>
                        <p class="text-slate-500 text-sm font-medium mt-1">DPT-HB-Hib (Dosis 1, 2, 3) dan Polio (Dosis 2, 3, 4) mencegah Difteri, Tetanus, Batuk Rejan, dan Polio.</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[51px] top-1.5 w-6 h-6 rounded-full bg-indigo-600 border-4 border-white shadow-md"></div>
                        <h4 class="font-extrabold text-slate-800">Usia 9 - 18 Bulan</h4>
                        <p class="text-slate-500 text-sm font-medium mt-1">Campak Rubella (9 Bulan) & booster DPT-HB-Hib lanjutan serta Campak Rubella lanjutan (18 Bulan).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-10 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-10 text-center">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">© 2026 StuntGuard. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
