@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Edit Pelanggan</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               value="{{ old('nama', $pelanggan->nama) }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No HP</label>
                        <input type="text"
                               name="no_hp"
                               class="form-control"
                               value="{{ old('no_hp', $pelanggan->no_hp) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat"
                                  class="form-control"
                                  rows="3"
                                  required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Paket Internet</label>
                        <select name="paket_id" class="form-select" required>
                            @foreach($pakets as $paket)
                                <option value="{{ $paket->id }}"
                                    {{ $pelanggan->paket_id == $paket->id ? 'selected' : '' }}>
                                    {{ $paket->nama_paket }} - Rp {{ number_format($paket->harga) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif" {{ $pelanggan->status == 'aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="nonaktif" {{ $pelanggan->status == 'nonaktif' ? 'selected' : '' }}>
                                Nonaktif
                            </option>
                        </select>
                    </div>

                </div>

                <div class="text-end">
                    <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button class="btn btn-primary">
                        Update Data
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
