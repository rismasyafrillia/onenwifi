@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Tagihan</h3>

    <form action="{{ route('admin.tagihan.update', $tagihan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Pelanggan</label>
            <input type="text"
                   class="form-control"
                   value="{{ $tagihan->pelanggan->nama }}"
                   readonly>
        </div>

        <div class="mb-3">
            <label>Nominal</label>
            <input type="number"
                   name="nominal"
                   class="form-control"
                   value="{{ $tagihan->nominal }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Jatuh Tempo</label>
            <input type="date"
                   name="jatuh_tempo"
                   class="form-control"
                   value="{{ $tagihan->jatuh_tempo->format('Y-m-d') }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="belum bayar" {{ $tagihan->status=='belum bayar'?'selected':'' }}>
                    Belum Bayar
                </option>
                <option value="menunggak" {{ $tagihan->status=='menunggak'?'selected':'' }}>
                    Menunggak
                </option>
                <option value="lunas" {{ $tagihan->status=='lunas'?'selected':'' }}>
                    Lunas
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.tagihan.index') }}" class="btn btn-secondary">
            Batal
        </a>
    </form>
</div>
@endsection
