@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- JUDUL --}}
    <div class="mb-4">
        <h3 class="fw-bold">Dashboard Admin</h3>
        <small class="text-muted">
            Ringkasan data sistem OneN WiFi
        </small>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="mb-4">

        <div class="row align-items-end g-3">

            {{-- MODE FILTER --}}
            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    Filter Dashboard
                </label>

                <select name="mode"
                        class="form-select"
                        onchange="togglePeriode(this.value)">

                    <option value="semua"
                        {{ $mode == 'semua' ? 'selected' : '' }}>
                        Semua Periode
                    </option>

                    <option value="periode"
                        {{ $mode == 'periode' ? 'selected' : '' }}>
                        Per Periode
                    </option>

                </select>

            </div>

            {{-- PILIH PERIODE --}}
            <div class="col-md-3"
                 id="periodeBox"
                 style="{{ $mode == 'periode' ? '' : 'display:none;' }}">

                <label class="form-label fw-semibold">
                    Pilih Periode
                </label>

                <select name="periode" class="form-select">

                    @foreach($listPeriode as $p)

                        <option value="{{ $p }}"
                            {{ $periode == $p ? 'selected' : '' }}>

                            {{ $p }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- BUTTON --}}
            <div class="col-md-2">

                <button class="btn btn-primary w-100">
                    Terapkan
                </button>

            </div>

        </div>

    </form>

    {{-- KARTU --}}
    <div class="row g-4 mb-4">

        {{-- TOTAL PELANGGAN --}}
        <div class="col-md-3">

            <a href="{{ route('admin.pelanggan.index') }}"
               class="text-decoration-none">

                <div class="card dashboard-card bg-primary text-white">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <small>Total Pelanggan Aktif</small>
                            <h2 class="fw-bold">
                                {{ $totalPelanggan }}
                            </h2>
                        </div>

                        <i class="bi bi-people fs-1 opacity-75"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- BELUM BAYAR --}}
        <div class="col-md-3">

            <a href="{{ route('admin.tagihan.index') }}"
               class="text-decoration-none">

                <div class="card dashboard-card bg-info text-white">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <small>Belum Bayar</small>
                            <h2 class="fw-bold">
                                {{ $tagihanBulanIni }}
                            </h2>
                        </div>

                        <i class="bi bi-receipt fs-1 opacity-75"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- MENUNGGAK --}}
        <div class="col-md-3">

            <a href="{{ route('admin.tagihan.index') }}"
               class="text-decoration-none">

                <div class="card dashboard-card bg-danger text-white">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <small>Menunggak</small>
                            <h2 class="fw-bold">
                                {{ $tagihanMenunggak }}
                            </h2>
                        </div>

                        <i class="bi bi-exclamation-circle fs-1 opacity-75"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- KOMPLAIN --}}
        <div class="col-md-3">

            <a href="{{ route('admin.komplain.index') }}"
               class="text-decoration-none">

                <div class="card dashboard-card bg-warning text-dark">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <small>Komplain Baru</small>
                            <h2 class="fw-bold">
                                {{ $komplainBaru }}
                            </h2>
                        </div>

                        <i class="bi bi-chat-dots fs-1 opacity-75"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- TOTAL PEMBAYARAN --}}
        <div class="col-md-12">

            <a href="{{ route('admin.pembayaran.bulanIni') }}"
               class="text-decoration-none">

                <div class="card shadow-sm border-0 dashboard-card bg-success text-white">

                    <div class="card-body">

                        <h6>Total Pembayaran</h6>

                        <h4>
                            Rp {{ number_format($totalPembayaranBulanIni,0,',','.') }}
                        </h4>

                    </div>

                </div>

            </a>

        </div>

    </div>

    {{-- GRAFIK --}}
    <div class="row g-4">

        {{-- GRAFIK REKAP --}}
        <div class="col-md-7">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h6 class="fw-bold mb-3">
                        Rekap Tagihan Menunggak & Lunas
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

                    <h6 class="fw-bold mb-3">
                        Daerah dengan Tunggakan Terbanyak
                    </h6>

                    <div class="chart-wrapper">
                        <canvas id="grafikDaerah"></canvas>
                    </div>

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

<script>
new Chart(document.getElementById('grafikDaerah'), {

    type: 'bar',

    data: {

        labels: {!! json_encode($daerahLabel) !!},

        datasets: [

            {
                label: 'Jumlah Menunggak',
                data: {!! json_encode($daerahMenunggak) !!},
                backgroundColor: '#dc3545'
            }

        ]

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

<script>
function togglePeriode(value)
{
    const periodeBox = document.getElementById('periodeBox');

    if (value === 'periode') {
        periodeBox.style.display = 'block';
    } else {
        periodeBox.style.display = 'none';
    }
}
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

.dashboard-card small {
    letter-spacing: .5px;
    opacity: .9;
}

.dashboard-card h2,
.dashboard-card h4 {
    line-height: 1.1;
}

.chart-wrapper {
    height: 250px;
    position: relative;
}

</style>

@endsection