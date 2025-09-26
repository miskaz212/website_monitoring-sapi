@extends('layouts.app')

@section('content')
<div class="container mt-4 text-center">
    <div class="card shadow-lg border-0 rounded-4 p-4">
        <h5 class="mb-3">🐄 QR Code untuk <strong>{{ $sapi->jenis_sapi }} #{{ $sapi->id }}</strong></h5>

        <div class="mb-4">
            {!! QrCode::size(250)->generate($data) !!}
        </div>

        <p class="text-muted">Scan QR untuk membuka detail sapi ini.</p>

        <a href="{{ route('sapi.show', $sapi->id) }}" class="btn btn-outline-primary rounded-pill px-4">
            ⬅ Kembali ke Detail
        </a>
    </div>
</div>
@endsection
