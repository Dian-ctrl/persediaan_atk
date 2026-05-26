<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model {
    use HasFactory;
    protected $table = 'satuans';
    protected $fillable = ['nama_satuan'];
    public function barang() { return $this->hasMany(Barang::class, 'satuan_id'); }
    public function getBarangCountAttribute() { return $this->barang()->count(); }
}
