@extends('layouts.app-kader')

@section('title', 'Kelola Imunisasi - StuntGuard')

@section('content')
@php
    $daftar_imunisasi = [
        "Hepatitis B (0 Bulan)",
        "BCG & Polio 1 (1 Bulan)",
        "DPT-HB-Hib 1 & Polio 2 (2 Bulan)",
        "DPT-HB-Hib 2 & Polio 3 (3 Bulan)",
        "DPT-HB-Hib 3 & Polio 4 (4 Bulan)",
        "Campak Rubella (9 Bulan)",
        "DPT-HB-Hib Lanjutan (18 Bulan)",
        "Campak Rubella Lanjutan (18 Bulan)"
    ];

    $jadwal_eksisting = \App\Models\Imunisasi::where('id_anak', $child->id)->get()->keyBy('jenis_vaksin');
@endphp

<div class="mb-10 flex justify-between items-end">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Kelola Imunisasi</h1>
        <p class="text-slate-400 font-medium mt-2">Atur jadwal dan status vaksinasi untuk <span class="text-indigo-600 font-bold">{{ $child->nama_lengkap }}</span>.</p>
    </div>
    <a href="{{ route('kader.pengukuran.index') }}"
        class="px-6 py-3 bg-white border border-slate-100 text-slate-400 rounded-2xl text-xs font-bold uppercase tracking-widest hover:text-blue-600 shadow-sm transition-all">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

@if (session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold rounded-2xl flex items-center gap-2">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 gap-6">
    @php $index = 0; @endphp
    @foreach ($daftar_imunisasi as $vaksin)
        @php
            $data_skrg = $jadwal_eksisting->get($vaksin);
            $tgl_val = $data_skrg ? $data_skrg->tgl_jadwal : '';
            $status_val = $data_skrg ? $data_skrg->status : 'Belum';
            $index++;
        @endphp
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-50 group hover:border-slate-200 transition-all">
            <form id="form_imunisasi_{{ $index }}" action="{{ route('kader.imunisasi.store') }}" method="POST"
                class="flex flex-col md:flex-row items-center gap-8">
                @csrf
                <input type="hidden" name="id_anak" value="{{ $child->id }}">
                <input type="hidden" name="jenis_vaksin" value="{{ $vaksin }}">

                <div class="flex items-center gap-5 w-full md:w-1/3">
                    <div class="w-14 h-14 {{ $status_val == 'Selesai' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }} rounded-2xl flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 leading-tight text-base">{{ $vaksin }}</h3>
                        <span class="text-[10px] font-black uppercase tracking-widest mt-1 inline-block {{ $status_val == 'Selesai' ? 'text-emerald-500' : 'text-amber-500' }}">
                            {{ $status_val == 'Selesai' ? 'Terlaksana' : 'Menunggu Jadwal' }}
                        </span>
                    </div>
                </div>

                <div class="w-full md:w-1/4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 ml-1">Tanggal Rencana</label>
                    <input type="date" name="tgl_jadwal" value="{{ $tgl_val }}" required
                        class="w-full p-4 bg-slate-50 border border-transparent focus:border-blue-500 font-bold text-slate-700 outline-none rounded-2xl transition-all">
                </div>

                <div class="w-full md:w-1/4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 ml-1">Status Pemberian</label>
                    <div class="relative">
                        <select name="status"
                            class="w-full p-4 bg-slate-50 border border-transparent focus:border-blue-500 font-bold text-slate-700 outline-none rounded-2xl transition-all appearance-none">
                            <option value="Belum" {{ $status_val == 'Belum' ? 'selected' : '' }}>Belum Selesai</option>
                            <option value="Selesai" {{ $status_val == 'Selesai' ? 'selected' : '' }}>Selesai (Sudah Vaksin)</option>
                        </select>
                        <div class="absolute right-4 top-5 text-slate-400 pointer-events-none text-xs">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-auto pt-4 md:pt-0">
                    <button type="button" onclick="confirmUpdate('{{ $index }}', '{{ addslashes($vaksin) }}')"
                        class="w-full md:w-auto px-8 py-4 bg-slate-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-slate-100 hover:bg-indigo-600 transition-all active:scale-95">
                        Update
                    </button>
                </div>
            </form>
        </div>
    @endforeach
</div>

<p class="mt-14 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
    Data ini akan langsung sinkron dengan aplikasi orang tua
</p>

<!-- Confirmation Modal -->
<div id="imunisasiModal"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
        id="modalContent">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Simpan Jadwal Imunisasi?</h3>
        <p class="text-slate-400 text-sm text-center leading-relaxed font-medium mb-8">
            Anda akan memperbarui rekam jadwal pemberian vaksin <span id="modalVaksinName" class="text-indigo-600 font-bold"></span> untuk anak ini.
        </p>
        <div class="flex gap-4">
            <button onclick="closeModal()"
                class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors">
                Batal
            </button>
            <button onclick="submitConfirmedForm()"
                class="flex-1 bg-indigo-600 text-white font-bold py-4 rounded-2xl hover:bg-indigo-700 transition-colors">
                Ya, Update
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentActiveFormId = null;

    function confirmUpdate(formIndex, namaVaksin) {
        const form = document.getElementById('form_imunisasi_' + formIndex);

        if (form.checkValidity()) {
            currentActiveFormId = 'form_imunisasi_' + formIndex;
            document.getElementById('modalVaksinName').innerText = namaVaksin;

            const modal = document.getElementById('imunisasiModal');
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
        const modal = document.getElementById('imunisasiModal');
        const content = document.getElementById('modalContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentActiveFormId = null;
        }, 300);
    }

    function submitConfirmedForm() {
        if (currentActiveFormId) {
            document.getElementById(currentActiveFormId).submit();
        }
    }
</script>
@endsection
