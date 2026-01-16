@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Pelanggan</h3>

    <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   value="{{ old('nama', $pelanggan->nama) }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat"
                      class="form-control"
                      required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Paket</label>
            <select name="paket_id" class="form-select" required>
                @foreach($pakets as $paket)
                    <option value="{{ $paket->id }}"
                        {{ $pelanggan->paket_id == $paket->id ? 'selected' : '' }}>
                        {{ $paket->nama_paket }} - Rp {{ number_format($paket->harga) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select" required>
                <option value="aktif" {{ $pelanggan->status=='aktif'?'selected':'' }}>
                    Aktif
                </option>
                <option value="nonaktif" {{ $pelanggan->status=='nonaktif'?'selected':'' }}>
                    Nonaktif
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">
            Batal
        </a>
    </form>
</div>
@endsection
