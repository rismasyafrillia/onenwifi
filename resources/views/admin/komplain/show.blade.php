@extends('layouts.app')

@section('content')
<h3>Detail Komplain</h3>

<p><b>Pelanggan:</b> {{ $komplain->pelanggan->nama }}</p>
<p><b>Judul:</b> {{ $komplain->judul }}</p>
<p><b>Deskripsi:</b></p>
<p>{{ $komplain->deskripsi }}</p>

<hr>

<form action="{{ route('admin.komplain.update', $komplain->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Tanggapan Admin</label>
        <textarea name="tanggapan_admin" class="form-control">
            {{ $komplain->tanggapan_admin }}
        </textarea>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
        </select>
    </div>

    <button class="btn btn-success">Simpan</button>
</form>
@endsection
