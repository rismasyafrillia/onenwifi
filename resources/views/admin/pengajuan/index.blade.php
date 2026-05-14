@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">Pengajuan Berhenti Berlangganan</h3>
        <p class="text-muted">Daftar pengajuan pelanggan</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID Pelanggan</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pengajuans as $p)
                    <tr>
                        <td>{{ $p->pelanggan->id }}</td>
                        <td>{{ $p->pelanggan->nama }}</td>
                        <td>{{ $p->created_at->format('d-m-Y H:i') }}</td>

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

                        <td class="text-center">
                            <a href="{{ route('admin.pengajuan.show', $p->id) }}"
                               class="btn btn-primary btn-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
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