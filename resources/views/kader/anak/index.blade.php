@extends('layouts.app-kader')

@section('title', 'Data Balita | StuntGuard')

@section('content')
<div class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Data Balita</h1>
        <p class="text-slate-400 font-medium mt-2">Kelola informasi identitas anak dan orang tua di wilayah Cirebon.</p>
    </div>
    <a href="{{ route('kader.anak.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all flex items-center gap-2">
        <i class="fas fa-plus-circle"></i>
        Tambah Data Anak
    </a>
</div>

@if (session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold rounded-2xl flex items-center gap-2">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-50 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                <th class="px-8 pb-6">Identitas Balita</th>
                <th class="px-8 pb-6 text-center">Gender</th>
                <th class="px-8 pb-6">Kontak Orang Tua</th>
                <th class="px-8 pb-6 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @if ($children->isEmpty())
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold uppercase text-xs tracking-wider">Belum ada data anak.</td>
                </tr>
            @else
                @foreach ($children as $row)
                    <tr class="hover:bg-slate-50/70 transition-all group">
                        <td class="px-8 py-6">
                            <p class="font-extrabold text-slate-800 text-base capitalize">{{ $row->nama_lengkap }}</p>
                            <p class="text-[10px] text-slate-400 font-black tracking-wider uppercase mt-1">
                                NIK: {{ !empty($row->nik_anak) ? $row->nik_anak : 'DATA BELUM DIISI' }}
                            </p>
                        </td>

                        <td class="px-8 py-6 text-center">
                            @if (strtoupper($row->jenis_kelamin) == 'L')
                                <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Laki-Laki</span>
                            @else
                                <span class="bg-pink-50 text-pink-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Perempuan</span>
                            @endif
                        </td>

                        <td class="px-8 py-6">
                            <p class="text-sm font-bold text-slate-700 capitalize">{{ $row->nama_ibu }}</p>
                            <p class="text-[10px] text-blue-500 font-black tracking-widest uppercase mt-1">
                                WA: {{ !empty($row->no_hp) ? $row->no_hp : '-' }}
                            </p>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('kader.user.index', ['pilih_anak' => $row->id, 'nama_ortu' => $row->nama_ibu]) }}"
                                    class="w-11 h-11 flex items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                    title="Buat Akun Login Orang Tua">
                                    <i class="fas fa-user-plus text-xs"></i>
                                </a>

                                <a href="{{ route('kader.anak.edit', $row->id) }}"
                                    class="w-11 h-11 flex items-center justify-center rounded-2xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                    title="Edit Data">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                <button type="button"
                                    onclick="confirmDelete('{{ $row->id }}', '{{ addslashes($row->nama_lengkap) }}')"
                                    class="w-11 h-11 flex items-center justify-center rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                                    title="Hapus Data">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<p class="mt-8 text-[11px] text-slate-400 font-black uppercase tracking-[0.25em] text-center">
    TOTAL DATA: {{ $children->count() }} BALITA TERDAFTAR
</p>

<!-- Modal Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300" id="modalContent">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Hapus Data Balita?</h3>
        <p class="text-slate-400 text-sm text-center leading-relaxed font-medium mb-8">
            Anda akan menghapus data balita bernama <span id="targetChildName" class="text-rose-500 font-bold"></span>. Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex gap-4">
            <button onclick="closeDeleteModal()" class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors">
                Batalkan
            </button>
            <a id="executeDeleteBtn" href="#" class="flex-1 bg-rose-500 text-white text-center font-bold py-4 rounded-2xl hover:bg-rose-600 transition-colors block">
                Ya, Hapus Data
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(id, nama) {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        const nameSpan = document.getElementById('targetChildName');
        const deleteAction = document.getElementById('executeDeleteBtn');

        nameSpan.innerText = nama;
        deleteAction.href = "{{ url('kader/anak') }}/" + id + "/hapus";

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
