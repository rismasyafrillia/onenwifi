@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Laporan Pembayaran</h3>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6>Total Tagihan</h6>
                    <h4>{{ $totalTagihan }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center text-success">
                <div class="card-body">
                    <h6>Lunas</h6>
                    <h4>{{ $lunas }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center text-danger">
                <div class="card-body">
                    <h6>Menunggak</h6>
                    <h4>{{ $menunggak }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center text-primary">
                <div class="card-body">
                    <h6>Total Pembayaran</h6>
                    <h4>Rp {{ number_format($totalNominal) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <a href="#" class="btn btn-primary mt-3">
        Lihat Laporan Bulanan
    </a>
</div>
@endsection
