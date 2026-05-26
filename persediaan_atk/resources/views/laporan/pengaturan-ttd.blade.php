@extends('layouts.app')
@php $pageTitle = 'Pengaturan Tanda Tangan'; $pageIcon = 'pen-fill'; @endphp

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card-hijau">
    <div class="card-header-hijau">
        <div class="title"><i class="bi bi-pen-fill"></i> Pengaturan Tanda Tangan Laporan</div>
        <a href="{{ route('laporan.index') }}" class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
    <div class="card-body-hijau p-4">
        <form method="POST" action="{{ route('laporan.simpan-ttd') }}">
            @csrf

            {{-- Info Instansi --}}
            <h6 class="fw-bold mb-3" style="color:var(--hijau);border-bottom:2px solid var(--hijau-border);padding-bottom:6px">
                <i class="bi bi-building me-1"></i> Informasi Instansi
            </h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.85rem;font-weight:600">Nama Instansi</label>
                    <input type="text" name="nama_instansi" class="form-control @error('nama_instansi') is-invalid @enderror"
                           value="{{ old('nama_instansi', $pengaturan['nama_instansi']) }}" required>
                    @error('nama_instansi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.85rem;font-weight:600">Kota</label>
                    <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror"
                           value="{{ old('kota', $pengaturan['kota']) }}" required>
                    @error('kota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Tanda Tangan --}}
            <h6 class="fw-bold mb-3" style="color:var(--hijau);border-bottom:2px solid var(--hijau-border);padding-bottom:6px">
                <i class="bi bi-person-lines-fill me-1"></i> Kolom Tanda Tangan
            </h6>
            <div class="row g-4">
                @foreach([
                    ['label'=>'Mengetahui',  'nama'=>'ttd_mengetahui_nama',    'jabatan'=>'ttd_mengetahui_jabatan',  'id'=>'mengetahui'],
                    ['label'=>'Menyetujui',  'nama'=>'ttd_menyetujui_nama',    'jabatan'=>'ttd_menyetujui_jabatan',  'id'=>'menyetujui'],
                    ['label'=>'Membuat',     'nama'=>'ttd_membuat_nama',       'jabatan'=>'ttd_membuat_jabatan',     'id'=>'membuat'],
                ] as $col)
                <div class="col-md-4">
                    <div class="p-3" style="background:var(--hijau-bg);border:1px solid var(--hijau-border);border-radius:8px">
                        <div class="fw-bold mb-3" style="color:var(--hijau)">
                            <i class="bi bi-person-check-fill me-1"></i> {{ $col['label'] }}
                        </div>
                        <div class="mb-2">
                            <label class="form-label mb-1" style="font-size:.82rem;font-weight:600">Nama</label>
                            <input type="text" name="{{ $col['nama'] }}" id="inp-{{ $col['id'] }}-nama"
                                   class="form-control form-control-sm @error($col['nama']) is-invalid @enderror"
                                   value="{{ old($col['nama'], $pengaturan[$col['nama']]) }}" required>
                            @error($col['nama'])<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label mb-1" style="font-size:.82rem;font-weight:600">Jabatan</label>
                            <input type="text" name="{{ $col['jabatan'] }}" id="inp-{{ $col['id'] }}-jabatan"
                                   class="form-control form-control-sm @error($col['jabatan']) is-invalid @enderror"
                                   value="{{ old($col['jabatan'], $pengaturan[$col['jabatan']]) }}" required>
                            @error($col['jabatan'])<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Preview --}}
            <div class="mt-4 p-3" style="background:#fff;border:1px solid var(--hijau-border);border-radius:8px">
                <div class="fw-bold mb-3" style="font-size:.82rem;color:#555"><i class="bi bi-eye me-1"></i> Preview Tanda Tangan</div>
                <div class="row text-center" style="font-size:.82rem">
                    @foreach(['mengetahui'=>'Mengetahui','menyetujui'=>'Menyetujui','membuat'=>'Membuat'] as $id=>$label)
                    <div class="col-md-4">
                        <p class="mb-0">{{ $label }},</p>
                        <div style="height:40px;border-bottom:1px dashed #ccc;margin:4px 20px"></div>
                        <p class="fw-bold mb-0" id="prev-{{ $id }}-nama">{{ $pengaturan['ttd_'.$id.'_nama'] }}</p>
                        <p class="mb-0 text-muted" id="prev-{{ $id }}-jabatan">{{ $pengaturan['ttd_'.$id.'_jabatan'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-hijau">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
['mengetahui','menyetujui','membuat'].forEach(id => {
    ['nama','jabatan'].forEach(field => {
        const inp = document.getElementById(`inp-${id}-${field}`);
        const pre = document.getElementById(`prev-${id}-${field}`);
        if (inp && pre) inp.addEventListener('input', () => pre.textContent = inp.value || '—');
    });
});
</script>
@endsection
