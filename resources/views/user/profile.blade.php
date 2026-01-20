@extends('layouts.user')

@section('content')
<h3 class="mb-4">Profil Saya</h3>

<div class="card">
    <div class="card-body">
        <p><strong>Nama:</strong> {{ $pelanggan->nama ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $pelanggan->alamat ?? '-' }}</p>
        <p><strong>No HP:</strong> {{ $pelanggan->no_hp ?? '-' }}</p>
    </div>
</div>
@endsection
