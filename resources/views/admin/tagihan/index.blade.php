@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3>Data Tagihan</h3>

        <div class="d-flex gap-2">

            <form action="{{ route('admin.tagihan.generate') }}" method="POST">
                @csrf
                <button class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Generate Tagihan Bulan Ini
                </button>
            </form>

            <form method="GET" class="d-flex gap-2">

                <select name="periode" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>

                    @foreach($listPeriode as $p)
                        <option value="{{ $p }}"
                            {{ request('periode') == $p ? 'selected' : '' }}>
                            {{ $p }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>

                    <option value="belum bayar"
                        {{ request('status') == 'belum bayar' ? 'selected' : '' }}>
                        Belum Bayar
                    </option>

                    <option value="menunggak"
                        {{ request('status') == 'menunggak' ? 'selected' : '' }}>
                        Menunggak
                    </option>

                    <option value="lunas"
                        {{ request('status') == 'lunas' ? 'selected' : '' }}>
                        Lunas
                    </option>
                </select>
            </form>
        </div>
    </div>

    @foreach($tagihan as $periode => $items)
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Periode {{ $periode }}
        </div>

        <table class="table table-bordered mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>ID Pelanggan</th>
                    <th>Pelanggan</th>
                    <th>Nominal</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $t)
                <tr>
                    <td>{{ $t->pelanggan->id }}</td>
                    <td>{{ $t->pelanggan->nama }}</td>
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

                    <td>
                        @php
                            $periodeIni = \Carbon\Carbon::createFromFormat('m-Y', $t->periode);
                            $adaTunggakan = \App\Models\Tagihan::where('pelanggan_id', $t->pelanggan_id)
                                ->whereIn('status', ['belum bayar', 'menunggak'])
                                ->get()
                                ->filter(fn($x) =>
                                    \Carbon\Carbon::createFromFormat('m-Y', $x->periode)
                                    ->lt($periodeIni)
                                )->count() > 0;
                        @endphp

                        @if($adaTunggakan)
                            <span class="text-danger fw-bold">
                                Ada tunggakan
                            </span>
                        @else
                            -
                        @endif
                    </td>

                    <td class="d-flex gap-1">

                        <a href="{{ route('admin.tagihan.detail', $t->pelanggan_id) }}"
                           class="btn btn-info btn-sm">
                            Detail
                        </a>

                        @if($t->status !== 'lunas')
                        <form method="POST"
                              action="{{ route('admin.tagihan.bayarCash', $t->id) }}">
                            @csrf

                            <button class="btn btn-success btn-sm"
                                onclick="return confirm('Bayar tagihan bulan ini?')">
                                Bayar Cash
                            </button>
                        </form>
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