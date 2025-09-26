@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="fw-bold mb-4 text-primary d-flex align-items-center gap-2">
        🌡 Tambah Data Sensor Manual
    </h3>

    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded-pill px-4 py-2">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- QR Scanner Card (Default View) -->
    <div id="qrScannerCard" class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-body text-center p-5">
            <div class="mb-4">
                <div class="display-1 mb-3">📱</div>
                <h4 class="fw-bold text-primary mb-3">Scan QR Code Sapi</h4>
                <p class="text-muted mb-4">
                    Silakan scan QR code yang ada pada sapi untuk melanjutkan input data sensor
                </p>
            </div>
            
            <button type="button" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-semibold shadow-sm" onclick="startAutoQRScanner()">
                📱 Mulai Scan QR Code
            </button>
            
            <div class="mt-3">
                <small class="text-muted">
                    atau <a href="#" onclick="showManualForm()" class="text-decoration-none">pilih manual</a>
                </small>
            </div>
        </div>
    </div>

    <!-- Form Card (Hidden by default) -->
    <div id="formCard" class="card shadow-lg border-0 rounded-4 d-none">
        <div class="card-body p-4">
            
            <!-- QR Scan Result Display -->
            <div id="scanResultCard" class="alert alert-success rounded-4 mb-4 d-none">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-3">✅ QR Code Berhasil Di-scan!</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <small class="text-muted">ID Sapi:</small><br>
                                <strong id="scannedSapiId">-</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Nama Sapi:</small><br>
                                <strong id="scannedSapiName">-</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Kandang:</small><br>
                                <strong id="scannedKandang">-</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Jenis & Kelamin:</small><br>
                                <strong id="scannedJenisKelamin">-</strong>
                            </div>
                        </div>
                    </div>
                    <div class="ms-3">
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="startAutoQRScanner()">
                            📱 Scan Ulang
                        </button>
                    </div>
                </div>
            </div>

            <form action="{{ route('sensor.storeManual') }}" method="POST" class="row g-3">
                @csrf

                {{-- Hidden inputs for scanned data --}}
                <input type="hidden" name="kandang_id" id="hiddenKandangId" value="{{ $sapi->kandang->id ?? '' }}">
                <input type="hidden" name="sapi_id" id="hiddenSapiId" value="{{ $sapi->id ?? '' }}">

                {{-- Mode: dari detail sapi --}}
                @if(isset($sapi))
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">🐄 Sapi</label>
                        <input type="text" class="form-control rounded-pill"
                               value="{{ $sapi->nama_sapi }} (ID: {{ $sapi->id_sapi ?? $sapi->id }})" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">🏡 Kandang</label>
                        <input type="text" class="form-control rounded-pill"
                               value="{{ $sapi->kandang->nama_kandang ?? '-' }}" disabled>
                    </div>

                @else
                    {{-- Manual Selection (Hidden by default) --}}
                    <div id="manualSelectionDiv" class="col-12 d-none">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kandang_id" class="form-label fw-semibold">🏡 Pilih Kandang</label>
                                <select name="kandang_id_manual" id="kandang_id" class="form-select rounded-pill">
                                    <option value="">-- Pilih Kandang --</option>
                                    @isset($kandangs)
                                        @foreach($kandangs as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kandang }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="sapi_id" class="form-label fw-semibold">🐄 Pilih Sapi</label>
                                <select name="sapi_id_manual" id="sapi_id" class="form-select rounded-pill" disabled>
                                    <option value="">-- Pilih Sapi --</option>
                                    @isset($sapis)
                                        @foreach($sapis as $s)
                                            <option value="{{ $s->id }}"
                                                data-nama="{{ $s->nama_sapi }}"
                                                data-id_sapi="{{ $s->id_sapi ?? $s->id }}"
                                                data-jenis="{{ $s->jenis_sapi ?? '' }}"
                                                data-kelamin="{{ $s->jenis_kelamin ?? '' }}"
                                                data-berat="{{ $s->berat_sapi ?? '' }}"
                                                data-kandang="{{ $s->kandang->nama_kandang ?? '' }}"
                                                data-kandang_id="{{ $s->kandang->id ?? '' }}">
                                                {{ $s->id_sapi ?? $s->id }} - {{ $s->nama_sapi ?? '-' }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Input sensor --}}
                <div class="col-md-6">
                    <label for="suhu" class="form-label fw-semibold">🌡 Suhu (°C)</label>
                    <input type="number" step="0.1" name="suhu" id="suhu" 
                           class="form-control rounded-pill"
                           value="{{ old('suhu', $latest->suhu ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="berat" class="form-label fw-semibold">⚖ Berat Pakan (Kg)</label>
                    <input type="number" step="0.1" name="berat" id="berat" 
                           class="form-control rounded-pill"
                           value="{{ old('berat', $latest->berat ?? '') }}" required>
                </div>

                {{-- Tombol --}}
                <div class="col-12 d-flex justify-content-between mt-4">
                    <a href="{{ isset($sapi) ? route('sapi.detail', $sapi->id) : route('dashboard') }}" 
                       class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                        ⬅ Kembali
                    </a>
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold">
                        ✅ Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal untuk QR Scanner -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="qrScannerModalLabel">📱 Scan QR Code Sapi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <p class="text-primary fw-semibold mb-2">Arahkan kamera ke QR Code yang ada pada sapi</p>
                        <small class="text-muted">QR Code berisi informasi ID Sapi, Nama, dan Kandang</small>
                    </div>
                    
                    <div id="qr-reader" style="width: 100%; height: 400px;"></div>
                    
                    <div class="mt-4">
                        <button type="button" class="btn btn-danger rounded-pill me-2" onclick="stopQRScanner()">
                            ⏹ Stop Scanner
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                            📝 Input Manual
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner;
let isFromDetailSapi = {{ isset($sapi) ? 'true' : 'false' }};

