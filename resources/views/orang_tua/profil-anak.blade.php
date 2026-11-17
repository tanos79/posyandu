@extends('layouts.app-ortua')

@section('title', 'Profil Anak - StuntGuard')

@section('content')
<header class="flex justify-between items-center mb-12">
    <div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Profil Si Kecil</h1>
        <p class="text-slate-400 font-medium text-sm mt-1">Informasi identitas lengkap berdasarkan data Posyandu.</p>
    </div>
    <a href="{{ route('orang-tua.dashboard') }}"
        class="px-6 py-3 bg-white border border-slate-100 rounded-2xl text-slate-600 font-bold text-xs hover:bg-slate-50 hover:text-slate-800 transition-all flex items-center gap-2 shadow-sm">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</header>

<div class="w-full bg-white rounded-[40px] border border-slate-100/60 shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-50 p-12 text-white flex items-center gap-10 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative">
            <div class="w-36 h-36 bg-white/20 backdrop-blur-xl rounded-[38px] flex items-center justify-center text-6xl border border-white/30 shadow-2xl">
                <i class="fas fa-baby text-indigo-600"></i>
            </div>
            <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-9 h-9 rounded-xl flex items-center justify-center border-4 border-white shadow-lg">
                <i class="fas fa-check text-xs"></i>
            </div>
        </div>
        <div>
            <h2 class="text-3xl font-black tracking-tight mb-3 text-slate-800">{{ $child->nama_lengkap }}</h2>
            <div class="flex flex-wrap items-center gap-3">
                <span class="bg-indigo-600 px-5 py-2 rounded-xl text-xs font-extrabold tracking-wider uppercase">
                    NIK: {{ $child->nik_anak }}
                </span>
                <span class="bg-emerald-500/20 text-emerald-700 px-5 py-2 rounded-xl text-xs font-extrabold tracking-wider uppercase border border-emerald-500/30">
                    Status: Terverifikasi
                </span>
            </div>
        </div>
    </div>

    <div class="p-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
        <div class="space-y-10">
            <div class="flex items-start gap-5">
                <div class="w-12 h-12 bg-indigo-50/60 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                    <i class="fas fa-map-marker-alt text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tempat Lahir</p>
                    <p class="text-base font-extrabold text-slate-800 tracking-tight">{{ $child->tempat_lahir ?: '-' }}</p>
                </div>
            </div>

            <div class="flex items-start gap-5">
                <div class="w-12 h-12 bg-indigo-50/60 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                    <i class="fas fa-calendar-day text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Lahir</p>
                    <p class="text-base font-extrabold text-slate-800 tracking-tight">{{ date('d F Y', strtotime($child->tanggal_lahir)) }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="flex items-start gap-5">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                    <i class="fas fa-venus-mars text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Jenis Kelamin</p>
                    <p class="text-base font-extrabold text-slate-800 tracking-tight">{{ $child->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</p>
                </div>
            </div>

            <div class="flex items-start gap-5">
                <div class="w-12 h-12 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                    <i class="fas fa-person-breastfeeding text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Nama Ibu Kandung</p>
                    <p class="text-base font-extrabold text-slate-800 tracking-tight">{{ $child->nama_ibu ?: '-' }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="flex items-start gap-5">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                    <i class="fas fa-weight-scale text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Berat Saat Lahir</p>
                    <p class="text-base font-extrabold text-slate-800 tracking-tight">
                        {{ $child->berat_lahir }} <span class="text-xs font-bold text-slate-400 uppercase">kg</span>
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-5">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                    <i class="fas fa-ruler-vertical text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tinggi Saat Lahir</p>
                    <p class="text-base font-extrabold text-slate-800 tracking-tight">
                        {{ $child->tinggi_lahir }} <span class="text-xs font-bold text-slate-400 uppercase">cm</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-16 mb-16 p-8 bg-slate-50/60 rounded-[28px] border border-slate-100/80 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-orange-500 shadow-sm flex-shrink-0">
                <i class="fas fa-phone-alt text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Nomor HP Terdaftar</p>
                <p class="text-xl font-black text-slate-800 tracking-tight">{{ $child->no_hp ?: 'Belum Ada' }}</p>
            </div>
        </div>
        <div class="text-center md:text-right">
            <p class="text-xs text-slate-400 font-medium italic">*Data ini disinkronkan langsung dari sistem database pusat Posyandu.</p>
        </div>
    </div>

    <div class="px-16 py-8 bg-amber-50/60 border-t border-amber-100/80 flex items-center gap-4">
        <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-amber-100">
            <i class="fas fa-exclamation-triangle text-sm"></i>
        </div>
        <p class="text-xs text-amber-800 font-bold leading-relaxed">
            Menemukan kesalahan data? <span class="text-slate-500 font-medium">Demi keamanan medis, perubahan data identitas hanya dapat dilakukan melalui petugas kesehatan atau Kader Posyandu saat sesi penimbangan.</span>
        </p>
    </div>
</div>
@endsection
