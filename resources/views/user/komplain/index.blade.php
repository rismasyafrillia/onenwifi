@extends('layouts.user')

@section('content')
<h3 class="mb-3">Komplain Saya</h3>

<a href="{{ route('user.komplain.create') }}" class="btn btn-warning mb-3">
    + Buat Komplain
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Status</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($komplains as $komplain)
        <tr>
            <td>{{ $komplain->judul }}</td>
            <td>
                <span class="badge bg-{{ 
                    $komplain->status == 'baru' ? 'warning' :
                    ($komplain->status == 'diproses' ? 'info' : 'success')
                }}">
                    {{ ucfirst($komplain->status) }}
                </span>
            </td>
            <td>{{ $komplain->created_at->format('d-m-Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
