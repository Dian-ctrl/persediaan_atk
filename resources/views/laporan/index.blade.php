@extends('layouts.app')
@php $pageTitle = 'Laporan Stok'; @endphp

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 style="font-weight:800;font-size:1.3rem;color:#1a1a1a;margin:0">Riwayat Laporan Bulanan</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.pengaturan-ttd') }}" class="btn btn-green-outline btn-sm"><i class="bi bi-pencil-square me-1"></i> Atur Tanda Tangan</a>
    </div>
</div>

{{-- Unduh Form --}}
<div class="card-md mb-4">
    <div class="card-md-header">
        <div class="card-md-title"><i class="bi bi-file-earmark-bar-graph-fill"></i> Unduh Laporan Persediaan Barang</div>
        <form method="GET" id="formLaporan" class="d-flex gap-2 align-items-center flex-wrap">
            <select name="bulan" id="bulanSelect" class="form-select form-select-sm" style="width:auto">
                <option value="">Semua Bulan</option>
                @php $bulanList=['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember']; @endphp
                @foreach($bulanList as $val => $nama)
                    <option value="{{ $val }}" {{ request('bulan', date('n')) == $val ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
            <select name="tahun" class="form-select form-select-sm" style="width:auto">
                @for($y = date('Y')+1; $y >= 2023; $y--)
                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <input type="date" name="tanggal_laporan" class="form-control form-control-sm" style="width:auto" value="{{ request('tanggal_laporan', date('Y-m-d')) }}">
            <select name="kategori_id" class="form-select form-select-sm" style="width:auto">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->kode }} – {{ $k->nama }}</option>
                @endforeach
            </select>
            <button type="submit" formaction="{{ route('laporan.preview') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye me-1"></i>Preview</button>
            <button type="submit" formaction="{{ route('laporan.download-excel') }}" class="btn btn-sm btn-green-outline"><i class="bi bi-file-earmark-excel me-1"></i>Excel</button>
            <button type="submit" formaction="{{ route('laporan.download-pdf') }}" class="btn btn-sm btn-green"><i class="bi bi-download me-1"></i>Unduh PDF</button>
        </form>
    </div>

    <div class="card-md-body">
        <div class="row g-3">
            <div class="col-md-3">
                <div style="background:#f5f7f6;border-radius:10px;padding:16px">
                    <div style="font-size:.78rem;font-weight:700;color:#888;margin-bottom:4px"><i class="bi bi-eye me-1"></i>Preview</div>
                    <div style="font-size:.8rem;color:#555">Lihat data laporan terlebih dahulu sebelum mengunduh.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div style="background:var(--green-light);border:1px solid var(--green-border);border-radius:10px;padding:16px">
                    <div style="font-size:.78rem;font-weight:700;color:var(--green);margin-bottom:4px"><i class="bi bi-file-earmark-excel me-1"></i>Excel (.xlsx)</div>
                    <div style="font-size:.8rem;color:#555">Unduh laporan format spreadsheet yang bisa diedit.</div>
                </div>
            </div>
            <div class="col-md-3">
                <div style="background:#fdecea;border-radius:10px;padding:16px">
                    <div style="font-size:.78rem;font-weight:700;color:#c62828;margin-bottom:4px"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</div>
                    <div style="font-size:.8rem;color:#555">Unduh laporan PDF siap cetak (landscape A4).</div>
                </div>
            </div>
            <div class="col-md-3">
                <div style="background:#fff8e1;border-radius:10px;padding:16px">
                    <div style="font-size:.78rem;font-weight:700;color:#795500;margin-bottom:4px"><i class="bi bi-lightbulb-fill me-1"></i>Tips</div>
                    <div style="font-size:.8rem;color:#555">
                        <div class="d-flex align-items-center gap-2">
                            <input type="checkbox" id="allMonths" name="all_months" value="1" form="formLaporan" class="form-check-input" {{ request('all_months') ? 'checked' : '' }}>
                            <label for="allMonths" style="font-size:.78rem">Unduh semua bulan</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Category list --}}
<div class="card-md">
    <div class="card-md-header">
        <div class="card-md-title"><i class="bi bi-collection-fill"></i> Kategori Tersedia</div>
        <span style="font-size:.78rem;color:#888">{{ $kategoris->count() }} kategori</span>
    </div>
    <div class="table-responsive">
        <table class="table tbl-md">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Kategori</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $i => $k)
                <tr>
                    <td style="color:#aaa">{{ $i + 1 }}</td>
                    <td><code style="font-size:.78rem;background:#f5f7f6;padding:2px 6px;border-radius:4px">{{ $k->kode }}</code></td>
                    <td style="font-weight:600">{{ $k->nama }}</td>
                    <td class="text-center">
                        <button type="submit" form="formLaporan" formaction="{{ route('laporan.download-pdf') }}"
                            onclick="document.querySelector('#formLaporan select[name=kategori_id]').value='{{ $k->id }}'"
                            class="btn btn-sm btn-green" style="font-size:.75rem">
                            <i class="bi bi-download me-1"></i>PDF Kategori Ini
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4" style="color:#aaa">Tidak ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const allCb = document.getElementById('allMonths');
    const bulanSel = document.getElementById('bulanSelect');
    const tahunSel = document.querySelector('#formLaporan select[name="tahun"]');
    if (!allCb || !bulanSel || !tahunSel) return;
    function toggleAll() { const c = allCb.checked; bulanSel.disabled = c; tahunSel.disabled = c; }
    allCb.addEventListener('change', toggleAll);
    window.addEventListener('load', toggleAll);
})();
</script>
@endpush

@endsection
