@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Komplain Saya</h3>

    <a href="{{ route('komplain.create') }}" class="btn btn-primary mb-3">
        + Buat Komplain
    </a>

    @foreach($komplains as $k)
        <div class="card mb-2">
            <div class="card-body">
                <h5>{{ $k->judul }}</h5>
                <p>{{ $k->deskripsi }}</p>

                <span class="badge
                    @if($k->status=='baru') bg-warning
                    @elseif($k->status=='diproses') bg-info
                    @else bg-success
                    @endif">
                    {{ ucfirst($k->status) }}
                </span>

                @if($k->tanggapan_admin)
                    <hr>
                    <strong>Tanggapan Admin:</strong>
                    <p>{{ $k->tanggapan_admin }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
