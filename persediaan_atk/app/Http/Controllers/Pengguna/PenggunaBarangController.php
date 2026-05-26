<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class PenggunaBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'satuan'])->where('aktif', true);

        if ($request->cari) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%'.$request->cari.'%')
                  ->orWhere('kode_barang', 'like', '%'.$request->cari.'%');
            });
        }
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->stok === 'habis') {
            $query->where('stok', 0);
        } elseif ($request->stok === 'ada') {
            $query->where('stok', '>', 0);
        }

        $data      = $query->orderBy('kategori_id')->orderBy('nama_barang')->paginate(25);
        $kategoris = KategoriBarang::orderBy('kode')->get();

        return view('pengguna.barang.index', compact('data', 'kategoris'));
    }
}
