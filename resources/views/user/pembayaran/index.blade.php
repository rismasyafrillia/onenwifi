@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Riwayat Pembayaran</h3>
        <p class="text-muted">Daftar pembayaran yang pernah Anda lakukan</p>
    </div>

    @if($pembayarans->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-clock-history fs-1 text-muted mb-3"></i>
                <p class="text-muted mb-0">
                    Belum ada riwayat pembayaran.
                </p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayarans as $p)
                        <tr>
                            <td>{{ $p->paid_at?->format('d-m-Y') }}</td>
                            <td>{{ $p->tagihan->periode ?? '-' }}</td>
                            <td class="fw-semibold">
                                Rp {{ number_format($p->nominal) }}
                            </td>
                            <td>{{ strtoupper($p->metode ?? '-') }}</td>
                            <td>
                                <span class="badge 
                                    {{ $p->status == 'success' ? 'bg-success' : 
                                    ($p->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ strtoupper($p->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('user.riwayat.show', $p->id) }}" 
                                class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-receipt"></i> Detail
                                </a>
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
