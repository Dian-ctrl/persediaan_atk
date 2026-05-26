<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Satuan;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang  = Barang::count();
        $totalStok    = Barang::sum('stok');
        $totalNilai   = Barang::selectRaw('SUM(stok * harga_satuan) as total')->value('total') ?? 0;
        $stokHabis    = Barang::where('stok', 0)->count();
        $totalKategori = KategoriBarang::count();
        $totalSatuan   = Satuan::count();

        $txMasukBulanIni  = TransaksiMasuk::whereMonth('tanggal', Carbon::now()->month)
                                           ->whereYear('tanggal', Carbon::now()->year)
                                           ->count();
        $txKeluarBulanIni = TransaksiKeluar::whereMonth('tanggal', Carbon::now()->month)
                                            ->whereYear('tanggal', Carbon::now()->year)
                                            ->count();

        $nilaiPerKategori = KategoriBarang::withCount('barang')
            ->withSum('barang', 'stok')
            ->selectRaw('kategori_barang.*, SUM(barang.stok * barang.harga_satuan) as total_nilai, COUNT(barang.id) as jumlah_barang')
            ->leftJoin('barang', 'barang.kategori_id', '=', 'kategori_barang.id')
            ->groupBy('kategori_barang.id', 'kategori_barang.kode', 'kategori_barang.nama', 'kategori_barang.keterangan', 'kategori_barang.created_at', 'kategori_barang.updated_at')
            ->orderByDesc('total_nilai')
            ->get();

        $barangStokRendah = Barang::with(['kategori', 'satuan'])
            ->where('stok', '<=', 5)
            ->orderBy('stok')
            ->limit(20)
            ->get();

        $txMasukTerakhir  = TransaksiMasuk::with('barang.satuan')
            ->latest('tanggal')->limit(5)->get();
        $txKeluarTerakhir = TransaksiKeluar::with('barang.satuan')
            ->latest('tanggal')->limit(5)->get();

        return view('dashboard', compact(
            'totalBarang', 'totalStok', 'totalNilai', 'stokHabis',
            'totalKategori', 'totalSatuan',
            'txMasukBulanIni', 'txKeluarBulanIni',
            'nilaiPerKategori', 'barangStokRendah',
            'txMasukTerakhir', 'txKeluarTerakhir'
        ));
    }
}
