@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h4 class="fw-bold mb-4">
                <i class="bi bi-receipt"></i> Detail Transaksi
            </h4>

            <table class="table">
                <tr>
                    <th>Order ID</th>
                    <td>{{ $pembayaran->order_id }}</td>
                </tr>
                <tr>
                    <th>Tanggal Bayar</th>
                    <td>{{ $pembayaran->paid_at?->format('d-m-Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Periode</th>
                    <td>{{ $pembayaran->tagihan->periode ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nominal</th>
                    <td>Rp {{ number_format($pembayaran->nominal) }}</td>
                </tr>
                <tr>
                    <th>Metode</th>
                    <td>{{ strtoupper($pembayaran->metode) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-success">SUCCESS</span>
                    </td>
                </tr>
            </table>

            <a href="{{ route('user.riwayat.index') }}" 
               class="btn btn-secondary">
                Kembali
            </a>

            <a href="{{ route('user.riwayat.cetak', $pembayaran->id) }}" 
            class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

</div>
@endsection