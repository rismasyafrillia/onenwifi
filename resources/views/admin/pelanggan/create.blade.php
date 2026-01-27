@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Pelanggan</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.pelanggan.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No HP</label>
                        <input type="text" name="no_hp" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Paket Internet</label>
                        <select name="paket_id" class="form-select" required>
                            <option value="">-- Pilih Paket --</option>
                            @foreach ($pakets as $paket)
                                <option value="{{ $paket->id }}">
                                    {{ $paket->nama_paket }}
                                    @if($paket->kecepatan)
                                        ({{ $paket->kecepatan }})
                                    @endif
                                    - Rp {{ number_format($paket->harga) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                </div>

                <div class="text-end">
                    <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button class="btn btn-success">
                        Simpan Pelanggan
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
