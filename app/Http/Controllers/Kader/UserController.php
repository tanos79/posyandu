<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of user accounts.
     */
    public function index(Request $request)
    {
        $id_pilihan = $request->query('pilih_anak', '');
        $nama_ortu_pilihan = $request->query('nama_ortu', '');

        // Fetch user accounts where role is 'orang_tua'
        $users = User::with('anak')
            ->where('role', 'orang_tua')
            ->orderBy('id', 'desc')
            ->get();

        // Fetch all children for association select dropdown
        $children = Anak::orderBy('nama_lengkap', 'asc')->get();

        return view('kader.user.index', compact('users', 'children', 'id_pilihan', 'nama_ortu_pilihan'));
    }

    /**
     * Store a newly created user account in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string',
            'id_anak' => 'required|exists:anak,id',
        ]);

        $username = $request->input('username');
        $email = $username . '@stuntguard.com';

        $user = User::create([
            'nama' => $request->input('nama'),
            'username' => $username,
            'password' => Hash::make($request->input('password')),
            'email' => $email,
            'id_anak' => $request->input('id_anak'),
            'role' => 'orang_tua',
        ]);

        if ($user) {
            return view('kader.user.success-popup', [
                'insert_status' => 'sukses',
                'nama' => $user->nama,
            ]);
        }

        return view('kader.user.success-popup', [
            'insert_status' => 'gagal',
            'error_message' => 'Gagal menulis data user.',
        ]);
    }

    /**
     * Remove the specified user account.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Safety: only delete orang_tua accounts from this panel
        if ($user->role === 'orang_tua') {
            $user->delete();
            return redirect()->route('kader.user.index')->with('success', 'Akun berhasil dihapus.');
        }

        return redirect()->route('kader.user.index')->with('error', 'Tidak berwenang menghapus akun ini.');
    }
}
