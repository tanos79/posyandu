<?php
include '../koneksi.php';

// Cek apakah ada kiriman bulan/tahun dari form, jika tidak gunakan bulan sekarang
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$nama_bulan_ind = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];

$nama_file = "Laporan_Posyandu_" . $nama_bulan_ind[$bulan] . "_$tahun.xls";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$nama_file");
?>

<style>
    .title-laporan {
        font-family: 'Plus Jakarta Sans', 'Calibri', sans-serif;
        font-size: 16pt;
        font-weight: bold;
        color: #1e293b;
    }

    .subtitle-laporan {
        font-family: 'Plus Jakarta Sans', 'Calibri', sans-serif;
        font-size: 12pt;
        font-weight: 500;
        color: #64748b;
    }

    table {
        font-family: 'Plus Jakarta Sans', 'Calibri', sans-serif;
        font-size: 11pt;
        border-collapse: collapse;
    }

    th {
        font-family: 'Plus Jakarta Sans', 'Calibri', sans-serif;
        font-weight: bold;
        text-transform: uppercase;
    }

    td {
        font-family: 'Plus Jakarta Sans', 'Calibri', sans-serif;
    }
</style>

<center>
    <div class="title-laporan">LAPORAN BULANAN POSYANDU</div>
    <div class="subtitle-laporan">Periode: <?= $nama_bulan_ind[$bulan]; ?> <?= $tahun; ?></div>
    <br>
</center>

<table border="1">
    <thead>
        <tr style="background-color: #2563eb; color: white; height: 35px;">
            <th style="padding: 5px;">No</th>
            <th style="padding: 5px;">Nama Balita</th>
            <th style="padding: 5px;">NIK Anak</th>
            <th style="padding: 5px;">Tgl Ukur</th>
            <th style="padding: 5px;">BB (kg)</th>
            <th style="padding: 5px;">TB (cm)</th>
            <th style="padding: 5px;">Vitamin A</th>
            <th style="padding: 5px;">Imunisasi</th>
            <th style="padding: 5px;">Status Gizi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        // MEMPERBAIKI TYPO 'p.p.tgl_pengukuran' MENJADI 'p.tgl_pengukuran' AGAR FUNGSI AMAN
        $sql = mysqli_query($koneksi, "SELECT p.*, a.nama_lengkap, a.nik_anak 
                                       FROM pengukuran p 
                                       JOIN anak a ON p.anak_id = a.id 
                                       WHERE MONTH(p.tgl_pengukuran) = '$bulan' 
                                       AND YEAR(p.tgl_pengukuran) = '$tahun'
                                       ORDER BY p.tgl_pengukuran ASC");

        if (mysqli_num_rows($sql) == 0) {
            echo "<tr><td colspan='9' align='center' style='padding: 10px; font-style: italic; color: #94a3b8;'>Tidak ada data pada periode ini</td></tr>";
        }

        while ($d = mysqli_fetch_array($sql)) {
            ?>
            <tr style="height: 25px;">
                <td align="center" style="padding: 5px;"><?= $no++; ?></td>
                <td style="padding: 5px; text-transform: capitalize;"><?= $d['nama_lengkap']; ?></td>
                <td style="padding: 5px; mso-number-format:'\@';">'<?= $d['nik_anak']; ?></td>
                <td align="center" style="padding: 5px;"><?= date('d-m-Y', strtotime($d['tgl_pengukuran'])); ?></td>
                <td align="center" style="padding: 5px;"><?= $d['berat_badan']; ?></td>
                <td align="center" style="padding: 5px;"><?= $d['tinggi_badan']; ?></td>
                <td align="center" style="padding: 5px;"><?= $d['vitamin_a']; ?></td>
                <td style="padding: 5px;"><?= $d['imunisasi']; ?></td>
                <td align="center" style="padding: 5px; font-weight: bold;"><?= strtoupper($d['status_stunting']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>