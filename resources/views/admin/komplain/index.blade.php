@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- JUDUL --}}
    <div class="mb-4">
        <h3 class="fw-bold">Daftar Komplain</h3>
        <small class="text-muted">Kelola dan tindak lanjuti komplain pelanggan</small>
    </div>

    <form method="GET" class="mb-3 d-flex gap-2">
        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
            <option value="">Semua Status</option>

            <option value="baru"
                {{ request('status') == 'baru' ? 'selected' : '' }}>
                Baru
            </option>

            <option value="diproses"
                {{ request('status') == 'diproses' ? 'selected' : '' }}>
                Diproses
            </option>

            <option value="selesai"
                {{ request('status') == 'selesai' ? 'selected' : '' }}>
                Selesai
            </option>
        </select>

        <button class="btn btn-primary">
            Cari
        </button>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th>Pelanggan</th>
                            <th>Judul Komplain</th>
                            <th>Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach ($komplains as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $k->pelanggan->nama }}</td>
                            <td>{{ $k->judul }}</td>
                            <td>
                                @if($k->status == 'baru')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-circle"></i> Baru
                                    </span>
                                @elseif($k->status == 'diproses')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split"></i> Diproses
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Selesai
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.komplain.show', $k->id) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
