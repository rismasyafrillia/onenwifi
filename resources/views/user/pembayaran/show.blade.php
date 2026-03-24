@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h4 class="fw-bold mb-4">
                Detail Pembayaran
            </h4>

            <table class="table table-bordered">
                <tr>
                    <th width="30%">No. Order</th>
                    <td>{{ $pembayaran->order_id }}</td>
                </tr>

                <tr>
                    <th>Tanggal Bayar</th>
                    <td>
                        {{ $pembayaran->paid_at 
                            ? \Carbon\Carbon::parse($pembayaran->paid_at)->format('d-m-Y H:i')
                            : '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Metode</th>
                    <td>{{ strtoupper($pembayaran->metode) }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-success">
                            {{ strtoupper($pembayaran->status) }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Total Dibayar</th>
                    <td class="fw-bold text-primary">
                        Rp {{ number_format($pembayaran->nominal,0,',','.') }}
                    </td>
                </tr>

                {{-- SUPPORT MULTI BULAN --}}
                <tr>
                    <th>Periode Dibayar</th>
                    <td>
                        @if($pembayaran->keterangan)
                            @php
                                $periodeList = explode(',', $pembayaran->keterangan);
                            @endphp

                            <ul class="mb-0">
                                @foreach($periodeList as $periode)
                                    <li>{{ trim($periode) }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{-- fallback kalau belum ada kolom keterangan --}}
                            {{ optional($pembayaran->tagihan)->periode ?? '-' }}
                        @endif
                    </td>
                </tr>

            </table>

            <div class="mt-3">
                <a href="{{ route('user.riwayat.cetak', $pembayaran->id) }}" 
                   class="btn btn-primary">
                    Download Struk PDF
                </a>

                <a href="{{ route('user.riwayat.index') }}" 
                   class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>
@endsection