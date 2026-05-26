<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model {
    use HasFactory;
    protected $table = 'barangs';
    protected $fillable = ['kode','nama_barang','kategori_id','satuan_id','harga_satuan','stok','keterangan'];

    public function kategori() { return $this->belongsTo(KategoriBarang::class, 'kategori_id'); }
    public function satuan() { return $this->belongsTo(Satuan::class, 'satuan_id'); }
    public function transaksiMasuk() { return $this->hasMany(TransaksiMasuk::class, 'barang_id'); }
    public function transaksiKeluar() { return $this->hasMany(TransaksiKeluar::class, 'barang_id'); }
}
