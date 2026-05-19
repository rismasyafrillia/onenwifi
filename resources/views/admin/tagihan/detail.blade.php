@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Detail Pembayaran - {{ $pelanggan->nama }}
            </h5>

            <a href="{{ route('admin.tagihan.index') }}"
               class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <tr>
                    <th width="200">ID Pelanggan</th>
                    <td>{{ $pelanggan->id }}</td>
                </tr>

                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $pelanggan->nama }}</td>
                </tr>
            </table>

            <hr>

            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Periode</th>
                        <th>Nominal</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tagihan as $t)
                    <tr>
                        <td>{{ $t->periode }}</td>

                        <td>
                            Rp {{ number_format($t->nominal) }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($t->jatuh_tempo)->format('d-m-Y') }}
                        </td>

                        <td>
                            <span class="badge
                                @if($t->status == 'lunas') bg-success
                                @elseif($t->status == 'menunggak') bg-danger
                                @else bg-warning
                                @endif">

                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Belum ada data tagihan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection