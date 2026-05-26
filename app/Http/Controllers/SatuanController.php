<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $data = Satuan::withCount('barang')->orderBy('nama')->get();
        return view('satuan.index', compact('data'));
    }

    public function create()
    {
        $data = Satuan::withCount('barang')->orderBy('nama')->get();
        return view('satuan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:satuan,nama',
        ], [
            'nama.required' => 'Nama satuan wajib diisi.',
            'nama.unique'   => 'Satuan sudah ada.',
        ]);

        Satuan::create(['nama' => ucfirst($request->nama)]);

        return redirect()->route('satuan.index')
                         ->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function edit(Satuan $satuan)
    {
        $data = Satuan::withCount('barang')->orderBy('nama')->get();
        return view('satuan.index', compact('data', 'satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:satuan,nama,'.$satuan->id,
        ]);

        $satuan->update(['nama' => ucfirst($request->nama)]);

        return redirect()->route('satuan.index')
                         ->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->barang()->count() > 0) {
            return redirect()->route('satuan.index')
                             ->with('error', 'Satuan tidak dapat dihapus karena masih digunakan barang.');
        }

        $satuan->delete();

        return redirect()->route('satuan.index')
                         ->with('success', 'Satuan berhasil dihapus.');
    }
}
