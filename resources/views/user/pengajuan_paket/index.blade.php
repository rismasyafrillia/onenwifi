@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Pengajuan Perubahan Paket</h3>
            <small class="text-muted">
                Riwayat pengajuan perubahan paket internet
            </small>
        </div>

        <a href="{{ route('user.pengajuan-paket.create') }}"
           class="btn btn-primary">
            + Ajukan Perubahan
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle">

                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Tanggal</th>
                        <th>Paket Lama</th>
                        <th>Paket Baru</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pengajuans as $p)
                    <tr>

                        <td>
                            {{ $p->created_at->format('d-m-Y H:i') }}
                        </td>

                        <td>
                            {{ $p->paketLama->nama_paket ?? '-' }}
                        </td>

                        <td>
                            {{ $p->paketBaru->nama_paket ?? '-' }}
                        </td>

                        <td class="text-center">

                            @if($p->status == 'menunggu')
                                <span class="badge bg-warning text-dark">
                                    Menunggu
                                </span>

                            @elseif($p->status == 'disetujui')
                                <span class="badge bg-success">
                                    Disetujui
                                </span>

                            @else
                                <span class="badge bg-danger">
                                    Ditolak
                                </span>
                            @endif

                        </td>

                    </tr>
                    @empty

                    <tr>
                        <td colspan="4"
                            class="text-center text-muted">
                            Belum ada pengajuan
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection