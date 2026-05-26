<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $data = KategoriBarang::withCount('barang')->orderBy('kode')->get();
        return view('kategori.index', compact('data'));
    }

    public function create()
    {
        return view('kategori.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:kategori_barang,kode',
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ], [
            'kode.required' => 'Kode kategori wajib diisi.',
            'kode.unique'   => 'Kode kategori sudah digunakan.',
            'nama.required' => 'Nama kategori wajib diisi.',
        ]);

        KategoriBarang::create($request->only('kode', 'nama', 'keterangan'));

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(KategoriBarang $kategori)
    {
        return view('kategori.form', compact('kategori'));
    }

    public function update(Request $request, KategoriBarang $kategori)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:kategori_barang,kode,'.$kategori->id,
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $kategori->update($request->only('kode', 'nama', 'keterangan'));

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriBarang $kategori)
    {
        if ($kategori->barang()->count() > 0) {
            return redirect()->route('kategori.index')
                             ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki barang.');
        }

        $kategori->delete();

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}
