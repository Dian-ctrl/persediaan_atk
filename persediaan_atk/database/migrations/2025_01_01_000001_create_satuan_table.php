<?php
// ============================================================
// FILE: database/migrations/2025_01_01_000001_create_satuan_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('satuan'); }
};
