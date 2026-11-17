<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use Illuminate\Http\Request;

class AnakController extends Controller
{
    /**
     * Display a listing of children.
     */
    public function index()
    {
        $children = Anak::orderBy('nama_lengkap', 'asc')->get();
        return view('kader.anak.index', compact('children'));
    }

    /**
     * Show the form for creating a new child.
     */
    public function create()
    {
        return view('kader.anak.create');
    }

    /**
     * Store a newly created child in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik_anak' => 'required|string|unique:anak,nik_anak|size:16',
            'nama_lengkap' => 'required|string|max:150',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ibu' => 'required|string|max:100',
            'berat_lahir' => 'nullable|numeric',
            'tinggi_lahir' => 'nullable|numeric',
            'no_hp' => 'required|string|max:15',
        ]);

        $child = Anak::create($request->all());

        if ($child) {
            return view('kader.anak.success-popup', [
                'insert_status' => 'sukses',
                'nama_lengkap' => $child->nama_lengkap,
            ]);
        }

        return view('kader.anak.success-popup', [
            'insert_status' => 'gagal',
            'error_message' => 'Gagal menulis data ke database.',
        ]);
    }

    /**
     * Show the form for editing the specified child.
     */
    public function edit($id)
    {
        $child = Anak::findOrFail($id);
        return view('kader.anak.edit', compact('child'));
    }

    /**
     * Update the specified child in database.
     */
    public function update(Request $request, $id)
    {
        $child = Anak::findOrFail($id);

        $request->validate([
            'nik_anak' => 'required|string|size:16|unique:anak,nik_anak,' . $child->id,
            'nama_lengkap' => 'required|string|max:150',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ibu' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
        ]);

        if ($child->update($request->all())) {
            return redirect()->route('kader.anak.index')->with('success', 'Berhasil! Data identitas balita telah diperbarui.');
        }

        return back()->with('error', 'Gagal memperbarui data.');
    }

    /**
     * Remove the specified child from database.
     */
    public function destroy($id)
    {
        $child = Anak::findOrFail($id);
        $child->delete();

        return redirect()->route('kader.anak.index')->with('success', 'Data balita berhasil dihapus.');
    }
}
