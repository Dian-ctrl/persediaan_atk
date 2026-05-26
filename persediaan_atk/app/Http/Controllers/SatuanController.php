<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::withCount('barang')->orderBy('nama_satuan')->paginate(20);
        return view('satuan.index', compact('satuans'));
    }

    public function create()
    {
        return $this->index();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|max:50|unique:satuans,nama_satuan',
        ]);
        Satuan::create($request->only('nama_satuan'));
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function edit(Satuan $satuan)
    {
        $satuans = Satuan::withCount('barang')->orderBy('nama_satuan')->paginate(20);
        return view('satuan.edit', compact('satuan', 'satuans'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|max:50|unique:satuans,nama_satuan,' . $satuan->id,
        ]);
        $satuan->update($request->only('nama_satuan'));
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diupdate.');
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->barang()->count() > 0) {
            return redirect()->route('satuan.index')->with('error', 'Satuan tidak bisa dihapus karena masih digunakan oleh ' . $satuan->barang()->count() . ' barang.');
        }
        $satuan->delete();
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}
