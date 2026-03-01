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

                {{-- BULAN --}}
                <div class="col-md-2">
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

                {{-- TAHUN --}}
                <div class="col-md-2">
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

                {{-- STATUS --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="semua">Semua</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>
                            Lunas
                        </option>
                        <option value="belum bayar" {{ request('status') == 'belum bayar' ? 'selected' : '' }}>
                            Belum Bayar
                        </option>
                        <option value="menunggak" {{ request('status') == 'menunggak' ? 'selected' : '' }}>
                            Menunggak
                        </option>
                    </select>
                </div>

                {{-- NAMA --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Nama Pelanggan</label>
                    <input type="text"
                           name="nama"
                           value="{{ request('nama') }}"
                           class="form-control"
                           placeholder="Cari nama...">
                </div>

                {{-- BUTTON FILTER --}}
                <div class="col-md-1">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i>
                    </button>
                </div>

                {{-- EXPORT PDF --}}
                <div class="col-md-2">
                    <a href="{{ route('admin.laporan.export.pdf', request()->query()) }}"
                       class="btn btn-danger w-100">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
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
        <x-laporan-card 
            title="Total Pembayaran" 
            :value="'Rp '.number_format($totalNominal)" 
            color="primary" 
        />
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
                            <th>Nama</th>
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
                            <td>{{ $t->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $t->periode }}</td>
                            <td>Rp {{ number_format($t->nominal) }}</td>
                            <td>
                                <span class="badge 
                                    @if($t->status == 'lunas') bg-success
                                    @elseif($t->status == 'menunggak') bg-danger
                                    @else bg-warning text-dark
                                    @endif">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td>{{ $t->created_at->format('d-m-Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
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