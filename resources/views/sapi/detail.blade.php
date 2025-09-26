@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center" 
             style="background: linear-gradient(90deg, #007bff, #00bcd4); color: #fff;">
            <h5 class="m-0">🐄 Detail Sapi - <strong>{{ $sapi->jenis_sapi }} #{{ $sapi->id }}</strong></h5>
            <span class="badge bg-light text-dark px-3 py-2 shadow-sm">{{ ucfirst($sapi->status) }}</span>
        </div>
        <div class="card-body">

            <!-- Identitas Sapi -->
            <h5 class="card-title mb-3 text-primary">📌 Identitas Sapi</h5>
            <div class="col-md-6">
                <ul class="list-group mb-3 shadow-sm rounded">
                    <li class="list-group-item"><strong>Jenis:</strong> {{ $sapi->jenis_sapi }}</li>
                    <li class="list-group-item"><strong>Jenis Kelamin:</strong> {{ ucfirst($sapi->jenis_kelamin) }}</li>
                    <li class="list-group-item"><strong>Tanggal Lahir:</strong> 
                        {{ $sapi->tanggal_lahir ? \Carbon\Carbon::parse($sapi->tanggal_lahir)->format('d M Y') : '-' }}
                    </li>
                    <li class="list-group-item"><strong>Berat:</strong> {{ $sapi->berat_sapi ?? '-' }} Kg</li>
                    <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($sapi->status) }}</li>

                    <!-- Suhu Terbaru dari sensor_readings (tanpa tahu sapi) -->
                    @php
                        use App\Models\SensorReading;
                        $latestReading = SensorReading::latest('created_at')->first();
                    @endphp
                    <li class="list-group-item">
                        <strong>Suhu Terbaru:</strong>
                        @if($latestReading && $latestReading->suhu !== null)
                            {{ $latestReading->suhu }} °C
                        @else
                            <span class="text-muted">Belum ada data</span>
                        @endif
                    </li>

                    <!-- Jadwal Makan -->
                    <li class="list-group-item">
                        <strong>Jadwal Makan:</strong>
                        {{ $sapi->jadwal_makan ?? '-' }}
                    </li>
                </ul>
            </div>

            <!-- Informasi Kandang -->
            <h5 class="card-title mb-3 text-success">🏠 Informasi Kandang</h5>
            <div class="alert alert-info shadow-sm">
                <p class="mb-1"><strong>Nama Kandang:</strong> {{ optional($sapi->kandang)->nama_kandang ?? '-' }}</p>
                <p class="mb-1"><strong>Lokasi:</strong> {{ optional($sapi->kandang)->lokasi ?? '-' }}</p>
                <p class="mb-1"><strong>Kapasitas:</strong> {{ optional($sapi->kandang)->kapasitas ?? '-' }} ekor</p>
                <p class="mb-0"><strong>Deskripsi:</strong> {{ optional($sapi->kandang)->deskripsi ?? '-' }}</p>
            </div>

            <!-- Tombol QR -->
            <div class="d-flex justify-content-start mt-3">
                <a href="{{ route('sapi.qr', $sapi->id) }}" class="btn btn-success rounded-pill px-4 me-2">
                    📱 Lihat QR
                </a>
            </div>

            <!-- Tombol Kembali -->
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('kandang.detail', optional($sapi->kandang)->id ?? 0) }}" 
                   class="btn btn-outline-secondary rounded-pill px-4">
                    ⬅ Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
