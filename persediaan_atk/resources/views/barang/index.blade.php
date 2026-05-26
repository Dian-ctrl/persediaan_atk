@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <div>
        <h1>Data Barang</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <span>Data Barang</span>
        </div>
    </div>
</div>

<!-- FILTER -->
<div class="filter-bar">
    <div class="filter-group" style="flex:2">
        <label class="filter-label">Cari Barang</label>
        <input type="text" id="searchBarang" class="form-control" placeholder="Nama / kode barang..." value="{{ request('q') }}">
    </div>
    <div class="filter-group">
        <label class="filter-label">Kategori</label>
        <select id="filterKategori" class="form-control">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kat)
            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label class="filter-label">Stok</label>
        <select id="filterStok" class="form-control">
            <option value="">Semua</option>
            <option value="ada" {{ request('stok') == 'ada' ? 'selected' : '' }}>Ada Stok</option>
            <option value="habis" {{ request('stok') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
        </select>
    </div>
    <div style="display:flex;gap:8px;align-self:flex-end">
        <button onclick="filterBarang()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Filter
        </button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Data Barang ({{ $barangs->total() }} item)
        </div>
        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Barang
        </a>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE</th>
                    <th>NAMA BARANG</th>
                    <th>KATEGORI</th>
                    <th>SATUAN</th>
                    <th>STOK</th>
                    <th>HARGA SATUAN</th>
                    <th>TOTAL NILAI</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $i => $b)
                <tr>
                    <td>{{ $barangs->firstItem() + $i }}</td>
                    <td><span class="code-text">{{ $b->kode }}</span></td>
                    <td style="font-weight:600;min-width:160px">{{ $b->nama_barang }}</td>
                    <td><span class="badge badge-green">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                    <td>{{ $b->satuan->nama_satuan ?? '-' }}</td>
                    <td>
                        <span class="stok-num {{ $b->stok == 0 ? 'empty' : ($b->stok <= 5 ? 'low' : 'ok') }}">
                            {{ $b->stok }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($b->harga_satuan,0,',','.') }}</td>
                    <td class="{{ $b->stok > 0 ? 'total-green' : 'total-red' }}">
                        Rp {{ number_format($b->stok * $b->harga_satuan,0,',','.') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:5px">
                            <a href="{{ route('barang.show', $b) }}" class="btn btn-secondary btn-icon btn-sm" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('barang.edit', $b) }}" class="btn btn-warning btn-icon btn-sm" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('barang.destroy', $b) }}" onsubmit="return confirm('Hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada data barang</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangs->hasPages())
    <div class="pagination">
        {{ $barangs->withQueryString()->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function filterBarang() {
    const q = document.getElementById('searchBarang').value;
    const kat = document.getElementById('filterKategori').value;
    const stok = document.getElementById('filterStok').value;
    let url = '{{ route("barang.index") }}?';
    if (q) url += 'q='+encodeURIComponent(q)+'&';
    if (kat) url += 'kategori_id='+kat+'&';
    if (stok) url += 'stok='+stok;
    window.location = url;
}
document.getElementById('searchBarang').addEventListener('keydown', e => { if(e.key==='Enter') filterBarang(); });
</script>
@endpush
@endsection
