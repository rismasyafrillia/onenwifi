@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Data Pelanggan</h3>

    <a href="{{ route('pelanggan.create') }}" class="btn btn-primary mb-3">
        Tambah Pelanggan
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Username</th>
                <th>Paket</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggan as $p)
            <tr>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->alamat }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>{{ $p->user->email ?? '-' }}</td>
                <td>{{ $p->paket->nama_paket ?? '-' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>
                <a href="{{ route('pelanggan.edit', $p->id) }}"
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form action="{{ route('pelanggan.destroy', $p->id) }}"
                      method="POST"
                      style="display:inline;"
                      onsubmit="return confirm('Yakin hapus pelanggan ini? Akun login juga akan terhapus!')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        Hapus
                    </button>
                </form>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