// Auto start QR scanner when page loads (if not from detail sapi)
document.addEventListener('DOMContentLoaded', function () {
    if (!isFromDetailSapi) {
        // Auto show scanner after 1 second
        setTimeout(() => {
            startAutoQRScanner();
        }, 1000);
    } else {
        // If from detail sapi, show form directly
        showForm();
    }

    setupManualFormLogic();
});

function startAutoQRScanner() {
    const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'), {
        backdrop: 'static',
        keyboard: false
    });
    modal.show();
    
    // Initialize scanner
    html5QrcodeScanner = new Html5Qrcode("qr-reader");
    
    const config = {
        fps: 10,
        qrbox: { width: 280, height: 280 },
        aspectRatio: 1.0
    };
    
    // Start scanning
    html5QrcodeScanner.start(
        { facingMode: "environment" },
        config,
        onQRScanSuccess,
        onQRScanFailure
    ).catch(err => {
        console.error("Unable to start scanning:", err);
        showErrorAlert("Tidak dapat memulai scanner. Pastikan kamera dapat diakses dan browser mengizinkan akses kamera.");
    });
}

function onQRScanSuccess(decodedText, decodedResult) {
    console.log(`QR Code detected: ${decodedText}`);
    
    // Parse QR data (expected format: "ID_SAPI|NAMA_SAPI|KANDANG_ID|KANDANG_NAMA")
    const qrData = parseQRData(decodedText);
    
    if (qrData.success) {
        // Show success and populate form
        populateFormFromQR(qrData);
        showSuccessAlert(`✅ QR Code berhasil di-scan! Sapi: ${qrData.nama_sapi}`);
    } else {
        // Try to find sapi manually
        const foundSapi = findSapiByQR(decodedText);
        if (foundSapi.success) {
            populateFormFromSapi(foundSapi);
            showSuccessAlert(`✅ Sapi ditemukan: ${foundSapi.nama_sapi}`);
        } else {
            showErrorAlert(`❌ QR Code tidak valid atau sapi tidak ditemukan: ${decodedText}`);
            return; // Don't close scanner
        }
    }
    
    // Stop scanner and show form
    stopQRScanner();
    showForm();
}

function onQRScanFailure(error) {
    // Normal behavior during scanning
}

function parseQRData(qrText) {
    try {
        // Try JSON format first
        const jsonData = JSON.parse(qrText);
        if (jsonData.id_sapi && jsonData.nama_sapi) {
            return {
                success: true,
                id_sapi: jsonData.id_sapi,
                nama_sapi: jsonData.nama_sapi,
                kandang_id: jsonData.kandang_id,
                kandang_nama: jsonData.kandang_nama,
                jenis: jsonData.jenis || '',
                kelamin: jsonData.kelamin || '',
                berat: jsonData.berat || ''
            };
        }
    } catch (e) {
        // Try pipe separated format: "ID_SAPI|NAMA_SAPI|KANDANG_ID|KANDANG_NAMA"
        const parts = qrText.split('|');
        if (parts.length >= 2) {
            return {
                success: true,
                id_sapi: parts[0],
                nama_sapi: parts[1],
                kandang_id: parts[2] || '',
                kandang_nama: parts[3] || '',
                jenis: parts[4] || '',
                kelamin: parts[5] || ''
            };
        }
    }
    
    return { success: false };
}

