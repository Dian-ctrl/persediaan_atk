<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20)->unique();
            $table->string('nama_barang', 200);
            $table->foreignId('kategori_id')->constrained('kategori_barang')->onDelete('restrict');
            $table->foreignId('satuan_id')->constrained('satuan')->onDelete('restrict');
            $table->integer('stok')->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->index(['kategori_id', 'nama_barang']);
        });
    }
    public function down(): void { Schema::dropIfExists('barang'); }
};
