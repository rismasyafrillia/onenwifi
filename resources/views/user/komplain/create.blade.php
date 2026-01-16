@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Buat Komplain</h3>

    <form method="POST" action="{{ route('komplain.store') }}">
        @csrf

    <select name="pelanggan_id" required>
        <option value="">-- Pilih Pelanggan --</option>
        @foreach($pelanggans as $p)
            <option value="{{ $p->id }}">{{ $p->nama }}</option>
        @endforeach
    </select>

        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
        </div>

        <button class="btn btn-success">Kirim</button>
    </form>
</div>
@endsection
