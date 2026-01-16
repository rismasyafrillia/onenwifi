@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Tambah Tagihan</h3>

    <form action="{{ route('tagihan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Pelanggan</label>
            <select name="pelanggan_id" class="form-select" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Periode</label>
            <input type="text"
                   name="periode"
                   class="form-control"
                   placeholder="Contoh: Januari 2026"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nominal</label>
            <input type="number"
                   name="nominal"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jatuh Tempo</label>
            <input type="date"
                   name="jatuh_tempo"
                   class="form-control"
                   required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">
            Batal
        </a>
    </form>
</div>
@endsection
