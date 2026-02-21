@extends('layouts.user')

@section('content')
<div class="container">

    <div class="mb-4">
        <h4 class="fw-bold">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard Pelanggan
        </h4>
        <p class="text-muted mb-0">
            Ringkasan informasi akun dan aktivitas Anda
        </p>
    </div>

<script>
function mintaIzin() {
  Notification.requestPermission().then(function(permission){
    alert("Permission: " + permission);
  });
}
</script>

    <div class="row g-4">

        <!-- TOTAL TAGIHAN -->
        <div class="col-md-4">
            <a href="{{ route('user.tagihan.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100 dashboard-card bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Total Tagihan</h6>
                            <h2 class="fw-bold mb-0">{{ $totalTagihan }}</h2>
                        </div>
                        <i class="bi bi-receipt fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- BELUM BAYAR -->
        <div class="col-md-4">
            <a href="{{ route('user.tagihan.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100 dashboard-card bg-danger text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Belum Bayar</h6>
                            <h2 class="fw-bold mb-0">{{ $tagihanBelumBayar }}</h2>
                        </div>
                        <i class="bi bi-exclamation-circle fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- KOMPLAIN -->
        <div class="col-md-4">
            <a href="{{ route('user.komplain.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100 dashboard-card bg-warning text-dark">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 opacity-75">Komplain Saya</h6>
                            <h2 class="fw-bold mb-0">{{ $totalKomplain }}</h2>
                        </div>
                        <i class="bi bi-chat-dots fs-1 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>

{{-- STYLE KHUSUS DASHBOARD --}}
<style>
.dashboard-card {
    transition: all .2s ease-in-out;
}
.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
}
</style>
@endsection