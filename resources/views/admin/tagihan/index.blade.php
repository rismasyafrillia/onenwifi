@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h3>Data Tagihan</h3>

        <form action="{{ route('admin.tagihan.generate') }}" method="POST">
            @csrf
            <button class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Generate Tagihan Bulan Ini
            </button>
        </form>
        
        <form method="GET">
            <select name="periode" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Periode</option>
                @foreach($listPeriode as $p)
                    <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>
                        {{ $p }}
                    </option>
                @endforeach
            </select>
        </form>

    </div>

    @foreach($tagihan as $periode => $items)
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Periode {{ $periode }}
        </div>

        <table class="table table-bordered mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>Pelanggan</th>
                    <th>Nominal</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $t)
                <tr>
                    <td>{{ $t->pelanggan->nama }}</td>
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

                    {{-- KETERANGAN OPSI A --}}
                    <td>
                        @php
                            $periodeIni = \Carbon\Carbon::createFromFormat('m-Y', $t->periode);
                            $adaTunggakan = \App\Models\Tagihan::where('pelanggan_id', $t->pelanggan_id)
                                ->whereIn('status', ['belum bayar', 'menunggak'])
                                ->get()
                                ->filter(fn($x) =>
                                    \Carbon\Carbon::createFromFormat('m-Y', $x->periode)->lt($periodeIni)
                                )->count() > 0;
                        @endphp

                        @if($adaTunggakan)
                            <span class="text-danger fw-bold">Ada tunggakan</span>
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        @if($t->status !== 'lunas')
                        <form method="POST" action="{{ route('admin.tagihan.bayarCash', $t->id) }}">
                            @csrf
                            <button class="btn btn-success btn-sm"
                                onclick="return confirm('Bayar semua tagihan sampai bulan ini?')">
                                Bayar Cash
                            </button>
                        </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

</div>
@endsection
