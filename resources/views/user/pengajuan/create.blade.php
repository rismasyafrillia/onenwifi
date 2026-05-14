@extends('layouts.user')

@section('content')
<div class="container">

    <div class="card">
        <div class="card-body">

            <h4 class="mb-3">Ajukan Berhenti Berlangganan</h4>

            <form method="POST"
                  action="{{ route('user.pengajuan.store') }}">
                @csrf

                <div class="mb-3">
                    <label>Alasan</label>

                    <textarea name="alasan"
                              class="form-control"
                              rows="5"
                              required></textarea>
                </div>

                <button class="btn btn-danger">
                    Kirim Pengajuan
                </button>

            </form>

        </div>
    </div>

</div>
@endsection