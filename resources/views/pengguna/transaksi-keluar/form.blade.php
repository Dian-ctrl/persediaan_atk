@extends('layouts.pengguna')
@php $pageTitle = 'Form Permintaan'; @endphp

@section('content')

<div class="row g-3">
    {{-- Form Permintaan --}}
    <div class="col-lg-8">
        <div class="card-md">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-list-check"></i> Form Permintaan</div>
            </div>
            <div class="card-md-body">

                {{-- Step Indicator --}}
                <div style="display:flex;align-items:center;margin-bottom:28px;gap:0">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:4px">
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--green);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:700"><i class="bi bi-check-lg"></i></div>
                        <div style="font-size:.72rem;font-weight:600;color:var(--green)">Langkah 1:</div>
                        <div style="font-size:.72rem;color:var(--green);font-weight:700">Pilih Barang</div>
                    </div>
                    <div style="flex:1;height:2px;background:var(--green);margin-bottom:22px"></div>
                    <div style="display:flex;flex-direction:column;align-items:center;gap:4px">
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--green);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:700">2</div>
                        <div style="font-size:.72rem;font-weight:600;color:var(--green)">Langkah 2:</div>
                        <div style="font-size:.72rem;color:var(--green);font-weight:700">Jumlah &amp; Keperluan</div>
                    </div>
                    <div style="flex:1;height:2px;background:#e5ebe8;margin-bottom:22px"></div>
                    <div style="display:flex;flex-direction:column;align-items:center;gap:4px">
                        <div style="width:28px;height:28px;border-radius:50%;background:#e5ebe8;display:flex;align-items:center;justify-content:center;color:#aaa;font-size:.75rem;font-weight:700">3</div>
                        <div style="font-size:.72rem;font-weight:600;color:#aaa">Langkah 3:</div>
                        <div style="font-size:.72rem;color:#aaa;font-weight:700">Konfirmasi</div>
                    </div>
                </div>

                <form action="{{ route('pengguna.transaksi-keluar.store') }}" method="POST" id="formPermintaan">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">No. Transaksi <span style="color:#dc3545">*</span></label>
                            <input type="text" name="no_transaksi" class="form-control @error('no_transaksi') is-invalid @enderror" value="{{ old('no_transaksi', $noTransaksi) }}">
                            @error('no_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal <span style="color:#dc3545">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}">
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Pilih Barang <span style="color:#dc3545">*</span></label>
                            <select name="barang_id" id="barang_id" class="form-select @error('barang_id') is-invalid @enderror" onchange="isiHarga(this)">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}" data-harga="{{ $b->harga_satuan }}" data-stok="{{ $b->stok }}"
                                        {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                        [{{ $b->kode_barang }}] {{ $b->nama_barang }} (Stok: {{ $b->stok }} {{ $b->satuan->nama }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Unit <span style="color:#dc3545">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" min="1"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                value="{{ old('jumlah', 1) }}" oninput="hitungTotal()">
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Satuan</label>
                            <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" value="{{ old('harga_satuan', 0) }}" readonly style="background:#f5f7f6">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keperluan / Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Jelaskan detail kebutuhan penggunaan barang...">{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-green w-100" style="padding:12px;font-size:.9rem">
                                <i class="bi bi-send-fill me-2"></i> Lanjutkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar: Status + Riwayat --}}
    <div class="col-lg-4">
        <div class="status-card mb-3">
            <h5>STATUS INVENTARIS</h5>
            <div style="display:flex;gap:14px;align-items:center;margin-bottom:12px">
                <div style="width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:1.3rem">⚙️</div>
                <div style="font-size:1rem;font-weight:800">Proses Terkelola,<br>Stok Aman.</div>
            </div>
            <p>Semua permintaan ditinjau dalam 12 jam.</p>
        </div>

        <div class="card-md">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-clock-history"></i> Riwayat Permintaan</div>
            </div>
            <div style="padding:0 16px 16px">
                @forelse([] as $r)
                <div style="padding:12px 0;border-bottom:1px solid #edf2ef;font-size:.82rem">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:3px">
                        <div style="color:#888;font-size:.74rem">{{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</div>
                        <span class="badge-green">Selesai</span>
                    </div>
                    <div style="font-weight:600;color:#1a1a1a">{{ Str::limit($r->barang->nama_barang, 28) }}</div>
                    <div style="color:#888;font-size:.74rem">{{ $r->jumlah }} unit</div>
                </div>
                @empty
                <div style="text-align:center;padding:20px;color:#aaa;font-size:.82rem">Belum ada riwayat</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function isiHarga(sel){
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('harga_satuan').value = opt.dataset.harga || 0;
    hitungTotal();
}
function hitungTotal(){
    const jml = parseFloat(document.getElementById('jumlah').value) || 0;
    const hrg = parseFloat(document.getElementById('harga_satuan').value) || 0;
    // total can be shown if needed
}
</script>
@endpush
@endsection
