@extends('layouts.app')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">📑 Data Tagihan Pelanggan</h4>

        <form action="{{ route('admin.tagihan.generate') }}"
              method="POST"
              onsubmit="return confirm('Generate tagihan bulan ini?')">
            @csrf
            <button class="btn btn-success">
                🔄 Generate Tagihan Bulanan
            </button>
        </form>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Periode</th>
                        <th>Nominal</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($tagihan as $t)
                    <tr>
                        <td>{{ $t->pelanggan->nama ?? '-' }}</td>
                        <td class="text-center">{{ $t->periode }}</td>
                        <td>Rp {{ number_format($t->nominal) }}</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($t->jatuh_tempo)->format('d-m-Y') }}
                        </td>
                        <td class="text-center">
                            <span class="badge 
                                @if($t->status == 'lunas') bg-success
                                @elseif($t->status == 'menunggak') bg-danger
                                @else bg-warning text-dark
                                @endif">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td>
                            @if(
                                $t->status == 'belum bayar' &&
                                $t->nominal > ($t->pelanggan->paket->harga ?? 0)
                            )
                                <span class="text-danger fw-semibold">
                                    ⚠ Termasuk tunggakan
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.tagihan.edit', $t->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Data tagihan belum tersedia
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
