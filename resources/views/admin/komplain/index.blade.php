@extends('layouts.app')

@section('content')
<h3 class="mb-3">Daftar Komplain</h3>

<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    @foreach ($komplains as $k)
        <tr class="
            {{ $k->status == 'baru' ? 'table-danger' : '' }}
            {{ $k->status == 'diproses' ? 'table-warning' : '' }}
        ">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $k->pelanggan->nama }}</td>
            <td>{{ $k->judul }}</td>
            <td>
                @if($k->status == 'baru')
                    <span class="badge bg-danger">Baru</span>
                @elseif($k->status == 'diproses')
                    <span class="badge bg-warning text-dark">Diproses</span>
                @else
                    <span class="badge bg-success">Selesai</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.komplain.show', $k->id) }}"
                   class="btn btn-info btn-sm">
                    Detail
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
