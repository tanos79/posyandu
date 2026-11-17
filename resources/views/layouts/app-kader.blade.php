<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kader Panel - StuntGuard')</title>
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
<body class="flex">

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 flex flex-col z-50">
        <!-- Logo -->
        <div class="p-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-100">
                    <i class="fas fa-shield-virus text-white text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold text-gray-800 tracking-tighter leading-none">StuntGuard</span>
                    <span class="text-[10px] font-bold text-blue-600 tracking-widest uppercase">Cirebon Safe</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-6 space-y-1 overflow-y-auto">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 mt-2 ml-4">Utama</p>
            
            <a href="{{ route('kader.dashboard') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 {{ Route::is('kader.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600' }}">
                <i class="fas fa-th-large text-sm"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Dashboard</span>
            </a>

            <a href="{{ route('kader.anak.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 {{ Route::is('kader.anak.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600' }}">
                <i class="fas fa-baby text-sm"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Data Balita</span>
            </a>

            <a href="{{ route('kader.pengukuran.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 {{ Route::is('kader.pengukuran.*') || Route::is('kader.imunisasi.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600' }}">
                <i class="fas fa-weight text-sm"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Monitoring</span>
            </a>

            <a href="{{ route('kader.jemput-bola') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 {{ Route::is('kader.jemput-bola') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600' }}">
                <i class="fas fa-running text-sm"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Jemput Bola</span>
            </a>

            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 mt-6 ml-4">Manajemen</p>

            <a href="{{ route('kader.user.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 {{ Route::is('kader.user.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600' }}">
                <i class="fas fa-users-cog text-sm"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Kelola Akun Warga</span>
            </a>

            <a href="{{ route('kader.laporan.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 {{ Route::is('kader.laporan.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600' }}">
                <i class="fas fa-file-medical text-sm"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Laporan</span>
            </a>

            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 mt-6 ml-4">Sistem</p>

            <a href="{{ route('kader.profil') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 {{ Route::is('kader.profil') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600' }}">
                <i class="fas fa-user-circle text-sm"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Profil Saya</span>
            </a>
        </nav>

        <!-- Logout Area -->
        <div class="p-6 border-t border-gray-50">
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group text-rose-500 hover:bg-rose-50">
                <i class="fas fa-sign-out-alt text-sm rotate-180"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Keluar</span>
            </a>
        </div>
    </div>

    <!-- Main Content Panel -->
    <main class="flex-1 ml-64 p-12 min-h-screen">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
