@extends('layouts.app-kader')

@section('title', 'Manajemen Akun | StuntGuard')

@section('content')
<div class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Manajemen Akun</h1>
        <p class="text-slate-400 font-medium mt-2">Kelola akses login orang tua masyarakat.</p>
    </div>
    <button onclick="openModal()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all flex items-center gap-2">
        <i class="fas fa-plus-circle"></i>
        Registrasi Akun Baru
    </button>
</div>

@if (session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold rounded-2xl flex items-center gap-2">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold rounded-2xl flex items-center gap-2">
    <i class="fas fa-exclamation-circle"></i>
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-50">
    <div class="flex px-8 pb-6 border-b border-slate-100 mb-6">
        <div class="w-1/3 text-[11px] font-black text-slate-400 uppercase tracking-widest">Nama Orang Tua</div>
        <div class="w-1/4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Username</div>
        <div class="w-1/4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Akses Anak</div>
        <div class="w-1/6 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</div>
    </div>

    <div class="space-y-4">
        @if ($users->isEmpty())
            <p class='text-center py-12 text-slate-400 font-bold uppercase text-xs tracking-widest'>Belum ada data warga</p>
        @else
            @foreach ($users as $row)
                <div class="flex items-center px-8 py-6 hover:bg-slate-50/70 rounded-[30px] transition-all border border-transparent hover:border-slate-100/80 group">
                    <div class="w-1/3 font-extrabold text-slate-800 capitalize">{{ $row->nama }}</div>
                    <div class="w-1/4 font-mono text-blue-500 text-sm font-semibold">@ {{$row->username}}</div>
                    <div class="w-1/4 text-center">
                        <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">
                            {{ $row->anak->nama_lengkap ?? 'Belum Dihubungkan' }}
                        </span>
                    </div>
                    <div class="w-1/6 flex justify-end gap-3">
                        <button type="button"
                            onclick="confirmDeleteAccount('{{ $row->id }}', '{{ addslashes($row->nama) }}')"
                            class="w-10 h-10 flex items-center justify-center rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Registration Modal -->
<div id="modalRegistrasi"
    class="{{ !empty($id_pilihan) ? 'flex' : 'hidden' }} fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] items-center justify-center p-4 opacity-100 transition-opacity duration-300">
    <div class="bg-white w-full max-w-lg rounded-[40px] p-10 shadow-2xl border border-slate-50 transform scale-100 transition-transform duration-300"
        id="regModalContent">
        <h2 class="text-2xl font-black text-slate-800 mb-8">Registrasi Akun Orang Tua</h2>

        <form action="{{ route('kader.user.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap Orang Tua</label>
                <input type="text" name="nama" value="{{ $nama_ortu_pilihan }}" required
                    class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white outline-none font-bold text-slate-700 transition-all"
                    placeholder="Masukkan nama lengkap">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Username</label>
                    <input type="text" name="username" required
                        class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white outline-none font-bold text-slate-700 transition-all"
                        placeholder="username_warga">
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white outline-none font-bold text-slate-700 transition-all"
                        placeholder="••••••••">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Hubungkan ke Anak</label>
                <div class="relative">
                    <select name="id_anak" required
                        class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white outline-none font-bold text-slate-700 transition-all appearance-none">
                        <option value="">-- Pilih Nama Anak --</option>
                        @foreach ($children as $a)
                            <option value="{{ $a->id }}" {{ $a->id == $id_pilihan ? 'selected' : '' }}>
                                {{ $a->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-5 top-7 text-slate-400 pointer-events-none text-xs">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 py-4 rounded-2xl font-bold uppercase text-xs tracking-widest transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold uppercase text-xs tracking-widest shadow-lg shadow-blue-100 transition-all">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteAccountModal"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
        id="deleteModalContent">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-user-times"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Cabut Hak Akses?</h3>
        <p class="text-slate-400 text-sm text-center leading-relaxed font-medium mb-8">
            Akun milik <span id="targetAccountName" class="text-rose-500 font-bold"></span> akan dihapus permanen dari sistem tata pamong posyandu.
        </p>
        <div class="flex gap-4">
            <button onclick="closeDeleteModal()"
                class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors">
                Kembali
            </button>
            <a id="executeDeleteAccountBtn" href="#"
                class="flex-1 bg-rose-500 text-white text-center font-bold py-4 rounded-2xl hover:bg-rose-600 transition-colors block">
                Ya, Hapus Akun
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal() {
        const modal = document.getElementById('modalRegistrasi');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('modalRegistrasi');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        window.history.replaceState({}, document.title, "{{ route('kader.user.index') }}");
    }

    function confirmDeleteAccount(id, nama) {
        const modal = document.getElementById('deleteAccountModal');
        const content = document.getElementById('deleteModalContent');
        const nameSpan = document.getElementById('targetAccountName');
        const deleteAction = document.getElementById('executeDeleteAccountBtn');

        nameSpan.innerText = nama;
        deleteAction.href = "{{ url('kader/pengguna') }}/" + id + "/hapus";

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteAccountModal');
        const content = document.getElementById('deleteModalContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
