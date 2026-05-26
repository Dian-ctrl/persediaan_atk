<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 30)->unique();
            $table->date('tanggal');
            $table->string('periode', 30);
            $table->foreignId('barang_id')->constrained('barangs')->restrictOnDelete();
            $table->integer('jumlah');
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedBigInteger('total');
            $table->string('penerima', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('tanggal');
            $table->index('barang_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluars');
    }
};
