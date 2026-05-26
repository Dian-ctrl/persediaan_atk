<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiMasuk extends Model {
    use HasFactory;
    protected $table = 'transaksi_masuks';
    protected $fillable = ['no_transaksi','tanggal','periode','barang_id','jumlah','harga_satuan','total','no_dokumen','keterangan'];
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
}
