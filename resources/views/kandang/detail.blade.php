@extends('layouts.app') 

@section('content')
<h4 class="mt-3 mb-4 fw-bold">📋 Daftar Sapi di {{ $kandang->nama_kandang }}</h4>

<!-- Tombol Aksi -->
<div class="mb-4 d-flex gap-2">
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        ⬅️ Kembali
    </a>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahSapiModal">
        ➕ Tambah Sapi
    </button>
</div>

<!-- Daftar Sapi -->
<div class="row g-4">
    @forelse($kandang->sapis as $sapi)
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100 overflow-hidden sapi-card">
                
                <!-- Header Card -->
                <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #4caf50, #2e7d32);">
                    🐄 Sapi #{{ $sapi->id }}
                    <span class="badge bg-light text-dark shadow-sm">
                        {{ ucfirst($sapi->jenis_sapi ?? 'Unknown') }}
                    </span>
                </div>

                <div class="card-body">
                    <!-- Jenis Kelamin -->
                    <p class="mb-2">
                        <strong>Jenis Kelamin:</strong><br>
                        @if(strtolower($sapi->jenis_kelamin) == 'jantan')
                            <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm"> ♂ Jantan </span>
                        @elseif(strtolower($sapi->jenis_kelamin) == 'betina')
                            <span class="badge bg-pink px-3 py-2 rounded-pill shadow-sm"> ♀ Betina </span>
                        @else
                            <span class="badge bg-secondary">Tidak Diketahui</span>
                        @endif
                    </p>

      

                    <!-- Tombol Detail -->
                    <a href="{{ route('sapi.show', $sapi->id) }}" 
                       class="btn btn-outline-primary w-100 rounded-pill shadow-sm">
                        🔍 Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center fw-semibold shadow-sm rounded-3">
                ❌ Belum ada sapi di kandang ini.
            </div>
        </div>
    @endforelse
</div>

<!-- Statistik Kandang -->
<hr>
<h5 class="mt-4 fw-bold">📊 Perbandingan Kebutuhan vs Sensor</h5>

<div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-3">
    <div class="card-body p-4">
        <div class="row g-3">
            <!-- Jumlah Sapi -->
            <div class="col-md-4">
                <div class="stat-box bg-light rounded-3 shadow-sm p-3 text-center">
                    <h6 class="text-muted">Jumlah Sapi</h6>
                    <h4 class="fw-bold text-primary mb-0">🐄 {{ $kandang->sapis->count() }}</h4>
                </div>
            </div>

            <!-- Kebutuhan Pakan -->
            <div class="col-md-4">
                <div class="stat-box bg-light rounded-3 shadow-sm p-3 text-center">
                    <h6 class="text-muted">Kebutuhan Pakan</h6>
                    <h4 class="fw-bold text-success mb-0">🌾 {{ number_format($kebutuhanPakan, 2) }} Kg</h4>
                </div>
            </div>

            <!-- Sensor Pakan Terakhir -->
           <!-- Sensor Pakan Terakhir -->
<div class="col-md-4">
    <div class="stat-box bg-light rounded-3 shadow-sm p-3 text-center">
        <h6 class="text-muted">Sensor Terakhir</h6>

        @php
            // Ambil pakan terakhir dari semua sapi di kandang
            $latestPakanGlobal = $kandang->sapis
                ->map(fn($sapi) => $sapi->latestPakan)
                ->filter() // buang null
                ->sortByDesc('created_at')
                ->first();
        @endphp

        @if($latestPakanGlobal)
            <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                🌾 {{ number_format($latestPakanGlobal->berat, 2) }} Kg
            </span>
        @else
            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                ❌ Belum ada data
            </span>
        @endif
    </div>
</div>

        <!-- Progress Bar -->
        @if($latestSensor)
            @php
                $progress = $kebutuhanPakan > 0 ? min(round(($latestSensor->berat / $kebutuhanPakan) * 100), 100) : 0;
            @endphp
            <div class="mt-4">
                <div class="progress" style="height: 20px; border-radius: 50px;">
                    <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : 'bg-danger' }}" 
                         role="progressbar" 
                         style="width: {{ $progress }}%;" 
                         aria-valuenow="{{ $progress }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $progress }}%
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mt-3 text-center">
                @if($latestSensor->berat >= $kebutuhanPakan)
                    <span class="badge bg-success px-4 py-2 rounded-pill shadow-sm">
                        ✅ Pakan mencukupi
                    </span>
                @else
                    <span class="badge bg-danger px-4 py-2 rounded-pill shadow-sm">
                        ⚠ Pakan kurang ({{ number_format($kebutuhanPakan - $latestSensor->berat, 2) }} Kg)
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
    .sapi-card {
        border-radius: 18px;
        transition: all 0.3s ease-in-out;
    }
    .sapi-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 14px 28px rgba(0,0,0,0.2);
    }
    .bg-pink {
        background-color: #e91e63 !important;
        color: #fff;
    }
    .stat-box {
        transition: transform 0.2s ease-in-out;
    }
    .stat-box:hover {
        transform: translateY(-5px);
    }
</style>

<!-- Modal Tambah Sapi -->
<div class="modal fade" id="tambahSapiModal" tabindex="-1" aria-labelledby="tambahSapiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('sapi.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="tambahSapiModalLabel">➕ Tambah Sapi Baru</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="kandang_id" value="{{ $kandang->id }}">

          <div class="mb-3">
            <label for="jenis_sapi" class="form-label">Jenis Sapi</label>
            <select class="form-select" id="jenis_sapi" name="jenis_sapi" required>
                <option value="">-- Pilih Jenis Sapi --</option>
                <option value="Bali">Sapi Bali</option>
                <option value="Madura">Sapi Madura</option>
                <option value="Ongole">Sapi Ongole (PO)</option>
                <option value="Limousin">Sapi Limousin</option>
                <option value="Simental">Sapi Simental</option>
                <option value="Brahman">Sapi Brahman</option>
                <option value="Brangus">Sapi Brangus</option>
                <option value="Friesian Holstein">Sapi Friesian Holstein (Perah)</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
              <option value="">-- Pilih --</option>
              <option value="Jantan">Jantan ♂</option>
              <option value="Betina">Betina ♀</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
          </div>

          <div class="mb-3">
            <label for="berat_sapi" class="form-label">Berat Sapi (Kg)</label>
            <input type="number" class="form-control" id="berat_sapi" name="berat_sapi" step="0.1" required>
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
              <option value="Sehat">Sehat ✅</option>
              <option value="Sakit">Sakit ❌</option>
              <option value="Karantina">Karantina ⚠</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Batal</button>
          <button type="submit" class="btn btn-success">💾 Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
