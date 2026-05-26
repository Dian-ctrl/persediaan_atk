@extends('layouts.pengguna')
@php $pageTitle = 'Data Barang'; $pageIcon = 'archive-fill'; @endphp

@section('content')

<div class="card-biru mb-3">
    <div class="card-body-biru">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Cari Barang</label>
                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Nama / kode barang..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Kategori</label>
                <select name="kategori_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id')==$k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Stok</label>
                <select name="stok" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="ada"   {{ request('stok')=='ada'   ? 'selected':'' }}>Ada</option>
                    <option value="habis" {{ request('stok')=='habis' ? 'selected':'' }}>Habis</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-biru btn-sm me-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('pengguna.barang.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card-biru">
    <div class="card-header-biru">
        <div class="title"><i class="bi bi-archive-fill"></i> Data Barang ({{ $data->total() }} item)</div>
        <span style="font-size:.75rem;color:#64748b;background:#f1f5f9;border-radius:20px;padding:3px 10px">
            <i class="bi bi-eye me-1"></i>Tampilan Baca
        </span>
    </div>
    <div class="table-responsive">
        <table class="table tbl-biru">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th class="text-center">Stok</th>
                    <th class="text-end">Harga Satuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td>{{ $data->firstItem() + $loop->index }}</td>
                    <td><code style="font-size:.8rem">{{ $d->kode_barang }}</code></td>
                    <td style="font-weight:600;font-size:.88rem">{{ $d->nama_barang }}</td>
                    <td><span class="badge-biru" style="font-size:.7rem">{{ $d->kategori->nama }}</span></td>
                    <td>{{ $d->satuan->nama }}</td>
                    <td class="text-center">
                        @if($d->stok == 0)
                            <span class="badge-merah">0</span>
                        @elseif($d->stok <= 5)
                            <span class="badge-kuning">{{ $d->stok }}</span>
                        @else
                            <span class="badge-hijau">{{ $d->stok }}</span>
                        @endif
                    </td>
                    <td class="text-end" style="font-size:.84rem">Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data barang</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $data->withQueryString()->links() }}</div>
</div>
@endsection
