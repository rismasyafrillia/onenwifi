@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">Detail Tagihan</h3>
        <p class="text-muted mb-0">Informasi lengkap tagihan layanan Anda</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Periode</p>
                    <h5 class="fw-semibold">{{ $tagihan->periode }}</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1 text-muted">Status</p>
                    <span class="badge fs-6
                        {{ $tagihan->status == 'lunas' ? 'bg-success' : 'bg-danger' }}">
                        {{ strtoupper($tagihan->status) }}
                    </span>
                </div>
            </div>

            <hr>

            <div class="mb-4">
                <p class="mb-1 text-muted">Nominal Tagihan</p>
                <h4 class="fw-bold text-primary">
                    Rp {{ number_format($tagihan->nominal) }}
                </h4>
            </div>

            @if($tagihan->status == 'belum bayar' || $tagihan->status == 'menunggak')
                <div class="d-flex justify-content-end">
                    <form action="{{ route('user.tagihan.bayar', $tagihan->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-warning text-dark">
                            <i class="bi bi-credit-card me-1"></i> Bayar Sekarang
                        </button>
                    </form>
                </div>
            @endif

            @if($tagihan->status == 'lunas')
                <div class="alert alert-success d-flex align-items-center mt-3">
                    <i class="bi bi-check-circle fs-4 me-2"></i>
                    Tagihan ini sudah dibayar lunas.
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
