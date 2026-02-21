@extends('layouts.user')

@section('content')
<div class="container">
    <h3 class="mb-4">Riwayat Transaksi</h3>

    @if($riwayat->isEmpty())
        <div class="alert alert-info">
            Belum ada transaksi yang berhasil.
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Tanggal Bayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->bulan }}</td>
                                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td>
                                    {{ $item->updated_at->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        Lunas
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
