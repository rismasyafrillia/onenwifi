@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detail Komplain</h3>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Pelanggan:</strong> {{ $komplain->pelanggan->nama }}</p>
            <p><strong>Judul:</strong> {{ $komplain->judul }}</p>
            <p><strong>Deskripsi:</strong></p>
            <p>{{ $komplain->deskripsi }}</p>
        </div>
    </div>

    <form method="POST"
          action="{{ route('admin.komplain.update', $komplain->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tanggapan Admin</label>
            <textarea name="tanggapan_admin"
                      class="form-control"
                      rows="4"
                      required>{{ $komplain->tanggapan_admin }}</textarea>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="diproses"
                    {{ $komplain->status=='diproses'?'selected':'' }}>
                    Diproses
                </option>
                <option value="selesai"
                    {{ $komplain->status=='selesai'?'selected':'' }}>
                    Selesai
                </option>
            </select>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.komplain.index') }}"
           class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
