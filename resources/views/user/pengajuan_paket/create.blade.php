@extends('layouts.user')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">
            Ajukan Perubahan Paket
        </h3>

        <small class="text-muted">
            Silakan pilih paket baru yang diinginkan
        </small>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('user.pengajuan-paket.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">
                        Paket Saat Ini
                    </label>

                    <input type="text"
                           class="form-control"
                           value="{{ $pelanggan->paket->nama_paket }}"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Pilih Paket Baru
                    </label>

                    <select name="paket_baru_id"
                            class="form-select"
                            required>

                        <option value="">
                            -- Pilih Paket --
                        </option>

                        @foreach($pakets as $paket)
                        <option value="{{ $paket->id }}">
                            {{ $paket->nama_paket }}
                            - Rp {{ number_format($paket->harga) }}
                        </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Alasan
                    </label>

                    <textarea name="alasan"
                              class="form-control"
                              rows="4"
                              required></textarea>
                </div>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('user.pengajuan-paket.index') }}"
                       class="btn btn-secondary">
                        Kembali
                    </a>

                    <button class="btn btn-primary">
                        Kirim Pengajuan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection