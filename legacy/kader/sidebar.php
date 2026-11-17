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
        <!-- KATEGORI: UTAMA -->
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 mt-2 ml-4">Utama</p>
        
        <!-- Dashboard diarahkan ke dashboard_kader.php -->
        <a href="dashboard_kader.php" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 <?= (basename($_SERVER['PHP_SELF']) == 'dashboard_kader.php') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600'; ?>">
            <i class="fas fa-th-large text-sm"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Dashboard</span>
        </a>

        <a href="data-anak.php" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 <?= (basename($_SERVER['PHP_SELF']) == 'data-anak.php') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600'; ?>">
            <i class="fas fa-baby text-sm"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Data Balita</span>
        </a>

        <a href="data-pengukuran.php" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 <?= (basename($_SERVER['PHP_SELF']) == 'data-pengukuran.php') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600'; ?>">
            <i class="fas fa-weight text-sm"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Monitoring</span>
        </a>

        <a href="jemput-bola.php" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 <?= (basename($_SERVER['PHP_SELF']) == 'jemput-bola.php') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600'; ?>">
            <i class="fas fa-truck-medical text-sm"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Jemput Bola</span>
        </a>

        <!-- KATEGORI: MANAJEMEN -->
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 mt-6 ml-4">Manajemen</p>

        <a href="data-pengguna.php" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group <?= (basename($_SERVER['PHP_SELF']) == 'data-pengguna.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-400 hover:bg-slate-50 hover:text-blue-600'; ?>">
            <i class="fas fa-users-cog text-sm"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Kelola Akun Warga</span>
        </a>

        <a href="laporan.php" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 <?= (basename($_SERVER['PHP_SELF']) == 'laporan.php') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600'; ?>">
            <i class="fas fa-file-medical text-sm"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Laporan</span>
        </a>

        <!-- KATEGORI: SISTEM -->
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 mt-6 ml-4">Sistem</p>

        <a href="profil.php" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group hover:bg-slate-50 <?= (basename($_SERVER['PHP_SELF']) == 'profil.php') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:text-blue-600'; ?>">
            <i class="fas fa-user-circle text-sm"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Profil Saya</span>
        </a>
    </nav>

    <!-- Logout Area -->
    <div class="p-6 border-t border-gray-50">
        <a href="../logout.php" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition-all group text-rose-500 hover:bg-rose-50">
            <i class="fas fa-sign-out-alt text-sm rotate-180"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Keluar</span>
        </a>
    </div>
</div>