<?php
// app/Models/KategoriBarang.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriBarang extends Model {
    use HasFactory;
    protected $table = 'kategori_barangs';
    protected $fillable = ['kode', 'nama_kategori', 'keterangan'];
    public function barang() { return $this->hasMany(Barang::class, 'kategori_id'); }
    public function getBarangCountAttribute() { return $this->barang()->count(); }
}
