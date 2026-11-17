<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Pengukuran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Show reports generator page.
     */
    public function index()
    {
        return view('kader.laporan.index');
    }

    /**
     * Generate and print report.
     */
    public function cetak(Request $request)
    {
        $request->validate([
            'bulan' => 'required|string|size:2',
            'tahun' => 'required|string|size:4',
            'format' => 'required|in:excel,pdf',
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $format = $request->input('format');

        $nama_bulan_arr = [
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

        $nama_bulan = $nama_bulan_arr[$bulan] ?? 'Bulan';

        // Fetch pengukuran records with child details
        $records = Pengukuran::with('anak')
            ->whereMonth('tgl_pengukuran', $bulan)
            ->whereYear('tgl_pengukuran', $tahun)
            ->get();

        if ($format === 'excel') {
            // Excel output headers
            $headers = [
                'Content-type' => 'application/vnd-ms-excel',
                'Content-Disposition' => "attachment; filename=Laporan_StuntGuard_{$nama_bulan}.xls"
            ];
            
            return response()->view('kader.laporan.cetak', compact('records', 'bulan', 'tahun', 'nama_bulan', 'format'), 200, $headers);
        }

        return view('kader.laporan.cetak', compact('records', 'bulan', 'tahun', 'nama_bulan', 'format'));
    }
}
