@extends('layouts.app')
@php $pageTitle = 'Dashboard ATK'; @endphp

@section('content')

<div class="row g-3 mb-4">
    {{-- Hero Banner --}}
    <div class="col-lg-8">
        <div class="hero-banner">
            <h2>Manajemen ATK<br>Balai Desa Medini</h2>
            <p>Ringkasan Operasional: Kendali Penuh.<br>Pantau dan kelola persediaan barang habis pakai secara real-time.</p>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('transaksi-keluar.index') }}" class="hero-btn">
                    <i class="bi bi-arrow-up-right-circle-fill"></i> Riwayat Keluar
                </a>
                <a href="{{ route('transaksi-masuk.create') }}" class="hero-btn">
                    <i class="bi bi-plus-circle-fill"></i> Proses Permintaan
                </a>
            </div>
        </div>
    </div>

    {{-- Status Card --}}
    <div class="col-lg-4">
        <div class="status-card">
            <h5>STATUS INVENTARIS</h5>
            <h3>Kendali Cepat &amp; Presisi</h3>
            <p>Permintaan ditinjau dalam 12 jam. Proses terautomasi.</p>
            <div class="mt-3" style="display:flex;align-items:center;gap:8px">
                <div style="width:8px;height:8px;border-radius:50%;background:#7fffb0;flex-shrink:0"></div>
                <small style="color:rgba(255,255,255,0.8);font-size:.75rem">Sistem berjalan normal</small>
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="stat-label">Total Stok ATK</div>
                <div class="stat-val">{{ number_format($totalStok) }}</div>
                <div class="stat-sub"><i class="bi bi-arrow-up me-1"></i>+{{ $totalBarang }} item baru</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-teal"><i class="bi bi-cart-check-fill"></i></div>
            <div>
                <div class="stat-label">Permintaan Bulan Ini</div>
                <div class="stat-val">{{ $txKeluarBulanIni }}</div>
                <div class="stat-sub">Normal (Rata-rata 140)</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="stat-label">Stok Menipis (Kritis)</div>
                <div class="stat-val" style="color:#e65100">{{ $stokHabis }}</div>
                <div class="stat-sub" style="color:#e65100">Segera Re-order</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-lime"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-label">Pengambilan Selesai</div>
                <div class="stat-val">{{ $txKeluarBulanIni }}</div>
                <div class="stat-sub">Hari ini</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Manajemen Cepat --}}
    <div class="col-lg-7">
        <div class="card-md mb-3">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-lightning-charge-fill"></i> Manajemen Cepat</div>
                <a href="{{ route('barang.index') }}" style="font-size:.8rem;color:var(--green);text-decoration:none;font-weight:600">Semua Fitur →</a>
            </div>
            <div class="card-md-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('barang.create') }}" class="text-decoration-none">
                            <div style="text-align:center;padding:18px 12px;border:1.5px solid #e5ebe8;border-radius:10px;transition:all .18s" onmouseover="this.style.borderColor='var(--green)';this.style.background='var(--green-light)'" onmouseout="this.style.borderColor='#e5ebe8';this.style.background='#fff'">
                                <i class="bi bi-plus-square-fill" style="font-size:1.5rem;color:var(--green);display:block;margin-bottom:6px"></i>
                                <div style="font-size:.82rem;font-weight:600;color:#1a1a1a">Tambah Stok Baru</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('transaksi-masuk.create') }}" class="text-decoration-none">
                            <div style="text-align:center;padding:18px 12px;border:1.5px solid #e5ebe8;border-radius:10px;transition:all .18s" onmouseover="this.style.borderColor='var(--green)';this.style.background='var(--green-light)'" onmouseout="this.style.borderColor='#e5ebe8';this.style.background='#fff'">
                                <i class="bi bi-box-arrow-in-down" style="font-size:1.5rem;color:var(--green);display:block;margin-bottom:6px"></i>
                                <div style="font-size:.82rem;font-weight:600;color:#1a1a1a">Input Pengadaan</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('transaksi-keluar.create') }}" class="text-decoration-none">
                            <div style="text-align:center;padding:18px 12px;border:1.5px solid #e5ebe8;border-radius:10px;transition:all .18s" onmouseover="this.style.borderColor='var(--green)';this.style.background='var(--green-light)'" onmouseout="this.style.borderColor='#e5ebe8';this.style.background='#fff'">
                                <i class="bi bi-person-check-fill" style="font-size:1.5rem;color:var(--green);display:block;margin-bottom:6px"></i>
                                <div style="font-size:.82rem;font-weight:600;color:#1a1a1a">Permintaan Barang</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('kategori.index') }}" class="text-decoration-none">
                            <div style="text-align:center;padding:18px 12px;border:1.5px solid #e5ebe8;border-radius:10px;transition:all .18s" onmouseover="this.style.borderColor='var(--green)';this.style.background='var(--green-light)'" onmouseout="this.style.borderColor='#e5ebe8';this.style.background='#fff'">
                                <i class="bi bi-collection-fill" style="font-size:1.5rem;color:var(--green);display:block;margin-bottom:6px"></i>
                                <div style="font-size:.82rem;font-weight:600;color:#1a1a1a">Kategori ATK</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Berdasarkan Kategori --}}
    <div class="col-lg-5">
        <div class="card-md h-100">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-pie-chart-fill"></i> Stok Berdasarkan Kategori</div>
            </div>
            <div class="card-md-body">
                @foreach($nilaiPerKategori as $kat)
                @php $pct = $totalNilai > 0 ? ($kat->total_nilai / $totalNilai) * 100 : 0; @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1" style="font-size:.83rem">
                        <span class="fw-600">{{ $kat->nama }}</span>
                        <span style="color:var(--green);font-weight:700">{{ number_format($pct,0) }}%</span>
                    </div>
                    <div class="progress" style="height:6px;border-radius:10px;background:#edf2ef">
                        <div class="progress-bar" style="width:{{ $pct }}%;background:var(--green);border-radius:10px"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Aktivitas --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card-md">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</div>
            </div>
            <div style="padding:0 18px 18px">
                @forelse($txMasukTerakhir as $tx)
                <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 0;border-bottom:1px solid #edf2ef">
                    <div style="width:36px;height:36px;border-radius:8px;background:var(--green-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-box-arrow-in-down-right" style="color:var(--green);font-size:.9rem"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.85rem;font-weight:600;color:#1a1a1a">Penambahan Stok {{ Str::limit($tx->barang->nama_barang, 35) }}</div>
                        <div style="font-size:.77rem;color:#888">Penambahan {{ $tx->jumlah }} {{ $tx->barang->satuan->nama }} berhasil disimpan</div>
                        <div class="mt-1"><span class="badge-green">BERHASIL · UPDATE</span></div>
                    </div>
                    <div style="font-size:.74rem;color:#aaa;flex-shrink:0">{{ \Carbon\Carbon::parse($tx->tanggal)->diffForHumans() }}</div>
                </div>
                @empty
                <div style="text-align:center;padding:30px;color:#aaa;font-size:.84rem">Belum ada aktivitas</div>
                @endforelse

                @forelse($txKeluarTerakhir as $tx)
                <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 0;border-bottom:1px solid #edf2ef">
                    <div style="width:36px;height:36px;border-radius:8px;background:#fff8e1;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-box-arrow-up-right" style="color:#e65100;font-size:.9rem"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.85rem;font-weight:600;color:#1a1a1a">Pengambilan {{ Str::limit($tx->barang->nama_barang, 35) }}</div>
                        <div style="font-size:.77rem;color:#888">Oleh {{ $tx->keterangan ?? 'Pengguna' }}</div>
                        <div class="mt-1"><span class="badge-kritis">MENUNGGU APPROVAL</span></div>
                    </div>
                    <div style="font-size:.74rem;color:#aaa;flex-shrink:0">{{ \Carbon\Carbon::parse($tx->tanggal)->diffForHumans() }}</div>
                </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Stok Habis --}}
        @if($barangStokRendah->count() > 0)
        <div class="card-md">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-exclamation-triangle-fill" style="color:#e65100"></i> Stok Kritis</div>
                <span class="badge-red">{{ $barangStokRendah->count() }} item</span>
            </div>
            <div style="padding:0 18px 18px">
                @foreach($barangStokRendah->take(5) as $b)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #edf2ef;font-size:.83rem">
                    <span style="font-weight:600;color:#333">{{ Str::limit($b->nama_barang, 22) }}</span>
                    <span class="{{ $b->stok == 0 ? 'badge-red' : 'badge-kritis' }}">{{ $b->stok == 0 ? 'Habis' : 'Menipis' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Ringkasan --}}
        <div class="card-md mt-3">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-info-circle-fill"></i> Ringkasan</div>
            </div>
            <div style="padding:0">
                <table style="width:100%;font-size:.83rem">
                    <tr style="border-bottom:1px solid #edf2ef">
                        <td style="padding:10px 18px;color:#888">Total Kategori</td>
                        <td style="padding:10px 18px;font-weight:700;color:var(--green);text-align:right">{{ $totalKategori }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #edf2ef">
                        <td style="padding:10px 18px;color:#888">Total Satuan</td>
                        <td style="padding:10px 18px;font-weight:700;color:var(--green);text-align:right">{{ $totalSatuan }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #edf2ef">
                        <td style="padding:10px 18px;color:#888">Masuk Bulan Ini</td>
                        <td style="padding:10px 18px;font-weight:700;color:var(--green);text-align:right">{{ $txMasukBulanIni }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 18px;color:#888">Keluar Bulan Ini</td>
                        <td style="padding:10px 18px;font-weight:700;color:var(--green);text-align:right">{{ $txKeluarBulanIni }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
