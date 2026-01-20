@extends('layouts.user')

@section('content')
<h3>Tagihan Saya</h3>

@if($tagihans->isEmpty())
    <div class="alert alert-info">
        Belum ada tagihan untuk akun Anda.
        <br>
        Silakan hubungi admin jika ini kesalahan.
    </div>
@else
<table class="table table-bordered">
<tr>
    <th>Periode</th>
    <th>Nominal</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

@foreach($tagihans as $t)
<tr>
    <td>{{ $t->periode }}</td>
    <td>Rp {{ number_format($t->nominal) }}</td>
    <td>
        <span class="badge bg-{{ $t->status == 'lunas' ? 'success' : 'danger' }}">
            {{ ucfirst($t->status) }}
        </span>
    </td>
    <td>
        <a href="{{ route('user.tagihan.show', $t->id) }}" class="btn btn-sm btn-info">
            Detail
        </a>
    </td>
</tr>
@endforeach
</table>
@endif
@endsection
