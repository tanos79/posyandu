@extends('layouts.app-ortua')

@section('title', 'Dashboard Orang Tua - StuntGuard')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<header class="flex justify-between items-start mb-12">
    <div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Selamat Datang, {{ $user->nama }}!</h1>
        <p class="text-slate-400 font-medium text-sm mt-1">Monitoring gizi si kecil secara berkala.</p>
    </div>
    <div class="flex items-center gap-4 bg-white p-2.5 pr-6 rounded-full border border-slate-100 shadow-sm">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=6366F1&color=fff"
            class="w-10 h-10 rounded-full shadow-inner">
        <div>
            <p class="text-xs font-extrabold text-slate-800 leading-tight">{{ $user->nama }}</p>
            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider mt-0.5">Orang Tua</p>
        </div>
    </div>
</header>

@if (session('error_alert'))
<div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold rounded-2xl flex items-center gap-2">
    <i class="fas fa-exclamation-circle"></i>
    {{ session('error_alert') }}
</div>
@endif

<div class="bg-white p-10 rounded-[40px] border border-slate-100/60 shadow-sm mb-12 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
    <div class="flex items-center gap-8">
        <div class="relative">
            <div class="w-24 h-24 bg-indigo-50/60 rounded-[32px] flex items-center justify-center text-indigo-600 text-4xl shadow-inner">
                <i class="fas fa-baby"></i>
            </div>
            <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-8 h-8 rounded-xl flex items-center justify-center border-4 border-white shadow-md">
                <i class="fas fa-check text-xs"></i>
            </div>
        </div>
        <div>
            <div class="flex flex-wrap items-center gap-3 mb-2.5">
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $nama_anak }}</h2>
                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider border border-emerald-100">
                    Status: {{ strtoupper($status_stunting) }}
                </span>
            </div>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-venus-mars text-indigo-400 text-sm"></i> {{ $jk }}
                <span class="text-slate-200 font-normal">|</span>
                <i class="fas fa-calendar-alt text-indigo-400 text-sm"></i> Data Realtime Posyandu
            </p>
        </div>
    </div>

    <div class="flex gap-6">
        <div class="bg-slate-50/60 px-10 py-6 rounded-[28px] border border-slate-100/80 text-center min-w-[150px]">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Berat Terakhir</p>
            <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $berat_terakhir }} <span class="text-xs font-bold text-slate-400 uppercase">kg</span></p>
        </div>
        <div class="bg-slate-50/60 px-10 py-6 rounded-[28px] border border-slate-100/80 text-center min-w-[150px]">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tinggi Terakhir</p>
            <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $tinggi_terakhir }} <span class="text-xs font-bold text-slate-400 uppercase">cm</span></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    <div class="lg:col-span-2 bg-white p-10 rounded-[40px] border border-slate-100/60 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-center mb-10">
            <h3 class="font-extrabold text-slate-800 text-base tracking-tight">Grafik Pertumbuhan Si Kecil</h3>
            <div class="px-4 py-1.5 bg-indigo-600 text-white text-[10px] font-extrabold rounded-xl shadow-md shadow-indigo-100 uppercase tracking-wider">
                BB (Kilogram)
            </div>
        </div>
        <div class="relative w-full h-[240px]">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <div class="space-y-8">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 p-10 rounded-[40px] shadow-xl shadow-indigo-100 text-white relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <h4 class="font-extrabold text-lg mb-2.5 tracking-tight">Tips Hari Ini ✨</h4>
            <p class="text-indigo-100 text-xs font-medium leading-relaxed mb-6">Penuhi kebutuhan MPASI dengan gizi seimbang untuk optimalkan tumbuh kembang si kecil.</p>
            <a href="{{ route('orang-tua.nutrisi') }}"
                class="inline-block bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl text-xs font-bold backdrop-blur-md transition-all tracking-wide">
                Cek Menu Makan
            </a>
        </div>

        <div class="bg-white p-10 rounded-[40px] border border-slate-100/60 shadow-sm">
            <h4 class="font-extrabold text-slate-800 mb-6 text-base tracking-tight">Kunjungan Berikutnya</h4>
            <div class="flex items-center gap-5 p-5 bg-amber-50/60 rounded-[24px] border border-amber-100/60">
                <div class="bg-amber-500 text-white w-12 h-12 rounded-xl flex items-center justify-center shadow-md shadow-amber-100 text-lg">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-amber-600 uppercase tracking-widest mb-0.5">Posyandu</p>
                    <p class="text-sm font-extrabold text-slate-800">15 Mei 2026</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('growthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                data: {!! json_encode($chartData) !!},
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.04)',
                fill: true,
                tension: 0.35,
                borderWidth: 4,
                pointRadius: 5,
                pointHitRadius: 10,
                pointBackgroundColor: '#4361ee',
                pointBorderColor: '#fff',
                pointBorderWidth: 2.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    grid: { color: '#F1F5F9', drawBorder: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' }, color: '#94A3B8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' }, color: '#94A3B8' }
                }
            }
        }
    });
</script>
@endsection
