@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">Buat Komplain</h3>
        <p class="text-muted">Sampaikan keluhan atau kendala layanan Anda</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('user.komplain.store') }}" method="POST">
                @csrf

                <!-- JUDUL -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Komplain</label>
                    <input type="text" name="judul" class="form-control"
                           placeholder="Contoh: Internet sering putus"
                           required>
                </div>

                <!-- DESKRIPSI -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="5"
                              placeholder="Jelaskan kendala yang Anda alami secara detail"
                              required></textarea>
                </div>

                <!-- BUTTON -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('user.komplain.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button class="btn btn-warning text-dark">
                        <i class="bi bi-send me-1"></i> Kirim Komplain
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
