@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- JUDUL --}}
    <div class="mb-4">
        <h3 class="fw-bold">Detail Komplain</h3>
        <small class="text-muted">Informasi komplain & tanggapan admin</small>
    </div>

    <div class="row g-4">

        {{-- INFO KOMPLAIN --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Data Komplain</h5>

                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Pelanggan</th>
                            <td>{{ $komplain->pelanggan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Judul</th>
                            <td>{{ $komplain->judul }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($komplain->status == 'baru')
                                    <span class="badge bg-danger">Baru</span>
                                @elseif($komplain->status == 'diproses')
                                    <span class="badge bg-warning text-dark">Diproses</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <label class="fw-semibold">Deskripsi Komplain</label>
                        <div class="p-3 bg-light rounded border">
                            {{ $komplain->deskripsi }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- FORM TANGGAPAN --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Tanggapan Admin</h5>

                    <form action="{{ route('admin.komplain.update', $komplain->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Tanggapan</label>
                            <textarea name="tanggapan_admin"
                                      class="form-control"
                                      rows="5"
                                      placeholder="Masukkan tanggapan admin...">{{ $komplain->tanggapan_admin }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="diproses"
                                    {{ $komplain->status == 'diproses' ? 'selected' : '' }}>
                                    Diproses
                                </option>
                                <option value="selesai"
                                    {{ $komplain->status == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.komplain.index') }}"
                               class="btn btn-secondary">
                                Kembali
                            </a>
                            <button class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
