@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Tagihan</h3>

    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Pelanggan</label>
            <input type="text"
                   class="form-control"
                   value="{{ $tagihan->pelanggan->nama }}"
                   readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Periode</label>
            <input type="text"
                   name="periode"
                   class="form-control"
                   value="{{ $tagihan->periode }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nominal</label>
            <input type="number"
                   name="nominal"
                   class="form-control"
                   value="{{ $tagihan->nominal }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="belum bayar"
                    {{ $tagihan->status=='belum bayar'?'selected':'' }}>
                    Belum Bayar
                </option>
                <option value="lunas"
                    {{ $tagihan->status=='lunas'?'selected':'' }}>
                    Lunas
                </option>
                <option value="menunggak"
                    {{ $tagihan->status=='menunggak'?'selected':'' }}>
                    Menunggak
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">
            Batal
        </a>
    </form>
</div>
@endsection
