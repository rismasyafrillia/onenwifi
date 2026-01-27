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
                <div class="card dashboard-card bg-success text-white">
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

    </div>

    {{-- GRAFIK MENUNGGAK --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3 fw-bold">Tagihan Menunggak ({{ date('Y') }})</h5>
            <div style="height:300px">
                <canvas id="grafikMenunggak"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('grafikMenunggak'), {
    type: 'line',
    data: {
        labels: {!! json_encode($bulanLabel) !!},
        datasets: [{
            label: 'Jumlah Menunggak',
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
</style>
@endsection
