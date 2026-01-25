@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- JUDUL -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Laporan Pembayaran</h3>
    </div>

    <!-- FILTER -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
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
                    <label class="form-label">Tahun</label>
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
                        🔍 Terapkan Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    <a href="{{ route('admin.laporan.export.pdf', request()->query()) }}"
    class="btn btn-danger mb-3">
        Export PDF
    </a>
    
    <!-- STATISTIK -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <small class="text-muted">Total Tagihan</small>
                    <h4 class="fw-bold">{{ $totalTagihan }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-success">
                    <small class="text-muted">Lunas</small>
                    <h4 class="fw-bold">{{ $lunas }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-danger">
                    <small class="text-muted">Menunggak</small>
                    <h4 class="fw-bold">{{ $menunggak }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-primary">
                    <small class="text-muted">Total Pembayaran</small>
                    <h5 class="fw-bold">Rp {{ number_format($totalNominal) }}</h5>
                </div>
            </div>
        </div>

    </div>

    <!-- GRAFIK -->
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="mb-3">Tren Tagihan Menunggak</h6>
            <div style="height:260px">
                <canvas id="grafikTren"></canvas>
            </div>
        </div>
    </div>

    <!-- TABEL LAPORAN -->
    <div class="card">
        <div class="card-body">
            <h6 class="mb-3">Detail Laporan Tagihan</h6>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihans ?? [] as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $t->periode ?? '-' }}</td>
                            <td>Rp {{ number_format($t->nominal) }}</td>
                            <td>
                                <span class="badge 
                                    {{ $t->status == 'lunas' ? 'bg-success' : 'bg-danger' }}">
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

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('grafikTren'), {
    type: 'line',
    data: {
        labels: {!! json_encode($bulanLabel) !!},
        datasets: [{
            label: 'Menunggak',
            data: {!! json_encode($bulanData) !!},
            borderColor: '#dc3545',
            backgroundColor: 'rgba(220,53,69,0.15)',
            fill: true,
            tension: 0.4,
            pointRadius: 4
        }]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
