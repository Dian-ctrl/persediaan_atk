<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transaksi_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 30)->unique();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->date('tanggal');
            $table->string('periode', 20);
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->string('penerima', 150)->nullable();
            $table->string('no_dokumen', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->index(['tanggal', 'barang_id', 'periode']);
        });
    }
    public function down(): void { Schema::dropIfExists('transaksi_keluar'); }
};
