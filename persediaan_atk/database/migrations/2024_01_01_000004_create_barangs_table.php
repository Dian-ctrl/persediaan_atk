<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama_barang', 150);
            $table->foreignId('kategori_id')->constrained('kategori_barangs')->restrictOnDelete();
            $table->foreignId('satuan_id')->constrained('satuans')->restrictOnDelete();
            $table->unsignedBigInteger('harga_satuan')->default(0);
            $table->integer('stok')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('kategori_id');
            $table->index('satuan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
