@extends('layouts.user')

@section('content')
<h3 class="mb-4">Dashboard Pelanggan</h3>

<div class="row g-3">

    <!-- TOTAL TAGIHAN -->
    <div class="col-md-4">
        <a href="{{ route('user.tagihan.index') }}" class="text-decoration-none">
            <div class="card text-bg-success h-100">
                <div class="card-body">
                    <h6>Total Tagihan</h6>
                    <h2>{{ $totalTagihan }}</h2>
                </div>
            </div>
        </a>
    </div>

    <!-- BELUM BAYAR -->
    <div class="col-md-4">
        <a href="{{ route('user.tagihan.index') }}" class="text-decoration-none">
            <div class="card text-bg-danger h-100">
                <div class="card-body">
                    <h6>Belum Bayar</h6>
                    <h2>{{ $tagihanBelumBayar }}</h2>
                </div>
            </div>
        </a>
    </div>

    <!-- KOMPLAIN -->
    <div class="col-md-4">
        <a href="{{ route('user.komplain.index') }}" class="text-decoration-none">
            <div class="card text-bg-warning h-100">
                <div class="card-body">
                    <h6>Komplain Saya</h6>
                    <h2>{{ $totalKomplain }}</h2>
                </div>
            </div>
        </a>
    </div>

</div>
@endsection
