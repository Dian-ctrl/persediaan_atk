@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
    </div>
    <div>
        <h1>Barang Masuk</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <span>Barang Masuk</span>
        </div>
    </div>
</div>

<!-- FILTER -->
<div class="filter-bar">
    <div class="filter-group" style="flex:2">
        <label class="filter-label">Cari Barang</label>
        <input type="text" id="searchQ" class="form-control" placeholder="Nama barang..." value="{{ request('q') }}">
    </div>
    <div class="filter-group">
        <label class="filter-label">Dari Tanggal</label>
        <input type="date" id="dariTgl" class="form-control" value="{{ request('dari') }}">
    </div>
    <div class="filter-group">
        <label class="filter-label">Sampai</label>
        <input type="date" id="sampaiTgl" class="form-control" value="{{ request('sampai') }}">
    </div>
    <div class="filter-group">
        <label class="filter-label">Periode</label>
        <input type="text" id="periode" class="form-control" placeholder="Contoh: Januari 2026" value="{{ request('periode') }}">
    </div>
    <div style="display:flex;gap:8px;align-self:flex-end">
        <button onclick="doFilter()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Filter
        </button>
        <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Data Barang Masuk ({{ $transaksis->total() }})
        </div>
        <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Input Barang Masuk
        </a>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NO. TRANSAKSI</th>
                    <th>TANGGAL</th>
                    <th>PERIODE</th>
                    <th>NAMA BARANG</th>
                    <th>JUMLAH</th>
                    <th>HARGA SATUAN</th>
                    <th>TOTAL</th>
                    <th>NO. DOKUMEN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $i => $t)
                <tr>
                    <td>{{ $transaksis->firstItem() + $i }}</td>
                    <td><span class="code-text" style="color:var(--accent)">{{ $t->no_transaksi }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                    <td><span class="badge badge-green">{{ $t->periode }}</span></td>
                    <td style="font-weight:600">{{ $t->barang->nama_barang ?? '-' }}</td>
                    <td><span class="tx-num tx-masuk">+{{ $t->jumlah }} {{ $t->barang->satuan->nama_satuan ?? '' }}</span></td>
                    <td>Rp {{ number_format($t->harga_satuan,0,',','.') }}</td>
                    <td class="total-green">Rp {{ number_format($t->total,0,',','.') }}</td>
                    <td>{{ $t->no_dokumen ?? '-' }}</td>
                    <td>
                        <div style="display:flex;gap:5px">
                            <a href="{{ route('barang-masuk.edit', $t) }}" class="btn btn-warning btn-icon btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('barang-masuk.destroy', $t) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada transaksi barang masuk</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($transaksis->count() > 0)
        <div style="padding:12px 20px;background:var(--primary-pale);border-top:1px solid var(--border);text-align:right;font-weight:700;color:var(--primary-dark)">
            Total Nilai Halaman Ini: Rp {{ number_format($transaksis->sum('total'),0,',','.') }}
        </div>
        @endif
    </div>
    @if($transaksis->hasPages())
    <div class="pagination">{{ $transaksis->withQueryString()->links() }}</div>
    @endif
</div>

@push('scripts')
<script>
function doFilter() {
    const q = document.getElementById('searchQ').value;
    const dari = document.getElementById('dariTgl').value;
    const sampai = document.getElementById('sampaiTgl').value;
    const periode = document.getElementById('periode').value;
    let url = '{{ route("barang-masuk.index") }}?';
    if (q) url += 'q='+encodeURIComponent(q)+'&';
    if (dari) url += 'dari='+dari+'&';
    if (sampai) url += 'sampai='+sampai+'&';
    if (periode) url += 'periode='+encodeURIComponent(periode);
    window.location = url;
}
</script>
@endpush
@endsection
