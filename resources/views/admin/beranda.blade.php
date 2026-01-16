@extends('layouts.app')

@section('content')
<h3 class="mb-4">Dashboard Admin</h3>

<div class="row">

    <div class="col-md-3">
        <div class="card text-bg-primary mb-3">
            <div class="card-body">
                <h5>Total Pelanggan</h5>
                <h2>{{ $totalPelanggan }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5>Tagihan Bulan Ini</h5>
                <h2>{{ $tagihanBulanIni }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-danger mb-3">
            <div class="card-body">
                <h5>Menunggak</h5>
                <h2>{{ $tagihanMenunggak }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-warning mb-3">
            <div class="card-body">
                <h5>Komplain Baru</h5>
                <h2>{{ $komplainBaru }}</h2>
            </div>
        </div>
    </div>

</div>

<hr>

<div class="row">
    <div class="col-md-3">
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-outline-primary w-100 mb-2">
            📋 Data Pelanggan
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.tagihan.index') }}" class="btn btn-outline-success w-100 mb-2">
            💰 Data Tagihan
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.komplain.index') }}" class="btn btn-outline-warning w-100 mb-2">
            📨 Komplain
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-dark w-100 mb-2">
            📊 Laporan
        </a>
    </div>
</div>
@endsection
