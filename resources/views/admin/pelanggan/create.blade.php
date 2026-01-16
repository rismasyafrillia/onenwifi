@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Pelanggan</h3>

    <form action="{{ route('admin.pelanggan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control">
        </div>

        <div class="mb-3">
            <label>Paket</label>
            <select name="paket_id" class="form-control" required>
                <option value="">-- Pilih Paket --</option>
                @foreach ($pakets as $paket)
                    <option value="{{ $paket->id }}">
                        {{ $paket->nama_paket }}
                        @if(isset($paket->kecepatan))
                            ({{ $paket->kecepatan }})
                        @endif
                        - Rp{{ number_format($paket->harga) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection