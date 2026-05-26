<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    use HasFactory;

    protected $table = 'transaksi_masuk';

    protected $fillable = [
        'no_transaksi', 'barang_id', 'tanggal', 'periode',
        'jumlah', 'harga_satuan', 'sumber', 'no_dokumen', 'keterangan',
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
