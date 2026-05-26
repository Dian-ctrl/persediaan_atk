@extends('layouts.app')
@php $pageTitle = 'Preview Laporan'; $pageIcon = 'eye-fill'; @endphp

@section('content')

@php
    $grouped = $barang->groupBy(fn($b) => $b->kategori->kode ?? '-');
    $grandTotal = $barang->sum(fn($b) => $b->stok * $b->harga_satuan);
    $no = 1;
    $bulanList = ['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei',
                  '6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September',
                  '10'=>'Oktober','11'=>'November','12'=>'Desember'];
    if (request('all_months')) {
        $periode = 'Semua Bulan';
    } elseif (request('bulan') && request('tahun')) {
        $periode = $bulanList[request('bulan')] . ' ' . request('tahun');
    } elseif (request('tahun')) {
        $periode = 'Tahun ' . request('tahun');
    } else {
        $periode = now()->locale('id')->isoFormat('MMMM YYYY');
    }
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold" style="color:var(--hijau)">
            <i class="bi bi-eye-fill me-2"></i>Preview Laporan — Per {{ $periode }}
        </h5>
        <small class="text-muted">{{ $barang->count() }} item barang</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('laporan.download-excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-excel me-1"></i>Unduh Excel
        </a>
        <a href="{{ route('laporan.download-pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i>Unduh PDF
        </a>
    </div>
</div>

<div class="card-hijau">
    <div class="table-responsive">
        <table class="table tbl-hijau" style="font-size:.83rem">
            <thead>
                <tr>
                    <th style="width:40px">No</th>
                    <th style="width:90px">Kode</th>
                    <th>Nama Barang</th>
                    <th style="width:70px">Satuan</th>
                    <th style="width:140px">Kategori</th>
                    <th style="width:60px;text-align:center">Stok</th>
                    <th style="width:80px;text-align:center">Penambahan</th>
                    <th style="width:80px;text-align:center">Pemakaian</th>
                    <th style="width:130px;text-align:right">Harga Satuan</th>
                    <th style="width:140px;text-align:right">Total Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grouped as $kode => $items)
                    <tr style="background:var(--hijau-bg)">
                        <td colspan="10" class="fw-bold" style="color:var(--hijau-tua);font-size:.78rem">
                            <i class="bi bi-folder-fill me-1"></i>
                            [{{ $kode }}] {{ $items->first()->kategori->nama ?? $kode }}
                        </td>
                    </tr>
                    @php $subtotal = 0; @endphp
                    @foreach($items as $b)
                        @php $total = $b->stok * $b->harga_satuan; $subtotal += $total; @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td><code style="font-size:.75rem">{{ $b->kode_barang }}</code></td>
                            <td>{{ $b->nama_barang }}</td>
                            <td>{{ $b->satuan->nama ?? '-' }}</td>
                            <td><span class="badge-hijau" style="font-size:.7rem">{{ $b->kategori->nama ?? '-' }}</span></td>
                            <td class="text-center">
                                @if($b->stok == 0)
                                    <span class="badge-merah">0</span>
                                @elseif($b->stok <= 5)
                                    <span class="badge-kuning">{{ $b->stok }}</span>
                                @else
                                    <span class="badge-hijau">{{ $b->stok }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ number_format($b->masuk ?? 0,0,',','.') }}</td>
                            <td class="text-center">{{ number_format($b->keluar ?? 0,0,',','.') }}</td>
                            <td class="text-end">Rp {{ number_format($b->harga_satuan,0,',','.') }}</td>
                            <td class="text-end fw-bold" style="color:var(--hijau)">Rp {{ number_format($total,0,',','.') }}</td>
                        </tr>
                    @endforeach
                    <tr style="background:#d5eeda">
                        <td colspan="9" class="text-end fw-bold" style="font-size:.8rem">
                            Subtotal {{ $items->first()->kategori->nama ?? $kode }}:
                        </td>
                        <td class="text-end fw-bold">Rp {{ number_format($subtotal,0,',','.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data barang</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:var(--hijau);color:#fff">
                    <td colspan="7" class="text-end fw-bold">GRAND TOTAL</td>
                    <td class="text-end fw-bold">Rp {{ number_format($grandTotal,0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
