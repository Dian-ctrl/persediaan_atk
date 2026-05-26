<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiKeluar extends Model {
    use HasFactory;
    protected $table = 'transaksi_keluars';
    protected $fillable = ['no_transaksi','tanggal','periode','barang_id','jumlah','harga_satuan','total','penerima','keterangan'];
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id'); }
}
