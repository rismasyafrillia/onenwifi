@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Daftar Pembayaran Bulan {{ now()->translatedFormat('F Y') }}</h4>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th>Periode Tagihan</th>
                        <th>Metode</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $p)
                        <tr>
                            <td>{{ $p->paid_at?->format('d-m-Y H:i') }}</td>
                            <td>{{ $p->tagihan->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $p->tagihan->periode ?? '-' }}</td>
                            <td>{{ strtoupper($p->metode) }}</td>
                            <td>Rp {{ number_format($p->nominal,0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pembayaran bulan ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection