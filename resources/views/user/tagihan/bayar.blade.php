@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">Pembayaran Tagihan</h3>
        <p class="text-muted mb-0">Selesaikan pembayaran Anda dengan aman</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="mb-3">
                <p class="mb-1 text-muted">Periode</p>
                <h6 class="fw-semibold">{{ $tagihan->periode }}</h6>
            </div>

            <div class="mb-4">
                <p class="mb-1 text-muted">Total Pembayaran</p>
                <h4 class="fw-bold text-primary">
                    Rp {{ number_format($tagihan->nominal) }}
                </h4>
            </div>

            <div class="text-end">
                <button id="pay-button" class="btn btn-success btn-lg">
                    <i class="bi bi-wallet2 me-1"></i> Bayar Sekarang
                </button>
            </div>

        </div>
    </div>

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}');
};
</script>
@endsection
