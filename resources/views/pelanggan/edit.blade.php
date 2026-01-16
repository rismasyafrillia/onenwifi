@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Pelanggan</h3>

    <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Pelanggan</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   value="{{ old('nama', $pelanggan->nama) }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat"
                      class="form-control"
                      rows="3"
                      required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Paket Internet</label>
            <select name="paket_id" class="form-select" required>
                @foreach($pakets as $paket)
                    <option value="{{ $paket->id }}"
                        {{ $pelanggan->paket_id == $paket->id ? 'selected' : '' }}>
                        {{ $paket->nama_paket }} - Rp {{ number_format($paket->harga) }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">
                Bisa digunakan untuk upgrade / downgrade paket
            </small>
        </div>

        <div class="mb-3">
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

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Update
            </button>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
