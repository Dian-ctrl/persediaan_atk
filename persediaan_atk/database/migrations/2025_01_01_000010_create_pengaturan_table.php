<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 100)->unique();
            $table->text('nilai')->nullable();
            $table->timestamps();
        });

        DB::table('pengaturan')->insert([
            ['kunci' => 'ttd_mengetahui_nama',    'nilai' => 'Nama Kepala Cabang',             'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'ttd_mengetahui_jabatan',  'nilai' => 'Kepala Kantor Cabang',           'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'ttd_menyetujui_nama',     'nilai' => 'Nama Kabid',                     'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'ttd_menyetujui_jabatan',  'nilai' => 'Kabid Pengendalian Operasional', 'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'ttd_membuat_nama',        'nilai' => 'Nama Pembuat',                   'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'ttd_membuat_jabatan',     'nilai' => 'Penata Operasional Cabang',      'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'nama_instansi',            'nilai' => 'BPJS Ketenagakerjaan',           'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'kota',                     'nilai' => 'Kudus',                          'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('pengaturan');
    }
};
