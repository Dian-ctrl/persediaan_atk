@extends('layouts.app')
@php $pageTitle = 'Stok Barang'; @endphp

@section('content')

{{-- Hero Banner --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="hero-banner">
            <h2>Manajemen Stok ATK</h2>
            <p>Pantau dan kelola persediaan barang habis pakai Balai<br>Desa Medini dengan presisi tinggi.</p>
            <a href="{{ route('barang.create') }}" class="hero-btn">
                <i class="bi bi-plus-circle-fill"></i> Tambah Barang Baru
            </a>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="row g-3 h-100">
            <div class="col-6">
                <a href="{{ route('laporan.index') }}" class="text-decoration-none">
                    <div style="background:#fff;border:1px solid #e5ebe8;border-radius:12px;padding:20px;text-align:center;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;transition:all .18s" onmouseover="this.style.borderColor='var(--green)'" onmouseout="this.style.borderColor='#e5ebe8'">
                        <i class="bi bi-printer-fill" style="font-size:1.8rem;color:var(--green)"></i>
                        <span style="font-size:.83rem;font-weight:600;color:#1a1a1a">Cetak Laporan</span>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('barang.create') }}" class="text-decoration-none">
                    <div style="background:#fff;border:1px solid #e5ebe8;border-radius:12px;padding:20px;text-align:center;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;transition:all .18s" onmouseover="this.style.borderColor='var(--green)'" onmouseout="this.style.borderColor='#e5ebe8'">
                        <i class="bi bi-plus-square-fill" style="font-size:1.8rem;color:var(--green)"></i>
                        <span style="font-size:.83rem;font-weight:600;color:#1a1a1a">Tambah Barang Baru</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Filter + Table --}}
<div class="card-md">
    <div class="card-md-header">
        <div class="card-md-title"><i class="bi bi-list-ul"></i> Daftar Inventaris <span style="font-weight:400;font-size:.78rem;color:#888;margin-left:4px">Menampilkan semua ketersediaan barang ATK</span></div>
        <div class="d-flex gap-2 align-items-center">
            <form method="GET" class="d-flex gap-2 align-items-center">
                <div style="display:flex;gap:6px">
                    <a href="{{ route('barang.index') }}" class="btn btn-sm {{ !request('stok') ? 'btn-green' : 'btn-outline-secondary' }}" style="border-radius:20px;padding:4px 14px;font-size:.78rem">Semua</a>
                    <a href="{{ route('barang.index', ['stok'=>'ada']) }}" class="btn btn-sm {{ request('stok')=='ada' ? 'btn-green' : 'btn-outline-secondary' }}" style="border-radius:20px;padding:4px 14px;font-size:.78rem">Tersedia</a>
                    <a href="{{ route('barang.index', ['stok'=>'habis']) }}" class="btn btn-sm {{ request('stok')=='habis' ? 'btn-green' : 'btn-outline-secondary' }}" style="border-radius:20px;padding:4px 14px;font-size:.78rem">Kritis</a>
                </div>
                <input type="hidden" name="stok" value="{{ request('stok') }}">
            </form>
        </div>
    </div>

    <div style="padding:14px 18px;border-bottom:1px solid #edf2ef">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Cari nama / kode barang..." value="{{ request('cari') }}" style="border-radius:8px">
            </div>
            <div class="col-md-3">
                <select name="kategori_id" class="form-select form-select-sm" style="border-radius:8px">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="stok" class="form-select form-select-sm" style="border-radius:8px">
                    <option value="">Semua Stok</option>
                    <option value="habis" {{ request('stok')=='habis' ? 'selected' : '' }}>Habis (0)</option>
                    <option value="ada"   {{ request('stok')=='ada'   ? 'selected' : '' }}>Ada</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-green btn-sm me-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table tbl-md">
            <thead>
                <tr>
                    <th style="width:45px">No</th>
                    <th style="width:100px">Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th class="text-center">Stok</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-end">Total Nilai</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td style="color:#aaa">{{ $data->firstItem() + $loop->index }}</td>
                    <td><code style="font-size:.78rem;background:#f5f7f6;padding:2px 6px;border-radius:4px">{{ $d->kode_barang }}</code></td>
                    <td>
                        <div style="font-weight:600;font-size:.87rem">{{ $d->nama_barang }}</div>
                    </td>
                    <td><span class="badge-green">{{ $d->kategori->nama }}</span></td>
                    <td style="color:#888">{{ $d->satuan->nama }}</td>
                    <td class="text-center">
                        @if($d->stok == 0)
                            <span class="badge-red">0</span>
                        @elseif($d->stok <= 5)
                            <span class="badge-kritis">{{ $d->stok }}</span>
                        @else
                            <span class="badge-green">{{ $d->stok }}</span>
                        @endif
                    </td>
                    <td class="text-end" style="font-size:.83rem;color:#555">Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                    <td class="text-end" style="font-weight:700;color:var(--green);font-size:.83rem">
                        Rp {{ number_format($d->stok * $d->harga_satuan,0,',','.') }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('barang.show', $d->id) }}" class="btn btn-sm" style="background:#e8f5ee;color:var(--green);border-radius:6px" title="Detail"><i class="bi bi-eye-fill"></i></a>
                        <a href="{{ route('barang.edit', $d->id) }}" class="btn btn-sm" style="background:#fff8e1;color:#795500;border-radius:6px" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('barang.destroy', $d->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus barang {{ $d->nama_barang }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm" style="background:#fdecea;color:#c62828;border-radius:6px" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-4" style="color:#aaa">Tidak ada data barang</td></tr>
                @endforelse
            </tbody>
            @if($data->count() > 0)
            <tfoot>
                <tr style="background:var(--green-light)">
                    <td colspan="7" class="text-end fw-bold" style="color:var(--green);font-size:.83rem">Total Nilai Persediaan:</td>
                    <td class="text-end fw-bold" style="color:var(--green)">
                        Rp {{ number_format($data->sum(fn($d) => $d->stok * $d->harga_satuan),0,',','.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <div class="p-3">{{ $data->withQueryString()->links() }}</div>
</div>
@endsection
