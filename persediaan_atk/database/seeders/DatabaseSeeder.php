<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── USERS ──────────────────────────────────────────
        DB::table('users')->insert([
            ['name' => 'Administrator', 'email' => 'admin@desamedini.go.id', 'password' => Hash::make('admin123'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Staff Desa', 'email' => 'staff@desamedini.go.id', 'password' => Hash::make('staff123'), 'role' => 'staff', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── KATEGORI ───────────────────────────────────────
        $kategoris = [
            ['kode' => 'ATK/01', 'nama_kategori' => 'Alat Tulis Kantor', 'keterangan' => 'Perlengkapan ATK operasional kantor'],
            ['kode' => 'BC/02',  'nama_kategori' => 'Barang Cetakan',     'keterangan' => 'Formulir, brosur, spanduk, dan cetakan Balai Desa Medini'],
            ['kode' => 'CON/03', 'nama_kategori' => 'Consumable',         'keterangan' => 'Kertas HVS, tinta printer, dan bahan habis pakai IT'],
            ['kode' => 'MET/04', 'nama_kategori' => 'Meterai',            'keterangan' => 'Meterai Rp 10.000'],
            ['kode' => 'KBR/05', 'nama_kategori' => 'Kebersihan',         'keterangan' => 'Peralatan dan bahan kebersihan kantor'],
        ];
        foreach ($kategoris as $k) {
            DB::table('kategori_barangs')->insert(array_merge($k, ['created_at' => now(), 'updated_at' => now()]));
        }

        // ── SATUAN ─────────────────────────────────────────
        $satuans = ['Buah','Rim','Dos','Pak','Box','Botol','Lembar','Buku','Set','Bendel','Rol','Liter','Kg','Lusin'];
        foreach ($satuans as $s) {
            DB::table('satuans')->insert(['nama_satuan' => $s, 'created_at' => now(), 'updated_at' => now()]);
        }

        // ── BARANG ─────────────────────────────────────────
        $kat = DB::table('kategori_barangs')->pluck('id', 'kode');
        $sat = DB::table('satuans')->pluck('id', 'nama_satuan');

        $barangs = [
            // ATK
            ['kode'=>'ATK001','nama'=>'Pulpen Ballpoint Hitam','kat'=>'ATK/01','sat'=>'Dos','harga'=>28000,'stok'=>15],
            ['kode'=>'ATK002','nama'=>'Pensil 2B','kat'=>'ATK/01','sat'=>'Dos','harga'=>18000,'stok'=>8],
            ['kode'=>'ATK003','nama'=>'Penghapus Staedtler','kat'=>'ATK/01','sat'=>'Buah','harga'=>5000,'stok'=>25],
            ['kode'=>'ATK004','nama'=>'Penggaris Besi 30cm','kat'=>'ATK/01','sat'=>'Buah','harga'=>15000,'stok'=>10],
            ['kode'=>'ATK005','nama'=>'Stabilo Boss','kat'=>'ATK/01','sat'=>'Buah','harga'=>8500,'stok'=>30],
            ['kode'=>'ATK006','nama'=>'Spidol Whiteboard Hitam','kat'=>'ATK/01','sat'=>'Dos','harga'=>45000,'stok'=>5],
            ['kode'=>'ATK007','nama'=>'Spidol Permanent','kat'=>'ATK/01','sat'=>'Dos','harga'=>35000,'stok'=>4],
            ['kode'=>'ATK008','nama'=>'Correction Fluid (Tip-Ex)','kat'=>'ATK/01','sat'=>'Buah','harga'=>7000,'stok'=>20],
            ['kode'=>'ATK009','nama'=>'Lem Kertas Fox','kat'=>'ATK/01','sat'=>'Buah','harga'=>5500,'stok'=>12],
            ['kode'=>'ATK010','nama'=>'Double Tape','kat'=>'ATK/01','sat'=>'Buah','harga'=>8000,'stok'=>18],
            ['kode'=>'ATK011','nama'=>'Cutter Besar','kat'=>'ATK/01','sat'=>'Buah','harga'=>12000,'stok'=>6],
            ['kode'=>'ATK012','nama'=>'Gunting Besar','kat'=>'ATK/01','sat'=>'Buah','harga'=>15000,'stok'=>4],
            ['kode'=>'ATK013','nama'=>'Binder Klip No. 107','kat'=>'ATK/01','sat'=>'Dos','harga'=>4000,'stok'=>22],
            ['kode'=>'ATK014','nama'=>'Binder Klip No. 111','kat'=>'ATK/01','sat'=>'Dos','harga'=>6000,'stok'=>15],
            ['kode'=>'ATK015','nama'=>'Staples HD-10','kat'=>'ATK/01','sat'=>'Buah','harga'=>25000,'stok'=>3],
            ['kode'=>'ATK016','nama'=>'Isi Staples No. 10','kat'=>'ATK/01','sat'=>'Dos','harga'=>3500,'stok'=>40],
            ['kode'=>'ATK017','nama'=>'Perforator (Pelubang Kertas)','kat'=>'ATK/01','sat'=>'Buah','harga'=>45000,'stok'=>2],
            ['kode'=>'ATK018','nama'=>'Map Plastik Folio','kat'=>'ATK/01','sat'=>'Buah','harga'=>3000,'stok'=>60],
            ['kode'=>'ATK019','nama'=>'Ordner Besar','kat'=>'ATK/01','sat'=>'Buah','harga'=>22000,'stok'=>20],
            ['kode'=>'ATK020','nama'=>'Amplop Coklat Folio','kat'=>'ATK/01','sat'=>'Dos','harga'=>28000,'stok'=>10],
            // Barang Cetakan
            ['kode'=>'BC001','nama'=>'Formulir Surat Keterangan','kat'=>'BC/02','sat'=>'Rim','harga'=>75000,'stok'=>5],
            ['kode'=>'BC002','nama'=>'Buku Tamu','kat'=>'BC/02','sat'=>'Buku','harga'=>25000,'stok'=>4],
            ['kode'=>'BC003','nama'=>'Buku Agenda Surat Masuk','kat'=>'BC/02','sat'=>'Buku','harga'=>30000,'stok'=>2],
            ['kode'=>'BC004','nama'=>'Buku Agenda Surat Keluar','kat'=>'BC/02','sat'=>'Buku','harga'=>30000,'stok'=>2],
            ['kode'=>'BC005','nama'=>'Formulir Administrasi Kependudukan','kat'=>'BC/02','sat'=>'Rim','harga'=>80000,'stok'=>3],
            ['kode'=>'BC006','nama'=>'Stempel Desa','kat'=>'BC/02','sat'=>'Buah','harga'=>150000,'stok'=>2],
            ['kode'=>'BC007','nama'=>'Kalender Dinding 2026','kat'=>'BC/02','sat'=>'Buah','harga'=>15000,'stok'=>20],
            // Consumable
            ['kode'=>'CON001','nama'=>'Kertas HVS A4 70gr','kat'=>'CON/03','sat'=>'Rim','harga'=>55000,'stok'=>25],
            ['kode'=>'CON002','nama'=>'Kertas HVS F4 70gr','kat'=>'CON/03','sat'=>'Rim','harga'=>60000,'stok'=>15],
            ['kode'=>'CON003','nama'=>'Tinta Printer Epson L3110 Hitam','kat'=>'CON/03','sat'=>'Botol','harga'=>35000,'stok'=>10],
            ['kode'=>'CON004','nama'=>'Tinta Printer Epson L3110 Cyan','kat'=>'CON/03','sat'=>'Botol','harga'=>30000,'stok'=>8],
            ['kode'=>'CON005','nama'=>'Tinta Printer Epson L3110 Magenta','kat'=>'CON/03','sat'=>'Botol','harga'=>30000,'stok'=>8],
            ['kode'=>'CON006','nama'=>'Tinta Printer Epson L3110 Yellow','kat'=>'CON/03','sat'=>'Botol','harga'=>30000,'stok'=>7],
            ['kode'=>'CON007','nama'=>'Flash Disk 32GB','kat'=>'CON/03','sat'=>'Buah','harga'=>85000,'stok'=>5],
            ['kode'=>'CON008','nama'=>'CD/DVD Blank','kat'=>'CON/03','sat'=>'Box','harga'=>50000,'stok'=>3],
            // Meterai
            ['kode'=>'MET001','nama'=>'Meterai Rp 10.000','kat'=>'MET/04','sat'=>'Lembar','harga'=>10000,'stok'=>100],
            // Kebersihan
            ['kode'=>'KBR001','nama'=>'Sapu Ijuk','kat'=>'KBR/05','sat'=>'Buah','harga'=>25000,'stok'=>4],
            ['kode'=>'KBR002','nama'=>'Pel Lantai','kat'=>'KBR/05','sat'=>'Buah','harga'=>35000,'stok'=>2],
            ['kode'=>'KBR003','nama'=>'Sabun Cuci Tangan Cair','kat'=>'KBR/05','sat'=>'Botol','harga'=>18000,'stok'=>10],
            ['kode'=>'KBR004','nama'=>'Cairan Pembersih Lantai','kat'=>'KBR/05','sat'=>'Liter','harga'=>22000,'stok'=>8],
            ['kode'=>'KBR005','nama'=>'Tisu Gulung','kat'=>'KBR/05','sat'=>'Pak','harga'=>28000,'stok'=>15],
            ['kode'=>'KBR006','nama'=>'Kantong Sampah Hitam','kat'=>'KBR/05','sat'=>'Pak','harga'=>15000,'stok'=>20],
        ];

        $barangIds = [];
        foreach ($barangs as $b) {
            $id = DB::table('barangs')->insertGetId([
                'kode'         => $b['kode'],
                'nama_barang'  => $b['nama'],
                'kategori_id'  => $kat[$b['kat']],
                'satuan_id'    => $sat[$b['sat']],
                'harga_satuan' => $b['harga'],
                'stok'         => $b['stok'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            $barangIds[$b['kode']] = ['id' => $id, 'harga' => $b['harga'], 'sat' => $b['sat']];
        }

        // ── TRANSAKSI MASUK ────────────────────────────────
        $masukData = [
            ['kode'=>'ATK001','jml'=>20,'tgl'=>'2026-01-10','periode'=>'Januari 2026','no_dok'=>'SPB/2026/001'],
            ['kode'=>'CON001','jml'=>10,'tgl'=>'2026-01-12','periode'=>'Januari 2026','no_dok'=>'SPB/2026/002'],
            ['kode'=>'CON002','jml'=>5,'tgl'=>'2026-01-15','periode'=>'Januari 2026','no_dok'=>'SPB/2026/003'],
            ['kode'=>'MET001','jml'=>50,'tgl'=>'2026-02-03','periode'=>'Februari 2026','no_dok'=>'SPB/2026/004'],
            ['kode'=>'ATK013','jml'=>10,'tgl'=>'2026-02-05','periode'=>'Februari 2026','no_dok'=>'SPB/2026/005'],
            ['kode'=>'BC001','jml'=>5,'tgl'=>'2026-02-10','periode'=>'Februari 2026','no_dok'=>'SPB/2026/006'],
            ['kode'=>'KBR003','jml'=>5,'tgl'=>'2026-03-01','periode'=>'Maret 2026','no_dok'=>'SPB/2026/007'],
            ['kode'=>'ATK018','jml'=>30,'tgl'=>'2026-03-05','periode'=>'Maret 2026','no_dok'=>'SPB/2026/008'],
            ['kode'=>'CON003','jml'=>5,'tgl'=>'2026-03-10','periode'=>'Maret 2026','no_dok'=>'SPB/2026/009'],
            ['kode'=>'ATK019','jml'=>10,'tgl'=>'2026-04-01','periode'=>'April 2026','no_dok'=>'SPB/2026/010'],
        ];

        foreach ($masukData as $i => $m) {
            $barang = $barangIds[$m['kode']];
            $noTx = 'TM-' . str_replace('-','',$m['tgl']) . '-' . str_pad($i+1,4,'0',STR_PAD_LEFT);
            DB::table('transaksi_masuks')->insert([
                'no_transaksi' => $noTx,
                'tanggal'      => $m['tgl'],
                'periode'      => $m['periode'],
                'barang_id'    => $barang['id'],
                'jumlah'       => $m['jml'],
                'harga_satuan' => $barang['harga'],
                'total'        => $m['jml'] * $barang['harga'],
                'no_dokumen'   => $m['no_dok'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // ── TRANSAKSI KELUAR ───────────────────────────────
        $keluarData = [
            ['kode'=>'ATK001','jml'=>5,'tgl'=>'2026-01-15','periode'=>'Januari 2026','penerima'=>'Sekretariat Desa'],
            ['kode'=>'CON001','jml'=>2,'tgl'=>'2026-01-20','periode'=>'Januari 2026','penerima'=>'Kaur Keuangan'],
            ['kode'=>'MET001','jml'=>10,'tgl'=>'2026-02-07','periode'=>'Februari 2026','penerima'=>'Kasie Pemerintahan'],
            ['kode'=>'ATK013','jml'=>3,'tgl'=>'2026-02-08','periode'=>'Februari 2026','penerima'=>'Kasie Pelayanan'],
            ['kode'=>'BC001','jml'=>1,'tgl'=>'2026-02-12','periode'=>'Februari 2026','penerima'=>'Kaur Tata Usaha'],
            ['kode'=>'KBR003','jml'=>2,'tgl'=>'2026-03-03','periode'=>'Maret 2026','penerima'=>'Petugas Kebersihan'],
            ['kode'=>'CON003','jml'=>2,'tgl'=>'2026-03-12','periode'=>'Maret 2026','penerima'=>'Operator Komputer'],
            ['kode'=>'ATK018','jml'=>10,'tgl'=>'2026-03-15','periode'=>'Maret 2026','penerima'=>'Sekretariat Desa'],
            ['kode'=>'ATK005','jml'=>5,'tgl'=>'2026-04-02','periode'=>'April 2026','penerima'=>'Kaur Perencanaan'],
        ];

        foreach ($keluarData as $i => $k) {
            $barang = $barangIds[$k['kode']];
            $noTx = 'TK-' . str_replace('-','',$k['tgl']) . '-' . str_pad($i+1,4,'0',STR_PAD_LEFT);
            DB::table('transaksi_keluars')->insert([
                'no_transaksi' => $noTx,
                'tanggal'      => $k['tgl'],
                'periode'      => $k['periode'],
                'barang_id'    => $barang['id'],
                'jumlah'       => $k['jml'],
                'harga_satuan' => $barang['harga'],
                'total'        => $k['jml'] * $barang['harga'],
                'penerima'     => $k['penerima'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
