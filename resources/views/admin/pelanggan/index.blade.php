@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Data Pelanggan</h4>
        <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary">
            + Tambah Pelanggan
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Username</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($pelanggan as $p)
                    <tr>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->alamat }}</td>
                        <td>{{ $p->no_hp }}</td>
                        <td>{{ $p->user->email ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $p->paket->nama_paket ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $p->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="text-center">

                            <a href="{{ route('admin.pelanggan.edit', $p->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.pelanggan.destroy', $p->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin hapus pelanggan ini? Akun login juga akan terhapus!')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Data pelanggan belum tersedia
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
