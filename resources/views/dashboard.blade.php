@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success shadow-sm rounded-3 animate__animated animate__fadeInDown">
        {{ session('success') }}
    </div>
@endif

<!-- Background -->
<div style="background: linear-gradient(135deg, #fdfbfb, #ebedee); min-height: 100vh; padding: 30px; border-radius: 20px;">

    <!-- Statistik -->
    <div class="row g-4">
        <!-- Total Sapi -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 h-100 card-statistik text-white"
                 style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 25px;">
                <div class="card-body d-flex align-items-center">
                    <div class="me-4 display-5">
                        🐄
                    </div>
                    <div>
                        <p class="fw-light mb-1">Total Sapi</p>
                        <h2 class="fw-bold mb-0">{{ $totalSapi }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kandang -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 h-100 card-statistik text-white"
                 style="background: linear-gradient(135deg, #11998e, #38ef7d); border-radius: 25px;">
                <div class="card-body d-flex align-items-center">
                    <div class="me-4 display-5">
                        🏠
                    </div>
                    <div>
                        <p class="fw-light mb-1">Total Kandang</p>
                        <h2 class="fw-bold mb-0">{{ $totalKandang }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header daftar kandang -->
    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <h3 class="fw-bold text-dark">📊 Daftar Kandang</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-primary shadow-sm fw-semibold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#tambahKandangModal">
                + Tambah Kandang
            </button>
            <a href="{{ route('sensor.create') }}" class="btn btn-success shadow-sm fw-semibold rounded-pill px-4">
                + Tambah Data Sensor
            </a>
        </div>
    </div>

    <!-- Card Kandang -->
    <div class="row">
    @foreach($kandangs as $kandang)
        <div class="col-md-4">
            <div class="card kandang-card shadow-lg border-0 mb-4 h-100 d-flex flex-column animate__animated animate__fadeInUp kandang-hover"
                 style="border-radius: 25px; overflow: hidden; transition: 0.3s;">
                
                <!-- Header Gradient -->
                <div class="p-3 text-white fw-bold"
                     style="background: linear-gradient(135deg, #007bff, #00bcd4);">
                    🏡 {{ $kandang->nama_kandang }}
                </div>
                
                <div class="card-body d-flex flex-column p-4">
                    <!-- Lokasi -->
                    <p class="text-muted mb-3 small">
                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ $kandang->lokasi ?? '-' }}
                    </p>
                    
                    <!-- Kapasitas & Jumlah Sapi -->
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                            🛑 Kapasitas: <strong>{{ $kandang->kapasitas ?? 0 }}</strong>
                        </span>
                        <span class="badge bg-secondary px-3 py-2 rounded-pill shadow-sm">
                            🐄 Jumlah: <strong>{{ $kandang->sapis->count() }}</strong>
                        </span>
                    </div>

                    <!-- Progress kapasitas -->
                    @if($kandang->kapasitas > 0)
                        @php
                            $persen = round(($kandang->sapis->count() / $kandang->kapasitas) * 100);
                        @endphp
                        <div class="mb-3">
                            <div class="progress" style="height: 12px; border-radius: 30px;">
                                <div class="progress-bar {{ $persen >= 80 ? 'bg-danger' : 'bg-success' }}" 
                                    role="progressbar" style="width: {{ $persen }}%;" 
                                    aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $persen }}%
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr class="my-3">

                    <!-- Data Sensor -->
                    @if($kandang->latestSensorReading)
                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="sensor-box bg-light">
                                <i class="bi bi-cow text-info fs-4 me-2"></i>
                                <span class="fw-semibold">Identitas:</span> <span>Sapi</span>
                            </div>
                            <div class="sensor-box bg-light">
                                <i class="bi bi-thermometer-half text-danger fs-4 me-2"></i>
                                <span class="fw-semibold">Suhu:</span> <span>- °C</span>
                            </div>
                            <div class="sensor-box bg-light">
                                <i class="bi bi-basket text-success fs-4 me-2"></i>
                                <span class="fw-semibold">Berat Pakan:</span> <span>- Kg</span>
                            </div>
                        </div>
                    @else
                        <p class="text-muted fst-italic">❌ Tidak ada data sensor terbaru</p>
                    @endif

                    <!-- Tombol -->
                    <div class="mt-auto">
                        <a href="{{ route('kandang.detail', $kandang->id) }}" 
                           class="btn btn-gradient w-100 mt-3 fw-semibold rounded-pill shadow-sm">
                            🔍 Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<style>
    /* Hover efek untuk card */
    .kandang-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    /* Sensor info box */
    .sensor-box {
        padding: 10px 15px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    /* Tombol gradient */
    .btn-gradient {
        background: linear-gradient(90deg, #007bff, #00bcd4);
        color: white;
        border: none;
    }
    .btn-gradient:hover {
        background: linear-gradient(90deg, #0056b3, #0097a7);
        color: #fff;
    }
</style>

    <!-- Modal Tambah Kandang -->
    <div class="modal fade" id="tambahKandangModal" tabindex="-1" aria-labelledby="tambahKandangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 shadow-lg">
                <form action="{{ route('kandang.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title">➕ Tambah Kandang Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kandang</label>
                            <input type="text" class="form-control" name="nama_kandang" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" name="lokasi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" name="kapasitas" required min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">❌ Batal</button>
                        <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<style>
    .kandang-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .card-statistik {
        transition: transform 0.3s ease;
    }
    .card-statistik:hover {
        transform: scale(1.05);
    }
</style>
@endsection
