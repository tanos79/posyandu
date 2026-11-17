<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'kader') {
    header("location:../login.php");
    exit;
}

$id_anak = mysqli_real_escape_string($koneksi, $_GET['id']);
$query_anak = mysqli_query($koneksi, "SELECT * FROM anak WHERE id = '$id_anak'");
$data_anak = mysqli_fetch_assoc($query_anak);

// --- POP-UP KUSTOM JIKA DATA ANAK TIDAK DITEMUKAN ---
if (!$data_anak) {
    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kesalahan Sistem - StuntGuard</title>
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

    <body class="bg-slate-900/40 backdrop-blur-sm min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-[40px] p-10 max-w-sm w-full shadow-2xl border border-slate-50 text-center">
            <div class="flex justify-center mb-6">
                <div
                    class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Data Tidak Ditemukan</h3>
            <p class="text-slate-400 text-xs font-medium mb-8 leading-relaxed px-4">
                Maaf, berkas rekam medis digital anak yang Anda cari tidak terdaftar atau telah dihapus dari pangkalan data.
            </p>
            <button onclick="window.location.href='data-anak.php'"
                class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl transition-colors text-sm shadow-lg shadow-rose-100">
                Kembali ke Daftar Anak
            </button>
        </div>
    </body>

    </html>
    <?php
    exit;
}

// --- LOGIKA AMBIL DATA UNTUK GRAFIK (ASLI BAWAAN ANDA) ---
$labels = [];
$berat_data = [];
$tinggi_data = [];

$chart_query = mysqli_query($koneksi, "SELECT tgl_pengukuran, berat_badan, tinggi_badan FROM pengukuran WHERE anak_id = '$id_anak' ORDER BY tgl_pengukuran ASC");

while ($row = mysqli_fetch_assoc($chart_query)) {
    $labels[] = date('d M Y', strtotime($row['tgl_pengukuran']));
    $berat_data[] = $row['berat_badan'];
    $tinggi_data[] = $row['tinggi_badan'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Pertumbuhan - <?= htmlspecialchars($data_anak['nama_lengkap']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>

<body class="text-slate-800 min-h-screen py-10 px-4 sm:px-6 lg:px-8">

    <div class="max-w-5xl mx-auto bg-white rounded-[32px] shadow-sm border border-slate-100 p-6 sm:p-10">

        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-8 mb-8">
            <div class="flex flex-col gap-2">
                <a href="data-anak.php"
                    class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-slate-800 transition-colors group">
                    <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali ke Data
                    Anak
                </a>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight mt-1">
                    <?= htmlspecialchars($data_anak['nama_lengkap']); ?>
                </h2>
                <div
                    class="inline-flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl w-max border border-slate-100">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">NIK</span>
                    <span
                        class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($data_anak['nik_anak']); ?></span>
                </div>
            </div>
            <div
                class="sm:text-right flex flex-row sm:flex-col items-center sm:items-end gap-3 sm:gap-1 bg-indigo-50/50 sm:bg-transparent p-4 sm:p-0 rounded-2xl w-full sm:w-auto">
                <div
                    class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-md sm:hidden">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <p class="text-sm font-extrabold text-slate-800">Tren Pertumbuhan</p>
                    <p class="text-[11px] font-medium text-slate-400">Visualisasi data berkala antropometri</p>
                </div>
            </div>
        </div>

        <div class="relative w-full bg-slate-50/50 rounded-2xl p-4 border border-slate-100/80 mb-10 h-[350px]">
            <canvas id="growthChart"></canvas>
        </div>

        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center text-sm">
                <i class="fas fa-history"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800">Tabel Riwayat Pengukuran</h3>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800 text-white text-xs tracking-wider font-bold">
                        <th class="p-4 text-center rounded-tl-2xl">Tgl Periksa</th>
                        <th class="p-4 text-center">Berat (kg)</th>
                        <th class="p-4 text-center">Tinggi (cm)</th>
                        <th class="p-4 text-center rounded-tr-2xl">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-600">
                    <?php
                    // Query data tabel terbalik (Terbaru ke Terlama) murni bawaan skrip asli Anda
                    $sql_table = mysqli_query($koneksi, "SELECT * FROM pengukuran WHERE anak_id = '$id_anak' ORDER BY tgl_pengukuran DESC");

                    if (mysqli_num_rows($sql_table) == 0): ?>
                        <tr>
                            <td colspan="4" class="p-8 text-center text-xs text-slate-400 font-medium bg-slate-50/30">
                                <i class="fas fa-inbox text-lg mb-2 block text-slate-300"></i> Belum ada rekaman log data
                                pengukuran untuk balita ini.
                            </td>
                        </tr>
                    <?php
                    endif;

                    while ($t = mysqli_fetch_assoc($sql_table)) {
                        if ($t['status_stunting'] == 'stunted') {
                            $badge_style = "bg-rose-50 text-rose-600 border border-rose-100";
                        } else {
                            $badge_style = "bg-emerald-50 text-emerald-600 border border-emerald-100";
                        }
                        ?>
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="p-4 text-center text-slate-500 font-normal">
                                <?= date('d/m/Y', strtotime($t['tgl_pengukuran'])); ?></td>
                            <td class="p-4 text-center font-bold text-slate-800"><?= $t['berat_badan']; ?> <span
                                    class="text-[10px] text-slate-400 font-normal">kg</span></td>
                            <td class="p-4 text-center font-bold text-slate-800"><?= $t['tinggi_badan']; ?> <span
                                    class="text-[10px] text-slate-400 font-normal">cm</span></td>
                            <td class="p-4 text-center">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider <?= $badge_style ?>">
                                    <?= htmlspecialchars($t['status_stunting']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('growthChart').getContext('2d');
        const growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Berat Badan (kg)',
                    data: <?php echo json_encode($berat_data); ?>,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.04)',
                    borderWidth: 3,
                    pointBackgroundColor: '#10B981',
                    pointHoverRadius: 6,
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y',
                }, {
                    label: 'Tinggi Badan (cm)',
                    data: <?php echo json_encode($tinggi_data); ?>,
                    borderColor: '#6366F1',
                    backgroundColor: 'rgba(99, 102, 241, 0.04)',
                    borderWidth: 3,
                    pointBackgroundColor: '#6366F1',
                    pointHoverRadius: 6,
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
                            boxWidth: 12,
                            boxHeight: 12,
                            borderRadius: 4
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Berat (kg)', font: { family: 'Plus Jakarta Sans', weight: '700', size: 11 } },
                        ticks: { font: { family: 'Plus Jakarta Sans' } }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'Tinggi (cm)', font: { family: 'Plus Jakarta Sans', weight: '700', size: 11 } },
                        ticks: { font: { family: 'Plus Jakarta Sans' } }
                    }
                }
            }
        });
    </script>

</body>

</html>