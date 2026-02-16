@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- JUDUL --}}
    <div class="mb-4">
        <h3 class="fw-bold">Dashboard Admin</h3>
        <small class="text-muted">Ringkasan data sistem OneN WiFi</small>
    </div>

    {{-- KARTU RINGKASAN --}}
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <a href="{{ route('admin.pelanggan.index') }}" class="text-decoration-none">
            <div class="card dashboard-card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small>Total Pelanggan</small>
                        <h2 class="fw-bold">{{ $totalPelanggan }}</h2>
                    </div>
                    <i class="bi bi-people fs-1 opacity-75"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.tagihan.index') }}" class="text-decoration-none">
            <div class="card dashboard-card bg-info text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small>Tagihan Bulan Ini</small>
                        <h2 class="fw-bold">{{ $tagihanBulanIni }}</h2>
                    </div>
                    <i class="bi bi-receipt fs-1 opacity-75"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.tagihan.index') }}" class="text-decoration-none">
            <div class="card dashboard-card bg-danger text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small>Menunggak</small>
                        <h2 class="fw-bold">{{ $tagihanMenunggak }}</h2>
                    </div>
                    <i class="bi bi-exclamation-circle fs-1 opacity-75"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.komplain.index') }}" class="text-decoration-none">
            <div class="card dashboard-card bg-warning text-dark">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small>Komplain Baru</small>
                        <h2 class="fw-bold">{{ $komplainBaru }}</h2>
                    </div>
                    <i class="bi bi-chat-dots fs-1 opacity-75"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- PEMBAYARAN --}}
    <div class="col-md-3">
        <div class="card dashboard-card bg-success text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small>Pembayaran Bulan Ini</small>
                    <h4 class="fw-bold mb-0">
                        Rp {{ number_format($totalPembayaranBulanIni ?? 0) }}
                    </h4>
                </div>
                <i class="bi bi-cash-coin fs-1 opacity-75"></i>
            </div>
        </div>
    </div>

</div>

    {{-- GRAFIK --}}
<div class="row g-4">

    {{-- GRAFIK REKAP BULANAN --}}
    <div class="col-md-7">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-2">
                    Rekap Tagihan Menunggak & Lunas ({{ date('Y') }})
                </h6>

                <div class="chart-wrapper">
                    <canvas id="grafikRekap"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK DAERAH --}}
    <div class="col-md-5">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-2">
                    Daerah dengan Tunggakan Terbanyak
                </h6>

                <div class="chart-wrapper">
                    <canvas id="grafikDaerah"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('grafikRekap'), {
    type: 'line',
    data: {
        labels: {!! json_encode($bulanLabel) !!},
        datasets: [
            {
                label: 'Menunggak',
                data: {!! json_encode($dataMenunggak) !!},
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220,53,69,0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 4
            },
            {
                label: 'Lunas',
                data: {!! json_encode($dataLunas) !!},
                borderColor: '#198754',
                backgroundColor: 'rgba(25,135,84,0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                mode: 'index',
                intersect: false
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
<style>
    .dashboard-card small {
    letter-spacing: .5px;
    opacity: .9;
}

.dashboard-card h2,
.dashboard-card h4 {
    line-height: 1.1;
}
</style>
<script>
new Chart(document.getElementById('grafikDaerah'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($daerahLabel) !!},
        datasets: [{
            label: 'Jumlah Tagihan Menunggak',
            data: {!! json_encode($daerahMenunggak) !!},
            backgroundColor: '#dc3545'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
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

{{-- STYLE --}}
<style>
.dashboard-card {
    border-radius: 14px;
    transition: all 0.25s ease;
}
.dashboard-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.chart-wrapper {
    height: 230px; /* ← KUNCI UKURAN */
    position: relative;
}
</style>
@endsection
