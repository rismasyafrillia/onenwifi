@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Komplain Saya</h3>
            <p class="text-muted mb-0">Daftar komplain yang pernah Anda kirim</p>
        </div>

        <a href="{{ route('user.komplain.create') }}" class="btn btn-warning text-dark">
            <i class="bi bi-plus-circle me-1"></i> Buat Komplain
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($komplains as $komplain)
                        <tr>
                            <td class="fw-semibold">
                                {{ $komplain->judul }}
                            </td>

                            <td>
                                <span class="badge 
                                    {{ $komplain->status == 'baru' ? 'bg-danger' :
                                       ($komplain->status == 'diproses' ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ ucfirst($komplain->status) }}
                                </span>
                            </td>

                            <td class="text-muted">
                                {{ $komplain->created_at->format('d M Y') }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('user.komplain.show', $komplain->id) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Belum ada komplain
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
