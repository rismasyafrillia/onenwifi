@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Data Komplain Pelanggan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($komplains as $k)
            <tr>
                <td>{{ $k->pelanggan->nama }}</td>
                <td>{{ $k->judul }}</td>
                <td>
                    <span class="badge
                        @if($k->status=='baru') bg-warning
                        @elseif($k->status=='diproses') bg-info
                        @else bg-success
                        @endif">
                        {{ ucfirst($k->status) }}
                    </span>
                </td>
                <td>{{ $k->created_at->format('d-m-Y') }}</td>
                <td>
                    <a href="{{ route('admin.komplain.show', $k->id) }}"
                       class="btn btn-sm btn-primary">
                        Detail
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
