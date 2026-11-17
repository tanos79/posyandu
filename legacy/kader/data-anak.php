<?php
session_start();
include '../koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kader') {
    header("location:../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Balita | StuntGuard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* SINKRONISASI TYPOGRAPHY DENGAN HALAMAN UTAMA STUNTGUARD */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>

<body class="flex">
    <?php include 'sidebar.php'; ?>

    <div class="flex-1 ml-72 p-12">

        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Data Balita</h1>
                <p class="text-slate-400 font-medium mt-2">Kelola informasi identitas anak dan orang tua di wilayah
                    Cirebon.</p>
            </div>
            <a href="tambah-anak.php"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Tambah Data Anak
            </a>
        </div>

        <div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-50 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-8 pb-6">Identitas Balita</th>
                        <th class="px-8 pb-6 text-center">Gender</th>
                        <th class="px-8 pb-6">Kontak Orang Tua</th>
                        <th class="px-8 pb-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM anak ORDER BY nama_lengkap ASC");
                    if (mysqli_num_rows($query) == 0) {
                        echo "<tr><td colspan='4' class='px-8 py-12 text-center text-slate-400 font-bold uppercase text-xs tracking-wider'>Belum ada data anak.</td></tr>";
                    }
                    while ($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr class="hover:bg-slate-50/70 transition-all group">
                            <td class="px-8 py-6">
                                <p class="font-extrabold text-slate-800 text-base capitalize"><?= $row['nama_lengkap']; ?>
                                </p>
                                <p class="text-[10px] text-slate-400 font-black tracking-wider uppercase mt-1">
                                    NIK: <?= (!empty($row['nik_anak'])) ? $row['nik_anak'] : 'DATA BELUM DIISI'; ?>
                                </p>
                            </td>

                            <td class="px-8 py-6 text-center">
                                <?php if (strtoupper($row['jenis_kelamin']) == 'L'): ?>
                                    <span
                                        class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Laki-Laki</span>
                                <?php else: ?>
                                    <span
                                        class="bg-pink-50 text-pink-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Perempuan</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-8 py-6">
                                <p class="text-sm font-bold text-slate-700 capitalize"><?= $row['nama_ibu']; ?></p>
                                <p class="text-[10px] text-blue-500 font-black tracking-widest uppercase mt-1">
                                    WA: <?= (!empty($row['no_hp'])) ? $row['no_hp'] : '-'; ?>
                                </p>
                            </td>

                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="data-pengguna.php?pilih_anak=<?= $row['id']; ?>&nama_ortu=<?= urlencode($row['nama_ibu']); ?>"
                                        class="w-11 h-11 flex items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                        title="Buat Akun Login Orang Tua">
                                        <i class="fas fa-user-plus text-xs"></i>
                                    </a>

                                    <a href="edit-anak.php?id=<?= $row['id']; ?>"
                                        class="w-11 h-11 flex items-center justify-center rounded-2xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                        title="Edit Data">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>

                                    <button type="button"
                                        onclick="confirmDelete('<?= $row['id']; ?>', '<?= addslashes($row['nama_lengkap']); ?>')"
                                        class="w-11 h-11 flex items-center justify-center rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                                        title="Hapus Data">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <p class="mt-8 text-[11px] text-slate-400 font-black uppercase tracking-[0.25em] text-center">
            TOTAL DATA: <?= mysqli_num_rows($query); ?> BALITA TERDAFTAR
        </p>
    </div>

    <div id="deleteModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border border-slate-50 transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div class="flex justify-center mb-6">
                <div
                    class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 text-center mb-2">Hapus Data Balita?</h3>
            <p class="text-slate-400 text-sm text-center leading-relaxed font-medium mb-8">
                Anda akan menghapus data balita bernama <span id="targetChildName"
                    class="text-rose-500 font-bold"></span>. Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex gap-4">
                <button onclick="closeDeleteModal()"
                    class="flex-1 bg-slate-100 text-slate-500 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-colors">
                    Batalkan
                </button>
                <a id="executeDeleteBtn" href="#"
                    class="flex-1 bg-rose-500 text-white text-center font-bold py-4 rounded-2xl hover:bg-rose-600 transition-colors block">
                    Ya, Hapus Data
                </a>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, nama) {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('modalContent');
            const nameSpan = document.getElementById('targetChildName');
            const deleteAction = document.getElementById('executeDeleteBtn');

            // Set nama anak dan link tujuan hapus asli
            nameSpan.innerText = nama;
            deleteAction.href = "proses-hapus-anak.php?id=" + id;

            // Tampilkan modal dengan efek transisi halus
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
</body>

</html>