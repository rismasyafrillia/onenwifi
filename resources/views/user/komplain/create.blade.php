@extends('layouts.app')

@section('content')
<h3>Buat Komplain</h3>

<form action="{{ route('user.komplain.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Pelanggan</label>
        <select name="pelanggan_id" class="form-control">
            @foreach ($pelanggans as $p)
                <option value="{{ $p->id }}">{{ $p->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control">
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control"></textarea>
    </div>

    <button class="btn btn-success">Kirim</button>
</form>
@endsection
