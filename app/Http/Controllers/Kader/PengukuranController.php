<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Pengukuran;
use App\Models\Imunisasi;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengukuranController extends Controller
{
    /**
     * Show list of children for monitoring.
     */
    public function index()
    {
        // Eloquent eager loading to get the latest measurement of each child
        $children = Anak::with(['pengukuran' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->orderBy('nama_lengkap', 'asc')->get();

        return view('kader.pengukuran.index', compact('children'));
    }

    /**
     * Show form to add measurement for a child.
     */
    public function create($id)
    {
        $child = Anak::findOrFail($id);
        return view('kader.pengukuran.create', compact('child'));
    }

    /**
     * Store measurement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anak_id' => 'required|exists:anak,id',
            'tgl_pengukuran' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'lingkar_kepala' => 'nullable|numeric',
            'catatan' => 'nullable|string',
            'vitamin_a' => 'required|in:Sudah,Belum',
            'imunisasi' => 'nullable|string',
        ]);

        $kader_id = Auth::id();
        $tinggi_badan = $request->input('tinggi_badan');

        // Logika penentuan status stunting
        $status_stunting = ($tinggi_badan < 60) ? 'stunted' : 'normal';

        $pengukuran = Pengukuran::create([
            'anak_id' => $request->input('anak_id'),
            'kader_id' => $kader_id,
            'tgl_pengukuran' => $request->input('tgl_pengukuran'),
            'berat_badan' => $request->input('berat_badan'),
            'tinggi_badan' => $tinggi_badan,
            'lingkar_kepala' => $request->input('lingkar_kepala'),
            'catatan' => $request->input('catatan'),
            'status_stunting' => $status_stunting,
            'vitamin_a' => $request->input('vitamin_a'),
            'imunisasi' => $request->input('imunisasi') ?: '-',
        ]);

        if ($pengukuran) {
            return view('kader.pengukuran.success-popup', [
                'insert_status' => 'sukses',
            ]);
        }

        return view('kader.pengukuran.success-popup', [
            'insert_status' => 'gagal',
            'error_message' => 'Gagal menulis data pengukuran.',
        ]);
    }

    /**
     * Show form to schedule immunization.
     */
    public function createImunisasi($id)
    {
        $child = Anak::findOrFail($id);
        return view('kader.pengukuran.imunisasi-create', compact('child'));
    }

    /**
     * Store or update scheduled immunization.
     */
    public function storeImunisasi(Request $request)
    {
        $request->validate([
            'id_anak' => 'required|exists:anak,id',
            'jenis_vaksin' => 'required|string|max:100',
            'tgl_jadwal' => 'required|date',
            'status' => 'required|in:Belum,Selesai',
        ]);

        $imunisasi = Imunisasi::updateOrCreate(
            [
                'id_anak' => $request->input('id_anak'),
                'jenis_vaksin' => $request->input('jenis_vaksin'),
            ],
            [
                'tgl_jadwal' => $request->input('tgl_jadwal'),
                'status' => $request->input('status'),
            ]
        );

        if ($imunisasi) {
            return redirect()->route('kader.imunisasi.create', $request->input('id_anak'))
                ->with('success', 'Jadwal ' . $imunisasi->jenis_vaksin . ' berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal memperbarui jadwal.');
    }

    /**
     * Send notification for upcoming immunization.
     */
    public function kirimNotif($id_anak)
    {
        $anak = Anak::findOrFail($id_anak);

        // Cari akun user jika ada
        $user = User::where('id_anak', $id_anak)->first();
        $nama_ortu = $user ? $user->nama : $anak->nama_ibu;
        $no_hp = $user && $user->no_hp ? $user->no_hp : $anak->no_hp;

        if (empty($no_hp)) {
            return back()->with('error_alert', 'Nomor HP tidak ditemukan! Pastikan data anak/orang tua sudah diisi nomor WA.');
        }

        // Ambil jadwal imunisasi terdekat yang "Belum"
        $imu = Imunisasi::where('id_anak', $id_anak)
            ->where('status', 'Belum')
            ->orderBy('tgl_jadwal', 'asc')
            ->first();

        if (!$imu) {
            return back()->with('error_alert', 'Jadwal imunisasi belum diatur untuk anak ini.');
        }

        $tgl_indo = date('d-m-Y', strtotime($imu->tgl_jadwal));
        $pesan_teks = "Halo Bunda " . $nama_ortu . ", kami dari Posyandu ingin mengingatkan bahwa Ananda " . $anak->nama_lengkap . " memiliki jadwal imunisasi *" . $imu->jenis_vaksin . "* pada tanggal *" . $tgl_indo . "*. Ditunggu kehadirannya ya Bun!";

        // Simpan notifikasi ke database jika akun user terdaftar
        if ($user) {
            Notifikasi::create([
                'id_user' => $user->id,
                'pesan' => $pesan_teks,
                'is_read' => 0
            ]);
        }

        // Format No HP ke standard internasional
        $no_hp_formatted = preg_replace('/[^0-9]/', '', $no_hp);
        if (str_starts_with($no_hp_formatted, '0')) {
            $no_hp_formatted = '62' . substr($no_hp_formatted, 1);
        }

        $link_wa = "https://api.whatsapp.com/send?phone=" . $no_hp_formatted . "&text=" . rawurlencode($pesan_teks);

        return view('kader.pengukuran.notif-preview', compact('pesan_teks', 'link_wa'));
    }
}
