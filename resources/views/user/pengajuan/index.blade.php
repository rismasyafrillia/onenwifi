@extends('layouts.user')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h3>Pengajuan Berhenti</h3>

        <a href="{{ route('user.pengajuan.create') }}"
           class="btn btn-danger">
            Ajukan Berhenti
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Catatan Admin</th>
            </tr>
        </thead>

        <tbody>
            @foreach($pengajuan as $p)
            <tr>
                <td>{{ $p->created_at->format('d-m-Y') }}</td>
                <td>{{ $p->alasan }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->catatan_admin ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection