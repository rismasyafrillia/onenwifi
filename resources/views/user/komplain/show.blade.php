@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">Detail Komplain</h3>
        <small class="text-muted">Status & balasan dari admin</small>
    </div>

    <div class="row g-4">

        {{-- KOMPLAIN --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Komplain Anda</h5>

                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Judul</th>
                            <td>{{ $komplain->judul }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($komplain->status == 'baru')
                                    <span class="badge bg-danger">Baru</span>
                                @elseif($komplain->status == 'diproses')
                                    <span class="badge bg-warning text-dark">Diproses</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <label class="fw-semibold">Deskripsi</label>
                        <div class="p-3 bg-light rounded border">
                            {{ $komplain->deskripsi }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- BALASAN ADMIN --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Balasan Admin</h5>

                    @if($komplain->tanggapan_admin)
                        <div class="p-3 bg-success-subtle border rounded">
                            {{ $komplain->tanggapan_admin }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            ⏳ Menunggu tanggapan admin
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <div class="mt-4">
        <a href="{{ route('user.komplain.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

</div>
@endsection
