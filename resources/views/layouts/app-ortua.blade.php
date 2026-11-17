<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Orang Tua Panel - StuntGuard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('head')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>
<body class="flex min-h-screen text-slate-800">

    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-slate-100 flex flex-col fixed h-full shadow-sm z-20">
        <div class="p-8 flex items-center gap-3">
            <div class="bg-indigo-600 p-2.5 rounded-xl text-white shadow-lg shadow-indigo-100">
                <i class="fas fa-shield-heart text-xl"></i>
            </div>
            <span class="font-extrabold text-xl text-slate-800 tracking-tight">StuntGuard</span>
        </div>

        <nav class="flex-1 px-6 space-y-1.5">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-4 mb-4">Menu Utama</p>

            <a href="{{ route('orang-tua.dashboard') }}"
                class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm transition-all {{ Route::is('orang-tua.dashboard') ? 'bg-indigo-50/60 text-indigo-600 font-extrabold' : 'text-slate-400 hover:bg-slate-50/80 hover:text-slate-700' }}">
                <i class="fas fa-th-large text-lg"></i> Dashboard
            </a>

            <a href="{{ route('orang-tua.profil-anak') }}"
                class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm transition-all {{ Route::is('orang-tua.profil-anak') ? 'bg-indigo-50/60 text-indigo-600 font-extrabold' : 'text-slate-400 hover:bg-slate-50/80 hover:text-slate-700' }}">
                <i class="fas fa-child text-lg"></i> Profil Anak
            </a>

            <a href="{{ route('orang-tua.jadwal-imunisasi') }}"
                class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm transition-all {{ Route::is('orang-tua.jadwal-imunisasi') ? 'bg-indigo-50/60 text-indigo-600 font-extrabold' : 'text-slate-400 hover:bg-slate-50/80 hover:text-slate-700' }}">
                <i class="fas fa-calendar-check text-lg"></i> Jadwal Imunisasi
            </a>

            <a href="{{ route('orang-tua.nutrisi') }}"
                class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm transition-all {{ Route::is('orang-tua.nutrisi') ? 'bg-indigo-50/60 text-indigo-600 font-extrabold' : 'text-slate-400 hover:bg-slate-50/80 hover:text-slate-700' }}">
                <i class="fas fa-apple-whole text-lg"></i> Nutrisi Harian
            </a>

            <div class="pt-6 mt-4 border-t border-slate-50">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-4 mb-4">Pengaturan Akun</p>
                <a href="{{ route('orang-tua.profil-akun') }}"
                    class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm transition-all {{ Route::is('orang-tua.profil-akun') ? 'bg-indigo-50/60 text-indigo-600 font-extrabold' : 'text-slate-400 hover:bg-slate-50/80 hover:text-slate-700' }}">
                    <i class="fas fa-user-gear text-lg"></i> Profil Akun
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-slate-50">
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();"
                class="flex items-center gap-4 px-4 py-4 text-rose-500 hover:bg-rose-50 rounded-2xl font-extrabold text-sm transition-all">
                <i class="fas fa-sign-out-alt text-lg"></i> Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content Panel -->
    <main class="flex-1 ml-72 p-12 min-h-screen">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
