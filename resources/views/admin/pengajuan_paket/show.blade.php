@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">
            Detail Pengajuan Paket
        </h3>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-borderless">

                <tr>
                    <th width="220">
                        ID Pelanggan
                    </th>

                    <td>
                        {{ $pengajuan->pelanggan->id }}
                    </td>
                </tr>

                <tr>
                    <th>Nama Pelanggan</th>

                    <td>
                        {{ $pengajuan->pelanggan->nama }}
                    </td>
                </tr>

                <tr>
                    <th>Paket Lama</th>

                    <td>
                        {{ $pengajuan->paketLama->nama_paket }}
                    </td>
                </tr>

                <tr>
                    <th>Paket Baru</th>

                    <td>
                        {{ $pengajuan->paketBaru->nama_paket }}
                    </td>
                </tr>

                <tr>
                    <th>Alasan</th>

                    <td>
                        {{ $pengajuan->alasan }}
                    </td>
                </tr>

            </table>

            <hr>

            <form action="{{ route('admin.pengajuan-paket.update', $pengajuan->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Catatan Admin
                    </label>

                    <textarea name="catatan_admin"
                              class="form-control"
                              rows="4">{{ $pengajuan->catatan_admin }}</textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="disetujui">
                            Disetujui
                        </option>

                        <option value="ditolak">
                            Ditolak
                        </option>

                    </select>

                </div>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('admin.pengajuan-paket.index') }}"
                       class="btn btn-secondary">
                        Kembali
                    </a>

                    <button class="btn btn-success">
                        Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection