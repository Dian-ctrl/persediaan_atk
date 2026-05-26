<?php
// =============================================
// DashboardController.php
// =============================================
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Satuan;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJenis = Barang::count();
        $totalStok = Barang::sum('stok');
        $nilaiPersediaan = Barang::selectRaw('SUM(stok * harga_satuan) as total')->value('total') ?? 0;
        $stokHabis = Barang::where('stok', 0)->count();
        $totalKategori = KategoriBarang::count();
        $totalSatuan = Satuan::count();

        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $txMasukBulan = TransaksiMasuk::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->count();
        $txKeluarBulan = TransaksiKeluar::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->count();

        $nilaiKategori = KategoriBarang::withCount('barang')
            ->withSum(['barang as total_nilai' => function($q) {
                $q->selectRaw('SUM(stok * harga_satuan)');
            }], 'stok')
            ->orderByDesc('total_nilai')
            ->get()
            ->map(function($k) {
                $k->total_nilai = \DB::table('barangs')
                    ->where('kategori_id', $k->id)
                    ->selectRaw('SUM(stok * harga_satuan) as total')
                    ->value('total') ?? 0;
                $k->jml_barang = $k->barang_count;
                return $k;
            });

        return view('dashboard.index', compact(
            'totalJenis','totalStok','nilaiPersediaan','stokHabis',
            'totalKategori','totalSatuan','txMasukBulan','txKeluarBulan','nilaiKategori'
        ));
    }
}
