@extends('layouts.app-ortua')

@section('title', 'Nutrisi Harian - StuntGuard')

@section('content')
<header class="flex justify-between items-end mb-12">
    <div>
        <h1 class="text-3xl sm:text-4xl font-extrabold mb-1.5 text-slate-900 tracking-tight">Halo Bunda! 👋</h1>
        <h2 class="text-2xl sm:text-3xl font-black text-indigo-600 tracking-tight">Nutrisi {{ $nama_panggilan }}</h2>
        <p class="text-slate-400 font-medium text-sm mt-1">Panduan gizi cerdas untuk usia <b class="text-slate-700 font-extrabold">{{ $usia_bulan }} Bulan</b>.</p>
    </div>
    <div class="text-right hidden lg:block">
        <span class="inline-block bg-emerald-50 text-emerald-600 border border-emerald-100 px-4 py-2 rounded-full text-[10px] font-extrabold uppercase tracking-widest">● Database Terhubung</span>
        <p class="text-xs font-bold text-slate-400 italic mt-2.5">"Gizi tepat, anak sehat!"</p>
    </div>
</header>

<div class="grid grid-cols-12 gap-8">
    <div class="col-span-12 lg:col-span-8 bg-slate-900 rounded-[40px] p-12 text-white relative overflow-hidden shadow-xl">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-8">
                <span class="bg-indigo-600 px-4 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-wider">Target: {{ $usia_bulan }} Bulan</span>
                <span class="bg-white/10 px-4 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-sm border border-white/5">Fase: {{ $fase }}</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black mb-3 leading-tight tracking-tight">Porsi Ideal <br>Usia {{ $usia_bulan }} Bulan 🍽️</h2>
            <p class="text-indigo-200/90 text-sm font-bold italic mb-10">{{ $pesan_fase }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div onclick="openNutriModal('Protein Hewani', '🥚', 'Sangat disarankan memberikan protein hewani seperti telur, ayam, ikan, atau daging sapi setiap hari untuk merangsang pertumbuhan linear tulang si kecil dan mencegah stunting.', '2 Sendok Makan Penuh')"
                    class="bg-white/10 p-6 rounded-[24px] backdrop-blur-md border border-white/10 text-center cursor-pointer hover:bg-white/[0.15] transition-all group">
                    <p class="text-3xl mb-2 group-hover:scale-110 transition-transform">🥚</p>
                    <p class="font-extrabold text-sm tracking-tight">Protein</p>
                    <p class="text-[10px] text-indigo-200/60 font-medium mt-0.5">2 Sdm Penuh</p>
                </div>
                <div onclick="openNutriModal('Karbohidrat Dasar', '🍚', 'Sebagai sumber energi utama untuk menunjang aktivitas fisik dan perkembangan motorik kasar si kecil. Pastikan dimasak dengan kelembutan yang sesuai.', '3 - 4 Kali Sehari')"
                    class="bg-white/10 p-6 rounded-[24px] backdrop-blur-md border border-white/10 text-center cursor-pointer hover:bg-white/[0.15] transition-all group">
                    <p class="text-3xl mb-2 group-hover:scale-110 transition-transform">🍚</p>
                    <p class="font-extrabold text-sm tracking-tight">Karbo</p>
                    <p class="text-[10px] text-indigo-200/60 font-medium mt-0.5">3-4 Kali Sehari</p>
                </div>
                <div onclick="openNutriModal('Lemak Tambahan', '🥥', 'Lemak tambahan seperti santan murni, mentega, atau minyak kelapa sangat penting sebagai booster kalori demi pencapaian berat badan anak yang optimal.', 'Santan murni / Minyak sehat')"
                    class="bg-white/10 p-6 rounded-[24px] backdrop-blur-md border border-white/10 text-center cursor-pointer hover:bg-white/[0.15] transition-all group">
                    <p class="text-3xl mb-2 group-hover:scale-110 transition-transform">🥥</p>
                    <p class="font-extrabold text-sm tracking-tight">Lemak</p>
                    <p class="text-[10px] text-indigo-200/60 font-medium mt-0.5">Santan/Minyak</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4 flex flex-col gap-6">
        <div class="bg-orange-50/70 p-8 rounded-[36px] border border-orange-100/70 flex-1 hover:translate-y-[-4px] hover:shadow-xl transition-all">
            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-orange-500 shadow-sm mb-6 border border-orange-100/30">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h3 class="text-lg font-black text-orange-900 tracking-tight mb-2">Tips Usia {{ $usia_bulan }} Bln</h3>
            <p class="text-xs text-orange-700/80 font-bold leading-relaxed">Berikan protein hewani setiap hari secara bervariasi untuk membantu mencegah gejala stunting secara optimal.</p>
        </div>
        <div class="bg-indigo-600 p-8 rounded-[36px] text-white flex-1 relative overflow-hidden shadow-lg shadow-indigo-50 hover:translate-y-[-4px] hover:shadow-xl transition-all">
            <h3 class="text-lg font-extrabold mb-1.5 tracking-tight">Lapor Gizi 📈</h3>
            <p class="text-xs text-indigo-100/80 leading-relaxed font-bold">Berdasarkan sinkronisasi berkala database Posyandu, {{ $nama_panggilan }} berada di dalam fase usia pertumbuhan emas.</p>
            <div class="absolute -right-4 -bottom-4 text-8xl opacity-10"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>

    <div class="col-span-12 mt-6">
        <h3 class="text-xl font-black text-slate-900 mb-6 tracking-tight">Timeline Makan Si Kecil ⏰</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div onclick="openNutriModal('Sarapan Pagi', '🌅', 'Waktu sarapan pagi ideal untuk mengisi energi awal anak. Berikan porsi menu utama yang kaya nutrisi hewani dan mudah dicerna.', 'Jam 08:00 WIB')"
                class="bg-white p-7 rounded-[28px] border border-slate-100 cursor-pointer group hover:translate-y-[-4px] hover:shadow-xl transition-all">
                <div class="w-10 h-10 bg-amber-50 text-amber-500 border border-amber-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-amber-500 group-hover:text-white transition-all">
                    <i class="fas fa-sun"></i>
                </div>
                <p class="text-amber-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">08:00</p>
                <p class="text-base font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">Sarapan Pagi</p>
            </div>

            <div onclick="openNutriModal('Makan Siang', '☀️', 'Menu utama siang hari harus seimbang dan memenuhi standar gizi makro (karbohidrat, lemak, protein) untuk mengiringi masa aktifnya.', 'Jam 12:00 WIB')"
                class="bg-white p-7 rounded-[28px] border border-slate-100 cursor-pointer group hover:translate-y-[-4px] hover:shadow-xl transition-all">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i class="fas fa-utensils"></i>
                </div>
                <p class="text-indigo-600 text-[10px] font-extrabold uppercase tracking-widest mb-1">12:00</p>
                <p class="text-base font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">Makan Siang</p>
            </div>

            <div onclick="openNutriModal('Makanan Selingan', '🍪', 'Makanan selingan atau camilan sehat sore bisa berupa buah lumat, biskuit bayi, atau bubur buah murni untuk menambah kalori harian.', 'Jam 15:00 WIB')"
                class="bg-white p-7 rounded-[28px] border border-slate-100 cursor-pointer group hover:translate-y-[-4px] hover:shadow-xl transition-all">
                <div class="w-10 h-10 bg-rose-50 text-rose-500 border border-rose-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-rose-500 group-hover:text-white transition-all">
                    <i class="fas fa-cookie-bite"></i>
                </div>
                <p class="text-rose-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">15:00</p>
                <p class="text-base font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">Selingan</p>
            </div>

            <div onclick="openNutriModal('Makan Malam', '🌙', 'Makan malam sebaiknya diberikan tidak terlalu dekat dengan waktu tidur malam anak, agar penyerapan nutrisi berjalan maksimal tanpa mengganggu sistem pencernaan.', 'Jam 18:00 WIB')"
                class="bg-white p-7 rounded-[28px] border border-slate-100 cursor-pointer group hover:translate-y-[-4px] hover:shadow-xl transition-all">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-500 border border-emerald-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <i class="fas fa-moon"></i>
                </div>
                <p class="text-emerald-500 text-[10px] font-extrabold uppercase tracking-widest mb-1">18:00</p>
                <p class="text-base font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">Makan Malam</p>
            </div>
        </div>
    </div>
</div>

<footer class="mt-16 p-8 bg-slate-50/80 rounded-[28px] border border-slate-100/70 flex items-center gap-5">
    <div class="w-12 h-12 bg-white border border-slate-100 rounded-xl shadow-sm flex items-center justify-center text-indigo-600 flex-shrink-0">
        <i class="fas fa-info-circle text-lg"></i>
    </div>
    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">
        <span class="text-slate-800 font-extrabold">Akurasi Sinkronisasi:</span> Perhitungan usia otomatis berdasarkan tanggal lahir database posyandu: {{ date('d F Y', strtotime($child->tanggal_lahir)) }}
    </p>
</footer>

<!-- Nutrition Detail Modal -->
<div id="nutriModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[32px] w-full max-w-md p-10 border border-slate-100 shadow-2xl m-4 transform scale-95 transition-transform duration-300 relative">
        <button onclick="closeNutriModal()"
            class="absolute top-6 right-6 w-10 h-10 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-full flex items-center justify-center transition-all">
            <i class="fas fa-times"></i>
        </button>

        <div class="text-center mt-3">
            <div id="modalEmoji" class="text-5xl mb-4">🍽️</div>
            <h3 id="modalTitle" class="text-xl font-black text-slate-900 tracking-tight">Detail Nutrisi</h3>
            <span id="modalTargetLabel"
                class="inline-block mt-2 bg-indigo-50 text-indigo-600 border border-indigo-100 px-4 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest"></span>
        </div>

        <hr class="border-slate-100 my-6">

        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Panduan Gizi & Edukasi</label>
                <p id="modalDesc" class="text-xs sm:text-sm text-slate-600 font-bold leading-relaxed text-justify bg-slate-50/80 p-4 border border-slate-100 rounded-2xl">
                </p>
            </div>
        </div>

        <div class="mt-8">
            <button onclick="closeNutriModal()"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs uppercase tracking-widest py-4 rounded-xl shadow-md transition-all">
                Selesai & Mengerti
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openNutriModal(title, emoji, desc, target) {
        const modal = document.getElementById('nutriModal');

        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalEmoji').innerText = emoji;
        document.getElementById('modalDesc').innerText = desc;
        document.getElementById('modalTargetLabel').innerText = "Anjuran: " + target;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-95');
        }, 10);
    }

    function closeNutriModal() {
        const modal = document.getElementById('nutriModal');
        modal.classList.add('opacity-0');
        modal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    window.onclick = function (event) {
        const modal = document.getElementById('nutriModal');
        if (event.target == modal) {
            closeNutriModal();
        }
    }
</script>
@endsection
