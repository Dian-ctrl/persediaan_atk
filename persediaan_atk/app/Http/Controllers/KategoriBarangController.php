<?php
namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBarang::withCount('barang')->paginate(15);
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriBarang::withCount('barang')->paginate(15);
        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:kategori_barangs,kode',
            'nama_kategori' => 'required|max:100',
            'keterangan' => 'nullable|max:500',
        ]);

        KategoriBarang::create($request->only(['kode','nama_kategori','keterangan']));
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(KategoriBarang $kategori)
    {
        $kategoris = KategoriBarang::withCount('barang')->paginate(15);
        return view('kategori.edit', compact('kategori','kategoris'));
    }

    public function update(Request $request, KategoriBarang $kategori)
    {
        $request->validate([
            'kode' => 'required|unique:kategori_barangs,kode,'.$kategori->id,
            'nama_kategori' => 'required|max:100',
        ]);
        $kategori->update($request->only(['kode','nama_kategori','keterangan']));
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(KategoriBarang $kategori)
    {
        if ($kategori->barang()->count() > 0) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak bisa dihapus karena masih digunakan.');
        }
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
