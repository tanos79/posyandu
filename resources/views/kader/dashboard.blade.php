@extends('layouts.app-kader')

@section('title', 'Dashboard Kader - StuntGuard')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<header class="mb-10 flex justify-between items-center">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Halo, {{ Auth::user()->nama ?: ucwords(str_replace('_', ' ', Auth::user()->username)) }}! 👋</h1>
        <p class="text-slate-400 font-medium mt-2">Berikut adalah ringkasan data stunting wilayah kamu hari ini.</p>
    </div>
    <div class="bg-white p-3 pr-6 rounded-[24px] border border-slate-100 shadow-sm flex items-center gap-4">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?: Auth::user()->username) }}&background=3b82f6&color=fff"
            class="w-10 h-10 rounded-xl object-cover">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akses Akun</p>
            <span class="text-sm font-bold text-slate-700">Kader Posyandu</span>
        </div>
    </div>
</header>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-100/80 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex items-center gap-4">
            <div class="bg-blue-50 text-blue-600 w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-baby"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Balita</p>
                <h2 class="text-2xl font-black text-slate-800 mt-0.5">{{ $total_anak }}</h2>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-100/80 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex items-center gap-4">
            <div class="bg-rose-50 text-rose-600 w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kasus Stunting</p>
                <h2 class="text-2xl font-black text-rose-600 mt-0.5">{{ $total_stunting }}</h2>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-100/80 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex items-center gap-4">
            <div class="bg-amber-50 text-amber-600 w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-running"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jemput Bola</p>
                <h2 class="text-2xl font-black text-amber-600 mt-0.5">{{ $total_jemput }}</h2>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-slate-100/80 hover:scale-[1.02] transition-transform duration-300">
        <div class="flex items-center gap-4">
            <div class="bg-emerald-50 text-emerald-600 w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ukur Bulan Ini</p>
                <h2 class="text-2xl font-black text-emerald-600 mt-0.5">{{ $total_ukur }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
        <div class="flex justify-between items-center mb-8">
            <h3 class="font-bold text-slate-800 text-lg">Tren Pengukuran (6 Bulan Terakhir)</h3>
            <button onclick="openInfoModal('tren')"
                class="text-xs font-bold bg-blue-50 text-blue-600 px-4 py-2 rounded-full hover:bg-blue-100 transition-colors flex items-center gap-2">
                <i class="fas fa-info-circle"></i> Detail Tren
            </button>
        </div>
        <div class="h-[300px]">
            <canvas id="realTimeChart"></canvas>
        </div>
    </div>

    <div class="flex flex-col gap-6">
        <div class="bg-blue-600 p-8 rounded-[40px] shadow-xl shadow-blue-100 text-white relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-blue-500 rounded-full opacity-30 group-hover:scale-125 transition-transform"></div>
            <h3 class="font-extrabold text-xl mb-4 leading-tight">Analisis Sistem ✨</h3>
            <p class="text-blue-100 text-sm leading-relaxed mb-6 font-medium">
                Bulan ini ada <b>{{ $total_jemput }}</b> balita yang belum hadir. Segera gunakan fitur WhatsApp Blast untuk meningkatkan kehadiran.
            </p>
            <button onclick="openInfoModal('wa_blast')"
                class="block w-full bg-white text-blue-600 text-center py-4 rounded-2xl font-extrabold text-xs hover:bg-blue-50 transition shadow-md">
                Info Strategi Intervensi
            </button>
        </div>

        <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <h4 class="font-bold text-slate-800 mb-1">Target Desa</h4>
                <p class="text-slate-400 text-xs font-semibold italic">"Zero Stunting 2026"</p>
            </div>
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 text-sm font-bold">
                2026
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="infoModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300" id="modalContent">
        <div class="flex justify-between items-start mb-6">
            <div id="modalIcon" class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-4"></div>
            <button onclick="closeInfoModal()" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <h3 id="modalTitle" class="text-xl font-extrabold text-slate-800 mb-2"></h3>
        <p id="modalDescription" class="text-slate-500 text-sm leading-relaxed font-medium mb-8"></p>
        <button onclick="closeInfoModal()" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl hover:bg-slate-800 transition">
            Mengerti
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openInfoModal(type) {
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('modalContent');
        const icon = document.getElementById('modalIcon');
        const title = document.getElementById('modalTitle');
        const desc = document.getElementById('modalDescription');

        if (type === 'tren') {
            icon.className = "w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl";
            icon.innerHTML = '<i class="fas fa-chart-line"></i>';
            title.innerText = 'Rincian Evaluasi Tren';
            desc.innerText = 'Grafik disamping memetakan total partisipasi penimbangan bulanan secara real-time. Jika grafik condong menurun, disarankan kader meningkatkan koordinasi berkala dengan kepala dusun/RT setempat.';
        } else if (type === 'wa_blast') {
            icon.className = "w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl";
            icon.innerHTML = '<i class="fab fa-whatsapp"></i>';
            title.innerText = 'Rencana Gerak WhatsApp Blast';
            desc.innerText = 'Sebanyak {{ $total_jemput }} anak terdeteksi mangkir bulan ini. Fitur otomatisasi pesan akan mengirimkan jadwal pengingat personal langsung ke nomor WhatsApp wali murid terdaftar guna mengejar target eliminasi stunting.';
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeInfoModal() {
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('modalContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Chart.js Setup
    const ctx = document.getElementById('realTimeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Jumlah Anak Diukur',
                data: {!! json_encode($data_grafik) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.04)',
                borderWidth: 4,
                fill: true,
                tension: 0.38,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans', weight: 'bold' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', weight: 'bold' } }
                }
            }
        }
    });
</script>
@endsection
