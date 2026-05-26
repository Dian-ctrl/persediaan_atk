<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Carbon\Carbon;

class PenggunaDashboardController extends Controller
{
    public function index()
    {
        $totalBarang  = Barang::where('aktif', true)->count();
        $totalStok    = Barang::where('aktif', true)->sum('stok');
        $stokHabis    = Barang::where('stok', 0)->count();

        $txMasukTerakhir  = TransaksiMasuk::with('barang.satuan')
                                           ->latest('tanggal')->limit(5)->get();
        $txKeluarTerakhir = TransaksiKeluar::with('barang.satuan')
                                            ->latest('tanggal')->limit(5)->get();

        return view('pengguna.dashboard', compact(
            'totalBarang', 'totalStok', 'stokHabis',
            'txMasukTerakhir', 'txKeluarTerakhir'
        ));
    }
}