function findSapiByQR(qrText) {
    const sapiSelect = document.getElementById('sapi_id');
    if (!sapiSelect) return { success: false };
    
    // Search in available options
    let foundOption = null;
    Array.from(sapiSelect.options).forEach(option => {
        const idSapi = option.dataset.id_sapi;
        const nama = option.dataset.nama;
        
        if (idSapi === qrText || nama === qrText || option.value === qrText) {
            foundOption = option;
        }
    });
    
    if (foundOption) {
        return {
            success: true,
            sapi_id: foundOption.value,
            id_sapi: foundOption.dataset.id_sapi,
            nama_sapi: foundOption.dataset.nama,
            kandang_id: foundOption.dataset.kandang_id,
            kandang_nama: foundOption.dataset.kandang,
            jenis: foundOption.dataset.jenis,
            kelamin: foundOption.dataset.kelamin,
            berat: foundOption.dataset.berat
        };
    }
    
    return { success: false };
}

function populateFormFromQR(qrData) {
    // Set hidden inputs
    document.getElementById('hiddenSapiId').value = qrData.sapi_id || '';
    document.getElementById('hiddenKandangId').value = qrData.kandang_id || '';
    
    // Show scan result
    showScanResult(qrData);
}

function populateFormFromSapi(sapiData) {
    // Set hidden inputs
    document.getElementById('hiddenSapiId').value = sapiData.sapi_id;
    document.getElementById('hiddenKandangId').value = sapiData.kandang_id;
    
    // Show scan result
    showScanResult(sapiData);
}

function showScanResult(data) {
    document.getElementById('scannedSapiId').textContent = data.id_sapi || '-';
    document.getElementById('scannedSapiName').textContent = data.nama_sapi || '-';
    document.getElementById('scannedKandang').textContent = data.kandang_nama || '-';
    document.getElementById('scannedJenisKelamin').textContent = 
        (data.jenis || '') + (data.kelamin ? ` (${data.kelamin})` : '');
    
    document.getElementById('scanResultCard').classList.remove('d-none');
}

function stopQRScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            html5QrcodeScanner.clear();
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
            if (modal) {
                modal.hide();
            }
        }).catch(err => {
            console.error("Error stopping scanner:", err);
        });
    }
}

function showForm() {
    document.getElementById('qrScannerCard').classList.add('d-none');
    document.getElementById('formCard').classList.remove('d-none');
}

function showManualForm() {
    showForm();
    document.getElementById('manualSelectionDiv').classList.remove('d-none');
}

function showSuccessAlert(message) {
    // You can implement a toast notification here
    console.log(message);
}

function showErrorAlert(message) {
    alert(message);
}

// Manual form logic
function setupManualFormLogic() {
    const kandangSelect = document.getElementById('kandang_id');
    const sapiSelect = document.getElementById('sapi_id');

    if (!sapiSelect || !kandangSelect) return;

    const allSapiOptions = Array.from(sapiSelect.options);

    kandangSelect.addEventListener('change', function () {
        const selectedKandang = this.value;
        sapiSelect.innerHTML = '<option value="">-- Pilih Sapi --</option>';

        if (!selectedKandang) {
            sapiSelect.disabled = true;
            return;
        }

        const filteredOptions = allSapiOptions.filter(opt => opt.dataset.kandang_id === selectedKandang);
        filteredOptions.forEach(opt => sapiSelect.appendChild(opt.cloneNode(true)));
        sapiSelect.disabled = filteredOptions.length === 0;
    });

    sapiSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) return;

        // Update hidden inputs for manual selection
        document.getElementById('hiddenSapiId').value = opt.value;
        document.getElementById('hiddenKandangId').value = opt.dataset.kandang_id;
    });
}

// Clean up when modal is closed
document.getElementById('qrScannerModal').addEventListener('hidden.bs.modal', function (event) {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().catch(err => {
            console.error("Error stopping scanner on modal close:", err);
        });
    }
    
    // If no data scanned and not from detail sapi, show scanner card again
    const hasScannedData = !document.getElementById('scanResultCard').classList.contains('d-none');
    const hasManualSelection = !document.getElementById('manualSelectionDiv').classList.contains('d-none');
    
    if (!hasScannedData && !hasManualSelection && !isFromDetailSapi) {
        document.getElementById('qrScannerCard').classList.remove('d-none');
        document.getElementById('formCard').classList.add('d-none');
    }
});
</script>

<style>
.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
}

#qr-reader {
    border: 3px dashed #007bff;
    border-radius: 15px;
    padding: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

#qr-reader__dashboard {
    margin-top: 15px;
}

#qr-reader__camera_selection {
    margin-bottom: 10px;
}

.display-1 {
    font-size: 5rem;
    color: #007bff;
}

.alert {
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from { 
        opacity: 0; 
        transform: translateY(-20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.card {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

@media (max-width: 768px) {
    .display-1 {
        font-size: 3rem;
    }
}
</style>
@endsection