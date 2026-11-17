<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Pengukuran;
use App\Models\Imunisasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DateTime;

class OrangTuaController extends Controller
{
    /**
     * Helper to get the authenticated user's linked child.
     */
    private function getLinkedChild()
    {
        $user = Auth::user();
        if (!$user || !$user->id_anak) {
            return null;
        }
        return Anak::find($user->id_anak);
    }

    /**
     * Parent's dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $child = $this->getLinkedChild();

        $nama_anak = "Data Anak Tidak Ditemukan";
        $jk = "-";
        $berat_terakhir = "0.00";
        $tinggi_terakhir = "0.00";
        $status_stunting = "Terpantau";

        // Chart data variables
        $chartLabels = ['-'];
        $chartData = [0];

        if ($child) {
            $nama_anak = $child->nama_lengkap;
            $jk = ($child->jenis_kelamin === 'L') ? 'Laki-Laki' : 'Perempuan';

            // Ambil data pengukuran terakhir
            $latestMeasure = Pengukuran::where('anak_id', $child->id)
                ->orderBy('tgl_pengukuran', 'desc')
                ->first();

            if ($latestMeasure) {
                $berat_terakhir = $latestMeasure->berat_badan;
                $tinggi_terakhir = $latestMeasure->tinggi_badan;
                $status_stunting = $latestMeasure->status_stunting ?? "Normal";
            }

            // Ambil data 5 pengukuran terakhir untuk chart pertumbuhan
            $history = Pengukuran::where('anak_id', $child->id)
                ->orderBy('tgl_pengukuran', 'asc')
                ->take(5)
                ->get();

            if ($history->count() > 0) {
                $chartLabels = [];
                $chartData = [];
                foreach ($history as $h) {
                    $chartLabels[] = date('M', strtotime($h->tgl_pengukuran));
                    $chartData[] = floatval($h->berat_badan);
                }
            }
        }

        return view('orang_tua.dashboard', compact(
            'user',
            'child',
            'nama_anak',
            'jk',
            'berat_terakhir',
            'tinggi_terakhir',
            'status_stunting',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Child profile view.
     */
    public function profilAnak()
    {
        $child = $this->getLinkedChild();

        if (!$child) {
            return redirect()->route('orang-tua.dashboard')->with('error_alert', 'Data anak belum ditautkan ke akun Anda!');
        }

        return view('orang_tua.profil-anak', compact('child'));
    }

    /**
     * Immunization schedule.
     */
    public function jadwalImunisasi()
    {
        $child = $this->getLinkedChild();

        if (!$child) {
            return redirect()->route('orang-tua.dashboard')->with('error_alert', 'Data anak belum ditautkan!');
        }

        // Hitung Usia dalam Bulan
        $tgl_lahir = new DateTime($child->tanggal_lahir);
        $sekarang = new DateTime();
        $diff = $sekarang->diff($tgl_lahir);
        $usia_bulan_skrg = ($diff->y * 12) + $diff->m;

        // Data Master Jadwal Imunisasi
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

        // Ambil data jadwal dari database yang sudah diinput Kader
        $riwayat_raw = Imunisasi::where('id_anak', $child->id)->get();
        $riwayat_db = [];
        foreach ($riwayat_raw as $row) {
            $riwayat_db[$row->jenis_vaksin] = $row;
        }

        return view('orang_tua.jadwal-imunisasi', compact('child', 'usia_bulan_skrg', 'master_jadwal', 'riwayat_db'));
    }

    /**
     * Daily Nutrition guide.
     */
    public function nutrisi()
    {
        $child = $this->getLinkedChild();

        if (!$child) {
            return redirect()->route('orang-tua.dashboard')->with('error_alert', 'Data anak belum ditautkan!');
        }

        // Hitung Usia
        $tgl_lahir = new DateTime($child->tanggal_lahir);
        $sekarang = new DateTime();
        $usia_bulan = ($sekarang->diff($tgl_lahir)->y * 12) + $sekarang->diff($tgl_lahir)->m;

        $nama_panggilan = explode(' ', $child->nama_lengkap)[0];

        // Logika Konten Berdasarkan Usia
        if ($usia_bulan < 6) {
            $fase = "ASI Eksklusif";
            $pesan_fase = "Hanya ASI hingga usia 6 bulan.";
        } elseif ($usia_bulan >= 6 && $usia_bulan < 9) {
            $fase = "MPASI Tekstur Lumat";
            $pesan_fase = "Mulai perkenalan makanan halus.";
        } elseif ($usia_bulan >= 9 && $usia_bulan < 12) {
            $fase = "MPASI Tekstur Cincang";
            $pesan_fase = "Naikkan tekstur perlahan.";
        } else {
            $fase = "Makanan Keluarga";
            $pesan_fase = "Sudah bisa makan menu keluarga.";
        }

        return view('orang_tua.nutrisi', compact('child', 'usia_bulan', 'nama_panggilan', 'fase', 'pesan_fase'));
    }

    /**
     * Show account profile settings.
     */
    public function profilAkun()
    {
        $user = Auth::user();
        return view('orang_tua.profil-akun', compact('user'));
    }

    /**
     * Update account profile settings.
     */
    public function updateProfilAkun(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string',
        ]);

        $user->nama = $request->input('nama');
        $user->username = $request->input('username');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($user->save()) {
            // Update session data
            session(['nama' => $user->nama, 'username' => $user->username]);
            return redirect()->route('orang-tua.profil-akun')->with('pesan', 'berhasil');
        }

        return redirect()->route('orang-tua.profil-akun')->with('pesan', 'gagal');
    }
}
