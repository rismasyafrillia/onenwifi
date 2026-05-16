@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">
            Detail Pengajuan Perubahan Paket
        </h3>

        <small class="text-muted">
            Kelola pengajuan perubahan paket pelanggan
        </small>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- WARNING AWAL BULAN --}}
    @if(now()->day > 5)

        <div class="alert alert-warning border-0 shadow-sm">

            <div class="d-flex align-items-center gap-2">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <div>
                    Perubahan paket hanya dapat diproses
                    pada tanggal <strong>1 - 5</strong> setiap bulan.
                </div>

            </div>

        </div>

    @endif

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <table class="table table-borderless align-middle">

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

                        <span class="badge bg-secondary">
                            {{ $pengajuan->paketLama->nama_paket }}
                        </span>

                    </td>
                </tr>

                <tr>
                    <th>Paket Baru</th>

                    <td>

                        <span class="badge bg-primary">
                            {{ $pengajuan->paketBaru->nama_paket }}
                        </span>

                    </td>
                </tr>

                <tr>
                    <th>Status Pengajuan</th>

                    <td>

                        @if($pengajuan->status == 'pending')

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @elseif($pengajuan->status == 'disetujui')

                            <span class="badge bg-success">
                                Disetujui
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Ditolak
                            </span>

                        @endif

                    </td>
                </tr>

                <tr>
                    <th>Alasan</th>

                    <td>
                        {{ $pengajuan->alasan }}
                    </td>
                </tr>

                <tr>
                    <th>Tanggal Pengajuan</th>

                    <td>
                        {{ $pengajuan->created_at->format('d M Y H:i') }}
                    </td>
                </tr>

            </table>

            <hr>

            {{-- FORM --}}
            <form action="{{ route('admin.pengajuan-paket.update', $pengajuan->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Catatan Admin
                    </label>

                    <textarea name="catatan_admin"
                              class="form-control"
                              rows="4"
                              placeholder="Tambahkan catatan jika diperlukan">{{ $pengajuan->catatan_admin }}</textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="disetujui"
                            {{ $pengajuan->status == 'disetujui' ? 'selected' : '' }}>
                            Disetujui
                        </option>

                        <option value="ditolak"
                            {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>
                            Ditolak
                        </option>

                    </select>

                </div>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('admin.pengajuan-paket.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>
                        Kembali

                    </a>

                    <button class="btn btn-success"
                        {{ now()->day > 5 ? 'disabled' : '' }}>

                        <i class="bi bi-check-circle"></i>
                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection