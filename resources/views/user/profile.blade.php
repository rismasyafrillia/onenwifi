@extends('layouts.user')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Profil Saya
                    </h5>
                </div>

                <div class="card-body">

                    {{-- DATA AKUN --}}
                    <h6 class="text-muted mb-3">Informasi Akun</h6>

                    <div class="row mb-2">
                        <div class="col-4 text-muted">Nama</div>
                        <div class="col-8 fw-semibold">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 text-muted">Email</div>
                        <div class="col-8 fw-semibold">
                            {{ auth()->user()->email }}
                        </div>
                    </div>

                    <hr>

                    {{-- DATA PELANGGAN --}}
                    <h6 class="text-muted mb-3">Data Pelanggan</h6>

                    <div class="row mb-2">
                        <div class="col-4 text-muted">Alamat</div>
                        <div class="col-8">
                            {{ $pelanggan->alamat ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-4 text-muted">No HP</div>
                        <div class="col-8">
                            {{ $pelanggan->no_hp ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-4 text-muted">Status</div>
                        <div class="col-8">
                            @if(($pelanggan->status ?? '') == 'aktif')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i> Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('user.dashboard') }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
