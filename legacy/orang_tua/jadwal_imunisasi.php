<?php
session_start();
include '../koneksi.php';

// 1. PROTEKSI HALAMAN (Murni Bawaan Asli Anda)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] !== 'orang_tua') {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id'];

// 2. AMBIL ID ANAK DARI TABEL USERS (Sinkronisasi Database)
$query_user = mysqli_query($koneksi, "SELECT id_anak FROM users WHERE id = '$id_user'");
$data_user = mysqli_fetch_assoc($query_user);
$id_anak_linked = $data_user['id_anak'] ?? 0;

// 3. AMBIL DETAIL ANAK & HITUNG USIA
$query_anak = mysqli_query($koneksi, "SELECT nama_lengkap, tanggal_lahir FROM anak WHERE id = '$id_anak_linked'");
$data_anak = mysqli_fetch_assoc($query_anak);
 if (!$data_anak) {
    echo "<script>alert('Data anak belum ditautkan!'); window.location='dashboard_anak.php';</script>";
    exit;
}

$nama_anak = $data_anak['nama_lengkap'];  
$tgl_lahir = new DateTime($data_anak['tanggal_lahir']);
$sekarang = new DateTime();
$diff = $sekarang->diff($tgl_lahir);
$usia_bulan_skrg = ($diff->y * 12) + $diff->m;

// 4. DATA MASTER JADWAL (Murni Sesuai Bawaan Anda)
$master_jadwal = [
    ['usia' => 0, 'vaksin' => 'Hepatitis B (0 Bulan)', 'ket' => 'Mencegah kerusakan hati'],
    ['usia' => 1, 'vaksin' => 'BCG & Polio 1 (1 Bulan)', 'ket' => 'Mencegah TBC dan kelumpuhan'],
    ['usia' => 2, 'vaksin' => 'DPT-HB-Hib 1 & Polio 2 (2 Bulan)', 'ket' => 'Difteri, Batuk Rejan, Tetanus'],
    ['usia' => 3, 'vaksin' => 'DPT-HB-Hib 2 & Polio 3 (3 Bulan)', 'ket' => 'Perlindungan lanjutan dosis ke-2'],
    ['usia' => 4, 'vaksin' => 'DPT-HB-Hib 3 & Polio 4 (4 Bulan)', 'ket' => 'Perlindungan lengkap dosis dasar'],
    ['usia' => 9, 'vaksin' => 'Campak Rubella (9 Bulan)', 'ket' => 'Mencegah Campak dan Rubella'],
    ['usia' => 18, 'vaksin' => 'DPT-HB-Hib Lanjutan (18 Bulan)', 'ket' => 'Booster kekebalan jangka panjang'],
    ['usia' => 18, 'vaksin' => 'Campak Rubella Lanjutan (18 Bulan)', 'ket' => 'Booster Campak & Rubella'],
];

