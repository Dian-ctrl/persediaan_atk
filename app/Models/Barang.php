<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang', 'nama_barang', 'kategori_id', 'satuan_id',
        'stok', 'harga_satuan', 'keterangan', 'aktif',
    ];

    protected $casts = [
        'aktif'        => 'boolean',
        'harga_satuan' => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class);
    }

    public function stokOpname()
    {
        return $this->hasMany(StokOpname::class);
    }

    public function getTotalNilaiAttribute(): float
    {
        return $this->stok * $this->harga_satuan;
    }
}
