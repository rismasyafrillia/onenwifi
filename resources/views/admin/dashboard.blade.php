@extends('layouts.app')

@section('content')
<h3 class="mb-4">Dashboard Admin</h3>

<div class="row g-3">

    <div class="col-md-3">
        <a href="{{ route('admin.pelanggan.index') }}" class="text-decoration-none">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h6>Total Pelanggan</h6>
                    <h2>{{ $totalPelanggan }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.tagihan.index') }}" class="text-decoration-none">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h6>Tagihan Bulan Ini</h6>
                    <h2>{{ $tagihanBulanIni }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.tagihan.index') }}" class="text-decoration-none">
            <div class="card text-bg-danger">
                <div class="card-body">
                    <h6>Menunggak</h6>
                    <h2>{{ $tagihanMenunggak }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.komplain.index') }}" class="text-decoration-none">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <h6>Komplain Baru</h6>
                    <h2>{{ $komplainBaru }}</h2>
                </div>
            </div>
        </a>
    </div>

</div>
@endsection
