@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-0">Laporan Pembayaran</h3>
        <small class="text-muted">Rekap pembayaran pelanggan OneN WiFi</small>
    </div>

    {{-- FILTER --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @for ($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$i,1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tahun</label>
                    <select name="tahun" class="form-select">
                        <option value="">Semua Tahun</option>
                        @for ($y = date('Y'); $y >= 2023; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Terapkan Filter
                    </button>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('admin.laporan.export.pdf', request()->query()) }}"
                       class="btn btn-danger w-100">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">
        <x-laporan-card title="Total Tagihan" :value="$totalTagihan" />
        <x-laporan-card title="Lunas" :value="$lunas" color="success" />
        <x-laporan-card title="Menunggak" :value="$menunggak" color="danger" />
        <x-laporan-card title="Total Pembayaran" 
            :value="'Rp '.number_format($totalNominal)" color="primary" />
    </div>

    {{-- TABEL --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Detail Tagihan</h6>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihans as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $t->periode }}</td>
                            <td>Rp {{ number_format($t->nominal) }}</td>
                            <td>
                                <span class="badge {{ $t->status == 'lunas' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td>{{ $t->created_at->format('d-m-Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
