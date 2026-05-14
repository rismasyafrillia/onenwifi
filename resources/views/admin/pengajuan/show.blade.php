@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Detail Pengajuan</h3>

    <div class="card">
        <div class="card-body">

            <p>
                <strong>Pelanggan:</strong>
                {{ $pengajuan->pelanggan->nama }}
            </p>

            <p>
                <strong>Alasan:</strong><br>
                {{ $pengajuan->alasan }}
            </p>

            <form method="POST"
                  action="{{ route('admin.pengajuan.update', $pengajuan->id) }}">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Status</label>

                    <select name="status" class="form-select">
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Catatan Admin</label>

                    <textarea name="catatan_admin"
                              class="form-control"></textarea>
                </div>

                <button class="btn btn-success">
                    Simpan
                </button>

            </form>

        </div>
    </div>

</div>
@endsection