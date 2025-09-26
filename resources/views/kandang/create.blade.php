@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="fw-bold mb-4">➕ Tambah Kandang</h3>

    <div class="card shadow-sm border-0 p-4">
        <form action="{{ route('kandang.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Kandang</label>
                <input type="text" name="nama_kandang" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Lokasi</label>
                <input type="text" name="lokasi" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kapasitas</label>
                <input type="number" name="kapasitas" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-success fw-semibold rounded-pill shadow-sm">
                ✅ Simpan
            </button>
            <a href="{{ route('home') }}" class="btn btn-secondary fw-semibold rounded-pill shadow-sm">
                ⬅ Kembali
            </a>
        </form>
    </div>
</div>
@endsection
