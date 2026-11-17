@extends('layouts.app-kader')

@section('title', 'Profil Kader Professional | StuntGuard')

@section('content')
<div class="mb-10">
    <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Profil Kader</h1>
    <p class="text-slate-400 font-medium mt-2">Kelola informasi otorisasi dan identitas digital Anda.</p>
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

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <div class="lg:col-span-4 col-span-12">
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden relative">
            <div class="h-36 bg-gradient-to-r from-indigo-500 to-blue-600"></div>
            <div class="px-8 pb-10 text-center -mt-16">
                <div class="relative inline-block">
                    <div class="w-32 h-32 rounded-[35px] border-4 border-white shadow-xl overflow-hidden bg-slate-100 mx-auto">
                        @php
                            $foto = (!empty($user->foto_profil)) ? $user->foto_profil : 'default.png';
                        @endphp
                        <img id="avatarPreview" src="{{ asset('assets/img/' . $foto) }}"
                            class="w-full h-full object-cover">
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-800 mt-4 capitalize">{{ $user->nama }}</h3>
                <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mt-1">Kader Posyandu</p>
                <div class="mt-6 p-4 bg-slate-50 border border-slate-100/50 rounded-2xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Username Aktif</p>
                    <p class="text-sm font-bold text-slate-700 font-mono mt-1">@ {{$user->username}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-8 col-span-12">
        <div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-50">
            <h2 class="text-xl font-black text-slate-800 mb-8">Pembaruan Autentikasi</h2>

            <form action="{{ route('kader.update-profil') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Username Kredensial</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                        class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 focus:bg-white outline-none font-bold text-slate-700 transition-all">
                </div>

                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ubah Password Baru (Opsional)</label>
                    <input type="password" name="password"
                        class="w-full mt-2 p-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-indigo-500 focus:bg-white outline-none font-bold text-slate-700 transition-all"
                        placeholder="Ketikan password baru jika ingin mengubah">
                </div>

                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Unggah Media Foto Profil</label>
                    <div class="mt-2 border-2 border-dashed border-slate-100 rounded-3xl p-6 hover:bg-slate-50/50 transition-colors relative">
                        <input type="file" name="foto" id="fotoFile" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-center">
                            <i class="fas fa-cloud-arrow-up text-2xl text-slate-300 mb-2"></i>
                            <p class="text-xs font-bold text-slate-500">Pilih Berkas atau Seret Kesini</p>
                            <p class="text-[10px] text-slate-400 font-semibold mt-1">PNG, JPG, JPEG maks 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-widest py-4 px-10 rounded-2xl shadow-lg shadow-indigo-100 transition-all active:scale-[0.98]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] flex items-center justify-center {{ session('pesan') == 'berhasil' || request()->query('pesan') == 'berhasil' ? 'flex' : 'hidden' }}">
    <div class="bg-white rounded-[40px] p-10 max-w-sm w-full mx-4 shadow-2xl border border-slate-50 text-center">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-user-gear"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 mb-2">Profil Diperbarui</h3>
        <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-2">
            Informasi akun identitas mandiri dan berkas media visual Anda berhasil disinkronkan ke server StuntGuard.
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
            Sistem gagal mengamankan pembaharuan profil digital Anda ke server StuntGuard.
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
    // Preview selected image
    document.getElementById('fotoFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('avatarPreview').src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    function closeSuccessModal() {
        document.getElementById('successModal').style.display = 'none';
        window.history.replaceState({}, document.title, "{{ route('kader.profil') }}");
    }

    function closeErrorModal() {
        document.getElementById('errorModal').style.display = 'none';
        window.history.replaceState({}, document.title, "{{ route('kader.profil') }}");
    }
</script>
@endsection
