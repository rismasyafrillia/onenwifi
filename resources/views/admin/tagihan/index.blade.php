@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Data Tagihan</h3>

        <form action="{{ route('admin.tagihan.generate') }}" method="POST"
              onsubmit="return confirm('Generate tagihan bulan ini?')">
            @csrf
            <button class="btn btn-success">
                🔄 Generate Tagihan Bulanan
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Pelanggan</th>
                <th>Periode</th>
                <th>Nominal</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tagihan as $t)
            <tr>
                <td>{{ $t->pelanggan->nama ?? '-' }}</td>
                <td>{{ $t->periode }}</td>
                <td>Rp {{ number_format($t->nominal) }}</td>
                <td>{{ \Carbon\Carbon::parse($t->jatuh_tempo)->format('d-m-Y') }}</td>
                <td>
                    <span class="badge 
                        @if($t->status == 'lunas') bg-success
                        @elseif($t->status == 'menunggak') bg-danger
                        @else bg-warning
                        @endif">
                        {{ ucfirst($t->status) }}
                    </span>
                </td>
                <td>
                    @if(
                        $t->status == 'belum bayar' &&
                        $t->nominal > ($t->pelanggan->paket->harga ?? 0)
                    )
                        <span class="text-danger">Termasuk tunggakan</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
