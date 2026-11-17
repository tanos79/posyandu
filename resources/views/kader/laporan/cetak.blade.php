<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan - StuntGuard</title>
    @if ($format == 'pdf')
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #ffffff;
                color: #1e293b;
                padding: 40px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 24px;
            }

            th, td {
                border: 1px solid #cbd5e1;
                padding: 12px 10px;
                text-align: left;
                font-size: 12px;
            }

            th {
                background-color: #f8fafc !important;
                font-weight: 800;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            td {
                font-weight: 500;
            }

            .header h2 {
                font-weight: 800;
                letter-spacing: -0.025em;
                color: #0f172a;
            }

            @media print {
                .no-print {
                    display: none !important;
                }

                body {
                    padding: 0px;
                }
            }
        </style>
    @else
        <style>
            body {
                font-family: sans-serif;
                padding: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
                font-size: 12px;
            }

            th {
                background-color: #f2f2f2;
            }

            .header {
                text-align: center;
                margin-bottom: 30px;
            }
        </style>
    @endif
</head>
<body {!! $format == 'pdf' ? 'onload="handlePdfLoad()"' : '' !!}>

    <div class="header" style="text-align: center; margin-bottom: 35px;">
        <h2 style="margin:0; font-size: 22px;">LAPORAN BULANAN PERKEMBANGAN BALITA</h2>
        <h3 style="margin:6px 0; font-size: 16px; color: #475569; font-weight: 700;">POSYANDU CIREBON - STUNTGUARD</h3>
        <p style="margin: 4px 0; font-size: 14px; color: #64748b; font-weight: 600;">Periode: {{ $nama_bulan }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Balita</th>
                <th>NIK</th>
                <th>Tgl Ukur</th>
                <th>BB (kg)</th>
                <th>TB (cm)</th>
                <th>Vit A</th>
                <th>Imunisasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($records as $d)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td style="font-weight: 700; color: #0f172a;">{{ $d->anak->nama_lengkap }}</td>
                    <td>'{{ $d->anak->nik_anak }}</td>
                    <td>{{ date('d/m/Y', strtotime($d->tgl_pengukuran)) }}</td>
                    <td>{{ $d->berat_badan }}</td>
                    <td>{{ $d->tinggi_badan }}</td>
                    <td>{{ $d->vitamin_a }}</td>
                    <td>{{ $d->imunisasi }}</td>
                    <td style="font-weight: 800;">{{ strtoupper($d->status_stunting) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data pengukuran di periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 60px; float: right; text-align: center; font-size: 14px; line-height: 1.6;">
        <p>Cirebon, {{ date('d F Y') }}</p>
        <p>Mengetahui,</p>
        <br><br><br>
        <p><b>( {{ Auth::check() ? Auth::user()->nama : 'Kader Kesehatan' }} )</b></p>
    </div>

    @if ($format == 'pdf')
        <div id="printWidget"
            class="no-print fixed bottom-6 right-6 bg-white/80 backdrop-blur-md border border-slate-200/80 p-4 rounded-3xl shadow-2xl flex items-center gap-3 transition-all duration-300 transform translate-y-20 opacity-0">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-sm">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="text-left pr-2">
                <p class="text-xs font-extrabold text-slate-800">Dokumen Siap</p>
                <p class="text-[10px] font-semibold text-slate-400">Gunakan dialog printer untuk menyimpan</p>
            </div>
            <button onclick="window.print()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-3 px-4 rounded-2xl shadow-lg shadow-indigo-100 transition-all flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak Ulang
            </button>
        </div>

        <script>
            function handlePdfLoad() {
                window.print();

                const widget = document.getElementById('printWidget');
                setTimeout(() => {
                    widget.classList.remove('translate-y-20', 'opacity-0');
                }, 1000);
            }
        </script>
    @endif

</body>
</html>
