@extends('layouts.app-kader')

@section('title', 'Edit Data Balita - StuntGuard')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-5 mb-10">
        <button type="button" onclick="triggerCancelModal()"
            class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 text-slate-400 hover:text-blue-600 transition flex items-center justify-center w-12 h-12">
            <i class="fas fa-arrow-left text-sm"></i>
        </button>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit Identitas Anak</h1>
            <p class="text-slate-400 font-medium mt-1 text-sm">Pastikan NIK dan data lahir sesuai dengan Akta Kelahiran/KIA.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold rounded-2xl">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-50">
        <form id="editAnakForm" action="{{ route('kader.anak.update', $child->id) }}" method="POST" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK Anak</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <input type="text" name="nik_anak" value="{{ old('nik_anak', $child->nik_anak) }}"
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-bold text-slate-700 focus:border-blue-500 focus:bg-white transition-all"
                            required maxlength="16">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $child->nama_lengkap) }}"
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-bold text-slate-700 focus:border-blue-500 focus:bg-white transition-all"
                            required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tempat Lahir</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $child->tempat_lahir) }}"
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-bold text-slate-700 focus:border-blue-500 focus:bg-white transition-all"
                            required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $child->tanggal_lahir) }}"
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-bold text-slate-700 focus:border-blue-500 focus:bg-white transition-all"
                            required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                    <div class="relative">
                        <select name="jenis_kelamin"
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-bold text-slate-700 focus:border-blue-500 focus:bg-white transition-all appearance-none"
                            required>
                            <option value="L" {{ old('jenis_kelamin', $child->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $child->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <div class="absolute right-5 top-5 text-slate-400 pointer-events-none text-xs">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Ibu Kandung</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                            <i class="fas fa-female text-lg"></i>
                        </span>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $child->nama_ibu) }}"
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-bold text-slate-700 focus:border-blue-500 focus:bg-white transition-all"
                            required>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor WhatsApp Orang Tua</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </span>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $child->no_hp) }}"
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-bold text-slate-700 focus:border-blue-500 focus:bg-white transition-all"
                            placeholder="08..." required>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-50">
                <button type="button" onclick="triggerCancelModal()"
                    class="px-8 py-4 text-slate-400 font-bold hover:text-slate-600 transition uppercase text-xs tracking-widest">
                    Batal
                </button>
                <button type="button" onclick="triggerSubmitModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all active:scale-95 uppercase text-xs tracking-widest">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div id="submitModal"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
        id="submitModalContent">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Simpan Perubahan?</h3>
        <p class="text-slate-400 text-sm text-center leading-relaxed font-medium mb-8">
            Data rekam identitas balita terbaru akan disimpan ke dalam database pusat StuntGuard.
        </p>
        <div class="flex gap-4">
            <button onclick="closeSubmitModal()"
                class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors">
                Periksa Kembali
            </button>
            <button onclick="executeSubmitForm()"
                class="flex-1 bg-blue-600 text-white font-bold py-4 rounded-2xl hover:bg-blue-700 transition-colors">
                Ya, Simpan
            </button>
        </div>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<div id="cancelModal"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
        id="cancelModalContent">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Batalkan Pengeditan?</h3>
        <p class="text-slate-400 text-sm text-center leading-relaxed font-medium mb-8">
            Seluruh perubahan data yang baru saja Anda ketikkan pada form tidak akan diterapkan ke sistem.
        </p>
        <div class="flex gap-4">
            <button onclick="closeCancelModal()"
                class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors">
                Lanjut Edit
            </button>
            <a href="{{ route('kader.anak.index') }}"
                class="flex-1 bg-rose-500 text-white text-center font-bold py-4 rounded-2xl hover:bg-rose-600 transition-colors block">
                Keluar Form
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function triggerSubmitModal() {
        const form = document.getElementById('editAnakForm');
        if (form.checkValidity()) {
            const modal = document.getElementById('submitModal');
            const content = document.getElementById('submitModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        } else {
            form.reportValidity();
        }
    }

    function closeSubmitModal() {
        const modal = document.getElementById('submitModal');
        const content = document.getElementById('submitModalContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    function executeSubmitForm() {
        const form = document.getElementById('editAnakForm');
        form.submit();
    }

    function triggerCancelModal() {
        const modal = document.getElementById('cancelModal');
        const content = document.getElementById('cancelModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancelModal');
        const content = document.getElementById('cancelModalContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
</script>
@endsection
