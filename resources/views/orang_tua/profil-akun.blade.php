@extends('layouts.app-ortua')

@section('title', 'Profil Akun - StuntGuard')

@section('content')
<header class="flex justify-between items-center mb-12">
    <div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Profil Akun</h1>
        <p class="text-slate-400 font-medium text-sm mt-1">Kelola data otentikasi login personal Anda.</p>
    </div>
    <a href="{{ route('orang-tua.dashboard') }}"
        class="px-6 py-3 bg-white border border-slate-100 rounded-2xl text-slate-600 font-bold text-xs hover:bg-slate-50 hover:text-slate-800 transition-all flex items-center gap-2 shadow-sm">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</header>

@if ($errors->any())
<div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold rounded-2xl">
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-[40px] p-12 border border-slate-100/60 shadow-sm max-w-2xl">
    <form action="{{ route('orang-tua.update-profil-akun') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap Orang Tua</label>
            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required
                class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 focus:bg-white outline-none font-bold text-slate-700 transition-all">
        </div>

        <div>
            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Username Kredensial</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 focus:bg-white outline-none font-bold text-slate-700 transition-all">
        </div>

        <div>
            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Password Baru (Opsional)</label>
            <input type="password" name="password"
                class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 focus:bg-white outline-none font-bold text-slate-700 transition-all"
                placeholder="Biarkan kosong jika tidak ingin mengubah password">
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-widest py-4 px-10 rounded-2xl shadow-lg shadow-indigo-100 transition-all active:scale-[0.98]">
                Simpan Profil
            </button>
        </div>
    </form>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center {{ session('pesan') == 'berhasil' || request()->query('pesan') == 'berhasil' ? 'flex' : 'hidden' }}">
    <div class="bg-white rounded-[40px] p-10 max-w-sm w-full mx-4 shadow-2xl border border-slate-50 text-center">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 mb-2">Profil Diperbarui</h3>
        <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
            Identitas akun dan otorisasi login berhasil dikompilasi ulang dan disinkronkan ke dalam server StuntGuard.
        </p>
        <button onclick="closeSuccessModal()"
            class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-slate-900/10">
            Selesai & Kembali
        </button>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center {{ session('pesan') == 'gagal' || request()->query('pesan') == 'gagal' ? 'flex' : 'hidden' }}">
    <div class="bg-white rounded-[40px] p-10 max-w-sm w-full mx-4 shadow-2xl border border-slate-50 text-center">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 mb-2">Gagal Mengubah</h3>
        <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
            Sistem gagal memproses dan menyimpan data otorisasi login profil digital Anda.
        </p>
        <button onclick="closeErrorModal()"
            class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
            Kembali & Perbaiki
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function closeSuccessModal() {
        document.getElementById('successModal').style.display = 'none';
        window.history.replaceState({}, document.title, "{{ route('orang-tua.profil-akun') }}");
    }

    function closeErrorModal() {
        document.getElementById('errorModal').style.display = 'none';
        window.history.replaceState({}, document.title, "{{ route('orang-tua.profil-akun') }}");
    }
</script>
@endsection
