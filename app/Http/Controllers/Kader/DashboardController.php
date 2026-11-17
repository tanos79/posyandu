<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Pengukuran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the Kader dashboard.
     */
    public function index()
    {
        $bulan_ini = date('m');
        $tahun_ini = date('Y');

        // Total Balita
        $total_anak = Anak::count();

        // Total Stunting (ambil status terakhir dari tiap anak)
        // SQL: SELECT COUNT(*) FROM (SELECT status_stunting FROM pengukuran WHERE id IN (SELECT MAX(id) FROM pengukuran GROUP BY anak_id) AND status_stunting = 'stunted') as sub
        $subQuery = Pengukuran::select('status_stunting')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('pengukuran')
                    ->groupBy('anak_id');
            });
        
        $total_stunting = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery->getQuery())
            ->where('status_stunting', 'stunted')
            ->count();

        // Total Jemput Bola (anak yang belum ada data ukur di bulan ini)
        $total_jemput = Anak::whereNotIn('id', function ($query) use ($bulan_ini, $tahun_ini) {
            $query->select('anak_id')
                ->from('pengukuran')
                ->whereMonth('tgl_pengukuran', $bulan_ini)
                ->whereYear('tgl_pengukuran', $tahun_ini);
        })->count();

        // Input Masuk Bulan Ini
        $total_ukur = Pengukuran::whereMonth('tgl_pengukuran', $bulan_ini)
            ->whereYear('tgl_pengukuran', $tahun_ini)
            ->count();

        // Logika Grafik Tren 6 Bulan
        $labels = [];
        $data_grafik = [];

        for ($i = 5; $i >= 0; $i--) {
            $tgl_target = date('Y-m-01', strtotime("-$i month"));
            $m = date('m', strtotime($tgl_target));
            $y = date('Y', strtotime($tgl_target));
            $nama_bulan = date('M', strtotime($tgl_target));

            $count = Pengukuran::whereMonth('tgl_pengukuran', $m)
                ->whereYear('tgl_pengukuran', $y)
                ->count();

            $labels[] = $nama_bulan;
            $data_grafik[] = $count;
        }

        return view('kader.dashboard', compact(
            'total_anak',
            'total_stunting',
            'total_jemput',
            'total_ukur',
            'labels',
            'data_grafik'
        ));
    }

    /**
     * Show Jemput Bola (follow-up list).
     */
    public function jemputBola()
    {
        $bulan_ini = date('m');
        $tahun_ini = date('Y');
        $nama_bulan = date('F');

        // Balita yang belum melakukan timbang rutin di bulan ini
        $children = Anak::whereNotIn('id', function ($query) use ($bulan_ini, $tahun_ini) {
            $query->select('anak_id')
                ->from('pengukuran')
                ->whereMonth('tgl_pengukuran', $bulan_ini)
                ->whereYear('tgl_pengukuran', $tahun_ini);
        })->orderBy('nama_lengkap', 'asc')->get();

        return view('kader.jemput-bola', compact('children', 'nama_bulan'));
    }

    /**
     * Show Kader profile edit page.
     */
    public function profil()
    {
        $user = Auth::user();
        return view('kader.profil', compact('user'));
    }

    /**
     * Update Kader profile.
     */
    public function updateProfil(Request $request)
    {
        $user = User::find(Auth::id());
        
        $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->username = $request->input('username');

        // Upload foto profil
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Pindahkan ke public/assets/img/
            $file->move(public_path('assets/img'), $filename);

            // Hapus foto lama jika bukan default.png
            if ($user->foto_profil && $user->foto_profil !== 'default.png') {
                $oldPath = public_path('assets/img/' . $user->foto_profil);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $user->foto_profil = $filename;
        }

        // Update password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($user->save()) {
            // Update session data
            session(['username' => $user->username]);
            return redirect()->route('kader.profil')->with('pesan', 'berhasil');
        }

        return redirect()->route('kader.profil')->with('pesan', 'gagal');
    }
}