// 5. AMBIL DATA DARI DATABASE (Riwayat yang diisi oleh Kader)
$riwayat_db = [];
$query_riwayat = mysqli_query($koneksi, "SELECT * FROM imunisasi WHERE id_anak = '$id_anak_linked'");
while ($row = mysqli_fetch_assoc($query_riwayat)) {
    $riwayat_db[$row['jenis_vaksin']] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Imunisasi - StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>

<body class="flex min-h-screen text-slate-800">

    <aside class="w-72 bg-white border-r border-slate-100 flex flex-col fixed h-full shadow-sm z-20">
        <div class="p-8 flex items-center gap-3">
            <div class="bg-indigo-600 p-2.5 rounded-xl text-white shadow-lg shadow-indigo-100">
                <i class="fas fa-shield-heart text-xl"></i>
            </div>
            <span class="font-extrabold text-xl text-slate-800 tracking-tight">StuntGuard</span>
        </div>

        <nav class="flex-1 px-6 space-y-1.5">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-4 mb-4">Menu Utama</p>

            <a href="dashboard_anak.php"
                class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                <i class="fas fa-th-large text-lg"></i> Dashboard
            </a>

            <a href="profil_anak.php"
                class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                <i class="fas fa-child text-lg"></i> Profil Anak
            </a>

            <a href="jadwal_imunisasi.php"
                class="flex items-center gap-4 px-4 py-4 bg-indigo-50/60 text-indigo-600 rounded-2xl font-extrabold text-sm transition-all">
                <i class="fas fa-calendar-check text-lg"></i> Jadwal Imunisasi
            </a>

            <a href="nutrisi.php"
                class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                <i class="fas fa-apple-whole text-lg"></i> Nutrisi Harian
            </a>

            <div class="pt-6 mt-4 border-t border-slate-50">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-4 mb-4">Pengaturan Akun
                </p>
                <a href="profil_akun.php"
                    class="flex items-center gap-4 px-4 py-4 text-slate-400 hover:bg-slate-50/80 hover:text-slate-700 rounded-2xl font-bold text-sm transition-all">
                    <i class="fas fa-user-gear text-lg"></i> Profil Akun
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-slate-50">
            <a href="../logout.php"
                class="flex items-center gap-4 px-4 py-4 text-rose-500 hover:bg-rose-50 rounded-2xl font-extrabold text-sm transition-all">
                <i class="fas fa-sign-out-alt text-lg"></i> Keluar
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-72 p-12">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Jadwal Imunisasi</h1>
                <p class="text-slate-400 font-medium text-sm mt-1">Status vaksinasi untuk <span
                        class="text-indigo-600 font-extrabold"><?= htmlspecialchars($nama_anak); ?></span></p>
            </div>
            <div class="bg-indigo-600 px-8 py-4 rounded-[24px] text-white shadow-xl shadow-indigo-100 min-w-[140px]">
                <p class="text-[10px] font-extrabold uppercase opacity-75 tracking-widest text-center">Usia Saat Ini</p>
                <p class="text-xl font-black text-center mt-0.5"><?= $usia_bulan_skrg; ?> Bulan</p>
            </div>
        </header>

        <div class="w-full bg-white rounded-[40px] border border-slate-100/60 shadow-sm p-12 relative overflow-hidden">
            <div class="absolute left-[84px] top-24 bottom-24 w-1 bg-slate-100 rounded-full z-0"></div>

            <div class="space-y-8">
                <?php foreach ($master_jadwal as $j): ?>
                    <?php
                    // Ambil data dari database berdasarkan nama vaksin yang sama persis
                    $data_v = $riwayat_db[$j['vaksin']] ?? null;
                    $status_v = $data_v['status'] ?? 'Belum';
                    $tgl_v = isset($data_v['tgl_jadwal']) ? date('d M Y', strtotime($data_v['tgl_jadwal'])) : '-';
                    $keterangan_tambahan = $data_v['keterangan'] ?? 'Tidak ada catatan tambahan dari kader kesehatan.';

                    // Logika Visual Status bawaan asli Anda
                    if ($status_v == 'Selesai') {
                        $color = "bg-emerald-500 text-white shadow-emerald-100";
                        $border = "border-slate-100/70 hover:border-emerald-200 hover:shadow-emerald-50/40";
                        $badge = "Sudah Vaksin";
                        $b_color = "bg-emerald-50 text-emerald-600 border border-emerald-100";
                        $icon = "fa-check";
                    } elseif ($data_v != null) {
                        $color = "bg-indigo-600 text-white shadow-indigo-100 animate-pulse";
                        $border = "border-indigo-200 ring-4 ring-indigo-50/50 hover:shadow-indigo-50/40";
                        $badge = "Terjadwal: " . $tgl_v;
                        $b_color = "bg-indigo-600 text-white shadow-sm shadow-indigo-100";
                        $icon = "fa-syringe";
                    } else {
                        $color = "bg-slate-100 text-slate-300";
                        $border = "border-slate-100/70 opacity-60 hover:opacity-100 hover:border-slate-200";
                        $badge = "Belum Ada Jadwal";
                        $b_color = "bg-slate-50 text-slate-400 border border-slate-200/60";
                        $icon = "fa-clock";
                    }
                    ?>
                    <div class="flex items-center gap-10 relative z-10 cursor-pointer group"
                        onclick="openModal('<?= htmlspecialchars($j['vaksin']); ?>', '<?= $j['usia']; ?> Bulan', '<?= htmlspecialchars($j['ket']); ?>', '<?= $status_v; ?>', '<?= $tgl_v; ?>', '<?= htmlspecialchars($keterangan_tambahan); ?>')">

                        <div class="w-12 text-right flex-shrink-0">
                            <p
                                class="text-[10px] font-black text-slate-400 uppercase tracking-wider group-hover:text-indigo-600 transition-colors">
                                <?= $j['usia']; ?> Bln</p>
                        </div>

                        <div
                            class="w-12 h-12 rounded-full border-4 border-white flex items-center justify-center flex-shrink-0 transition-all shadow-md <?= $color; ?>">
                            <i class="fas <?= $icon; ?> text-sm"></i>
                        </div>

                        <div
                            class="flex-1 bg-white p-7 rounded-[28px] border transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4 <?= $border; ?>">
                            <div class="flex items-center gap-6">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-slate-50/80 flex items-center justify-center text-slate-400 text-lg group-hover:text-indigo-500 group-hover:bg-indigo-50/40 transition-all flex-shrink-0">
                                    <i class="fas fa-vial-virus"></i>
                                </div>
                                <div>
                                    <h3
                                        class="text-lg font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">
                                        <?= $j['vaksin']; ?></h3>
                                    <p class="text-xs text-slate-400 font-bold mt-0.5"><?= $j['ket']; ?></p>
                                    <?php if ($tgl_v && $status_v != 'Selesai' && $data_v != null): ?>
                                        <p
                                            class="text-[10px] mt-1.5 font-extrabold text-indigo-500 uppercase tracking-wider italic flex items-center gap-1">
                                            <i class="fas fa-calendar-day"></i> Rencana Kunjungan: <?= $tgl_v; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span
                                class="px-5 py-2 rounded-full text-[10px] font-extrabold uppercase tracking-widest sm:self-center self-start <?= $b_color; ?>">
                                <?= $badge; ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <div id="vaxModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div
            class="bg-white rounded-[36px] w-full max-w-lg p-10 border border-slate-100 shadow-2xl m-4 transform scale-95 transition-transform duration-300 relative">

            <button onclick="closeModal()"
                class="absolute top-6 right-6 w-10 h-10 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-full flex items-center justify-center transition-all">
                <i class="fas fa-times"></i>
            </button>

            <div class="flex items-center gap-4 mb-6">
                <div id="modalIconContainer"
                    class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-md">
                    <i id="modalIcon" class="fas"></i>
                </div>
                <div>
                    <span id="modalUsiaBadge"
                        class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1 rounded-md"></span>
                    <h2 id="modalTitle" class="text-xl font-black text-slate-800 tracking-tight mt-1.5">Detail Vaksinasi
                    </h2>
                </div>
            </div>

            <hr class="border-slate-100 my-6">

            <div class="space-y-5">
                <div>
                    <label
                        class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Deskripsi
                        & Manfaat Medis</label>
                    <p id="modalDesc" class="text-sm font-bold text-slate-700 leading-relaxed"></p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                        <label
                            class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Status
                            Imunisasi</label>
                        <span id="modalStatusBadge"
                            class="inline-block text-[10px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-full mt-1"></span>
                    </div>
                    <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                        <label
                            class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Tanggal
                            Pemberian</label>
                        <p id="modalDate" class="text-xs font-extrabold text-slate-800 mt-1"></p>
                    </div>
                </div>

                <div class="pt-2">
                    <label
                        class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Catatan
                        Medis Kader Kesehatan</label>
                    <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100/80 mt-1">
                        <p id="modalNotes" class="text-xs text-slate-500 font-medium italic leading-relaxed"></p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button onclick="closeModal()"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg transition-all">
                    Selesai & Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal(vaksin, usia, ket, status, tanggal, catatan) {
            const modal = document.getElementById('vaxModal');

            // Set Data Teks Konten
            document.getElementById('modalTitle').innerText = vaksin;
            document.getElementById('modalUsiaBadge').innerText = "Rekomendasi: " + usia;
            document.getElementById('modalDesc').innerText = ket;
            document.getElementById('modalDate').innerText = tanggal !== '' ? tanggal : '-';
            document.getElementById('modalNotes').innerText = catatan;

            const iconContainer = document.getElementById('modalIconContainer');
            const icon = document.getElementById('modalIcon');
            const statusBadge = document.getElementById('modalStatusBadge');

            // Reset Kelas Desain Statis Sebelumnya
            iconContainer.className = "w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-md ";
            icon.className = "fas ";
            statusBadge.className = "inline-block text-[10px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-full mt-1 ";

            // Kondisi Desain Menyesuaikan Status Imunisasi Realtime
            if (status === 'Selesai') {
                iconContainer.classList.add('bg-emerald-500', 'text-white');
                icon.classList.add('fa-check');
                statusBadge.classList.add('bg-emerald-50', 'text-emerald-600', 'border', 'border-emerald-200');
                statusBadge.innerText = "Sudah Vaksin";
            } else if (status === 'Belum' && tanggal !== '-') {
                iconContainer.classList.add('bg-indigo-600', 'text-white');
                icon.classList.add('fa-syringe');
                statusBadge.classList.add('bg-indigo-50', 'text-indigo-600', 'border', 'border-indigo-200');
                statusBadge.innerText = "Terjadwal";
            } else {
                iconContainer.classList.add('bg-slate-100', 'text-slate-400');
                icon.classList.add('fa-clock');
                statusBadge.classList.add('bg-slate-100', 'text-slate-400', 'border', 'border-slate-200');
                statusBadge.innerText = "Belum Ada Jadwal";
            }

            // Animasi Fade In Smooth
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('vaxModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Tutup Modal ketika area luar modal diklik
        window.onclick = function (event) {
            const modal = document.getElementById('vaxModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>