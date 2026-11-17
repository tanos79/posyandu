@extends('layouts.app-kader')

@section('title', 'Input Pengukuran - StuntGuard')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
@php
    $history = $child->pengukuran()->orderBy('tgl_pengukuran', 'asc')->get();
    $labels = [];
    $bb_data = [];
    $tb_data = [];
    foreach ($history as $h) {
        $labels[] = date('d M y', strtotime($h->tgl_pengukuran));
        $bb_data[] = floatval($h->berat_badan);
        $tb_data[] = floatval($h->tinggi_badan);
    }
@endphp

<div class="mb-10 flex justify-between items-end">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Input Pengukuran</h1>
        <p class="text-slate-400 font-medium mt-2">Ananda: <span class="text-indigo-600 font-bold">{{ $child->nama_lengkap }}</span></p>
    </div>
    <a href="{{ route('kader.pengukuran.index') }}"
        class="px-6 py-3 bg-white border border-slate-100 text-slate-400 rounded-2xl text-xs font-bold uppercase tracking-widest hover:text-blue-600 shadow-sm transition-all">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-50 mb-10">
    <h3 class="font-black text-slate-400 mb-6 flex items-center gap-3 text-[11px] uppercase tracking-widest">
        <i class="fas fa-chart-line text-blue-500 text-sm"></i> Tren Pertumbuhan (KMS Digital)
    </h3>
    <div class="h-[280px]">
        <canvas id="chartKMS"></canvas>
    </div>
</div>

@if ($errors->any())
<div class="mb-8 p-6 bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold rounded-[28px] shadow-sm">
    <div class="flex items-center gap-3 mb-3">
        <i class="fas fa-exclamation-circle text-rose-600 text-lg"></i>
        <span class="text-base font-black text-rose-900 uppercase tracking-wide">Pencatatan Gagal</span>
    </div>
    <ul class="list-disc pl-5 space-y-1 font-medium">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white p-12 rounded-[40px] shadow-sm border border-slate-50">
    <form id="formPengukuran" action="{{ route('kader.pengukuran.store') }}" method="POST">
        @csrf
        <input type="hidden" name="anak_id" value="{{ $child->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Pengukuran</label>
                <input type="date" name="tgl_pengukuran" required value="{{ old('tgl_pengukuran', date('Y-m-d')) }}"
                    class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white text-slate-700 font-bold outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tinggi Badan (cm)</label>
                <input type="number" step="0.1" name="tinggi_badan" required placeholder="0.0" value="{{ old('tinggi_badan') }}"
                    class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white text-slate-700 font-bold outline-none transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Berat Badan (kg)</label>
                <input type="number" step="0.01" name="berat_badan" required placeholder="0.00" value="{{ old('berat_badan') }}"
                    class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white text-slate-700 font-bold outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Lingkar Kepala (cm)</label>
                <input type="number" step="0.01" name="lingkar_kepala" placeholder="0.00" value="{{ old('lingkar_kepala') }}"
                    class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white text-slate-700 font-bold outline-none transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pemberian Vitamin A</label>
                <div class="relative">
                    <select name="vitamin_a"
                        class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white text-slate-700 font-bold outline-none transition-all appearance-none">
                        <option value="Belum" {{ old('vitamin_a') == 'Belum' ? 'selected' : '' }}>Belum Diberikan</option>
                        <option value="Sudah" {{ old('vitamin_a') == 'Sudah' ? 'selected' : '' }}>Sudah Diberikan</option>
                    </select>
                    <div class="absolute right-4 top-5 text-slate-400 pointer-events-none text-xs">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Imunisasi</label>
                <input type="text" name="imunisasi" placeholder="Contoh: BCG, Polio 1, dll" value="{{ old('imunisasi') }}"
                    class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white text-slate-700 font-bold outline-none transition-all">
            </div>
        </div>

        <div class="mb-10 space-y-2">
            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan Tambahan</label>
            <textarea name="catatan" rows="3" placeholder="Tulis catatan perkembangan di sini..."
                class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white text-slate-700 font-bold outline-none transition-all">{{ old('catatan') }}</textarea>
        </div>

        <button type="button" onclick="triggerSubmitModal()"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white p-5 rounded-2xl font-black shadow-xl shadow-blue-100 transition-all uppercase tracking-widest text-xs">
            Simpan & Analisis Status Gizi
        </button>
    </form>
</div>

<!-- Confirm Modal -->
<div id="confirmModal"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
        id="modalContent">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Proses Pengukuran?</h3>
        <p class="text-slate-400 text-sm text-center leading-relaxed font-medium mb-8">
            Sistem StuntGuard akan menghitung Z-Score antropometri balita berdasarkan standar antropometri Kemenkes.
        </p>
        <div class="flex gap-4">
            <button onclick="closeModal()"
                class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors">
                Periksa Kembali
            </button>
            <button onclick="executeSubmit()"
                class="flex-1 bg-blue-600 text-white font-bold py-4 rounded-2xl hover:bg-blue-700 transition-colors">
                Ya, Analisis
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Chart KMS Digital
    const ctx = document.getElementById('chartKMS').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Berat (kg)',
                data: {!! json_encode($bb_data) !!},
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.05)',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointRadius: 4
            },
            {
                label: 'Tinggi (cm)',
                data: {!! json_encode($tb_data) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 12 },
                        color: '#64748b'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', weight: 'bold' }, color: '#94a3b8' } },
                y: { ticks: { font: { family: 'Plus Jakarta Sans', weight: 'bold' }, color: '#94a3b8' } }
            }
        }
    });

    // Modals
    function triggerSubmitModal() {
        const form = document.getElementById('formPengukuran');
        if (form.checkValidity()) {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        } else {
            form.reportValidity();
        }
    }

    function closeModal() {
        const modal = document.getElementById('confirmModal');
        const content = document.getElementById('modalContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    function executeSubmit() {
        const form = document.getElementById('formPengukuran');
        form.submit();
    }
</script>
@endsection
