<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKeluar extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keluar';

    protected $fillable = [
        'no_transaksi', 'barang_id', 'tanggal', 'periode',
        'jumlah', 'harga_satuan', 'penerima', 'no_dokumen', 'keterangan',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'harga_satuan' => 'decimal:2',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
