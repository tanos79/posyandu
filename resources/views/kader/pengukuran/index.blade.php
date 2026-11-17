@extends('layouts.app-kader')

@section('title', 'Monitoring Anak | StuntGuard')

@section('content')
<div class="mb-10">
    <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Monitoring Anak</h1>
    <p class="text-slate-400 font-medium mt-2">Kelola Pengukuran & Jadwal Imunisasi Balita secara real-time.</p>
</div>

@if (session('error_alert'))
<div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold rounded-2xl flex items-center gap-2">
    <i class="fas fa-exclamation-circle"></i>
    {{ session('error_alert') }}
</div>
@endif

<div class="bg-white rounded-[40px] shadow-sm border border-slate-50 p-10 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                <th class="pb-6 px-4">Identitas Balita</th>
                <th class="pb-6 px-4 text-center">Status Gizi</th>
                <th class="pb-6 px-4 text-center">Imunisasi</th>
                <th class="pb-6 px-4 text-right">Aksi & Notifikasi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @if ($children->isEmpty())
                <tr>
                    <td colspan="4" class="py-12 text-center text-slate-400 font-bold uppercase text-xs tracking-wider">Belum ada data balita.</td>
                </tr>
            @else
                @foreach ($children as $row)
                    @php
                        // Get latest measurement status stunting
                        $latest = $row->pengukuran->first();
                        $status = strtolower($latest->status_stunting ?? '');
                    @endphp
                    <tr class="group hover:bg-slate-50/70 transition-all">
                        <td class="py-6 px-4">
                            <h3 class="font-extrabold text-slate-800 text-base capitalize">{{ $row->nama_lengkap }}</h3>
                            <p class="text-[10px] text-slate-400 font-black tracking-wider uppercase mt-1">
                                NIK: {{ !empty($row->nik_anak) ? $row->nik_anak : 'BELUM DIISI' }}
                            </p>
                        </td>

                        <td class="py-6 px-4 text-center">
                            @if ($status == 'normal')
                                <span class="px-5 py-2 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black tracking-widest border border-emerald-100">NORMAL</span>
                            @elseif ($status == 'stunted')
                                <span class="px-5 py-2 rounded-full bg-rose-50 text-rose-600 text-[10px] font-black tracking-widest border border-rose-100">STUNTED</span>
                            @else
                                <span class="text-[10px] font-black text-slate-300 italic tracking-widest uppercase">NO DATA</span>
                            @endif
                        </td>

                        <td class="py-6 px-4 text-center">
                            <a href="{{ route('kader.imunisasi.create', $row->id) }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-orange-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all">
                                <i class="fas fa-syringe text-xs"></i> Kelola Imunisasi
                            </a>
                        </td>

                        <td class="py-6 px-4 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('kader.pengukuran.create', $row->id) }}"
                                    class="inline-flex items-center justify-center w-11 h-11 bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-colors"
                                    title="Input Pengukuran Baru">
                                    <i class="fas fa-chart-line text-xs"></i>
                                </a>

                                <button type="button"
                                    onclick="openNotificationModal('{{ $row->id }}', '{{ addslashes($row->nama_lengkap) }}')"
                                    class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                                    <i class="fas fa-bell"></i> Notif
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<!-- Notification Modal -->
<div id="notificationModal"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
        id="modalContent">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-paper-plane"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Kirim Notifikasi Posyandu?</h3>
        <p class="text-slate-400 text-sm text-center leading-relaxed font-medium mb-8">
            Anda akan diarahkan menuju gerbang pengiriman pesan konfirmasi/peringatan untuk orang tua dari <span
                id="targetChildName" class="text-indigo-600 font-bold"></span>.
        </p>
        <div class="flex gap-4">
            <button onclick="closeNotificationModal()"
                class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors">
                Kembali
            </button>
            <a id="executeNotificationBtn" href="#"
                class="flex-1 bg-indigo-600 text-white text-center font-bold py-4 rounded-2xl hover:bg-indigo-700 transition-colors block">
                Lanjutkan
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openNotificationModal(id, nama) {
        const modal = document.getElementById('notificationModal');
        const content = document.getElementById('modalContent');
        const nameSpan = document.getElementById('targetChildName');
        const actionBtn = document.getElementById('executeNotificationBtn');

        nameSpan.innerText = nama;
        actionBtn.href = "{{ url('kader/kirim-notif') }}/" + id;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeNotificationModal() {
        const modal = document.getElementById('notificationModal');
        const content = document.getElementById('modalContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
