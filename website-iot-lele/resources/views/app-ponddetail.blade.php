@extends('app')

@section('content')


<div class="container mt-3">

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-theme-white-2 position-relative" role="alert">
            <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-success rounded-circle mx-auto me-1">
                <i class="fas fa-check align-self-center mb-0 text-white"></i>
            </div>
            {{ session('success') }}
            <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
    @endif

    <div class="d-flex justify-content-center justify-content-md-between align-items-center mb-4 pb-3 border-bottom flex-wrap position-relative mt-3">
        <!-- Back Button -->
        <a href="{{ route('kolam.index') }}" class="btn btn-outline-secondary text-dark position-absolute top-0 start-0 d-none d-sm-inline-flex">
            <i class="fas fa-arrow-left"></i>
        </a>
        <a href="{{ route('kolam.index') }}" class="btn btn-outline-secondary text-dark position-absolute top-0 start-0 d-inline-flex d-sm-none">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Pond Information -->
        <div class="d-flex justify-content-center align-items-center gap-3 gap-md-5 flex-wrap text-center w-100 mt-2">
            <!-- Pond Name -->
            <div>
                <p class="text-secondary text-uppercase fw-semibold fs-7 fs-md-6 mb-1">Nama Kolam</p>
                <p class="fw-bold fs-5 fs-md-4 mb-0">{{ $pond->name_pond }}</p>
            </div>

            <span class="text-secondary fs-5 d-none d-md-inline">|</span>

            <!-- Fish Age -->
            <div>
                <p class="text-secondary text-uppercase fw-semibold fs-7 fs-md-6 mb-1">Umur Ikan</p>
                <p class="fw-bold fs-5 fs-md-4 mb-0">{{ $pond->formatted_age }}</p>
            </div>

            <span class="text-secondary fs-5 d-none d-md-inline">|</span>

            <!-- Fish Count -->
            <div>
                <p class="text-secondary text-uppercase fw-semibold fs-7 fs-md-6 mb-1">Jumlah Ikan</p>
                <p class="fw-bold fs-5 fs-md-4 mb-0">{{ $pond->total_fish }}</p>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs justify-content-center border-0 fs-5" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab" aria-controls="schedule" aria-selected="true">Jadwal Pakan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="sensor-tab" data-bs-toggle="tab" data-bs-target="#sensor" type="button" role="tab" aria-controls="sensor" aria-selected="false">Status Terkini</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="allsensor-tab" data-bs-toggle="tab" data-bs-target="#allsensor" type="button" role="tab" aria-controls="allsensor" aria-selected="false">Rekap Status</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="setting-tab" data-bs-toggle="tab" data-bs-target="#setting" type="button" role="tab" aria-controls="setting" aria-selected="false">Atur Sensor</button>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
            <div class="container mt-4">
                <div class="row d-flex align-items-stretch">
                    <!-- Left Column: Schedule -->
                    <div class="col-md-4">
                        @php
                            $status = $feeder->feeder_status;
                            $imageFile = $status === 'Kosong' 
                                ? 'fish_food_can_red.png' 
                                : 'fish_food_can_green.png';
                        @endphp

                        <div class="card h-100 p-3" style="background-color: lavender;">
                            <h5><strong>Status Tempat Pakan</strong></h5>
                            <div class="card-body text-center">
                                <img 
                                    src="{{ asset('images/' . $imageFile) }}" 
                                    alt="Tempat Pakan" 
                                    class="img-fluid" 
                                    style="max-height: 150px;">
                                <p class="mt-3 fw-bold">
                                    Status: <span class="text-{{ $status === 'Kosong' ? 'danger' : 'success' }}">{{ $status }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Bottom Section: Pemberian Pakan Manual -->
                        <div class="card p-3" style="background-color: lavender;">
                            <h5><strong>Pemberian Pakan Manual</strong></h5>
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    <button id="startBtn" class="btn btn-success px-4 py-2">Mulai</button>
                                </div>
                                <p class="mt-3 fw-bold">Status Sistem: <span id="systemStatus">Berhenti</span></p>
                            </div>
                        </div>
                    </div>  --}}

                    <div class="card p-3" style="background-color: lavender;">
                        <h5><strong>Pemberian Pakan Manual</strong></h5>
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-center align-items-center gap-3">
                                <button id="startBtn" class="btn btn-success px-4 py-2">Mulai</button>
                            </div>
                            <div id="responseMessage" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                    
                </div>               

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card p-3" style="background-color: lavender;">
                            <h5><strong>Riwayat Pemberian Makan</strong></h5>
                                <div class="card-body">
                                        <table class="table mt-3 bg-white rounded">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Waktu</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($histories as $history)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($history->feeding_time)->format('d-m-y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($history->feeding_time)->format('H:i') }}</td>
                                                        <td><span class="badge p-2 bg-success">{{ $history->status }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">Belum Ada Data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                </div>
                        </div>
                    </div>
                </div>    
            </div>

            <!-- Modal for Editing Total Food -->
            <div class="modal fade" id="editTotalFood{{ $pond->id_pond }}" tabindex="-1" aria-labelledby="editTotalFoodLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTotalFoodLabel">Edit Jumlah Pakan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editFeedingForm" method="POST" action="{{ route('kolam.updatetotalfood', $feeder->id  ?? '') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="total_food" class="form-label">Jumlah Pakan (kg)</label>
                                    <input type="number" class="form-control" name="total_food" id="total_food" value="{{ $feeder->total_food }}" required>
                                </div>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const editFeedingModal = document.getElementById('editFeedingModal');
                    editFeedingModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const idPond = button.getAttribute('data-id-pond');
                        const feedingTime = button.getAttribute('data-feeding-time');

                        const form = editFeedingModal.querySelector('#editFeedingForm');
                        form.setAttribute('action', `/kolam/${idPond}/updatetime`);
                        form.querySelector('#feeding_time').value = feedingTime;
                    });
                });
            </script>

        </div>

        <div class="tab-pane fade show active" id="sensor" role="tabpanel" aria-labelledby="sensor-tab">
            <div class="row row-cols-1 row-cols-md-3 mt-3 g-3">
                @forelse($latestReadings as $reading)
                    @php
                        $parameters = [
                            ['name' => 'pH Air', 'value' => $reading->ph, 'min' => $settings->min_ph, 'max' => $settings->max_ph, 'status' => $reading->ph_status],
                            ['name' => 'Suhu Air (°C)', 'value' => $reading->temperature, 'min' => $settings->min_temperature, 'max' => $settings->max_temperature, 'status' => $reading->temperature_status],
                            ['name' => 'TDS/Zat Terlarut (ppm)', 'value' => $reading->tds, 'min' => $settings->min_tds, 'max' => $settings->max_tds, 'status' => $reading->tds_status],
                            ['name' => 'Konduktivitas Air (µS/cm)', 'value' => $reading->conductivity, 'min' => $settings->min_conductivity, 'max' => $settings->max_conductivity, 'status' => $reading->conductivity_status],
                            ['name' => 'Salinitas (ppt)', 'value' => $reading->salinity, 'min' => $settings->min_salinity, 'max' => $settings->max_salinity, 'status' => $reading->salinity_status]
                        ];
                    @endphp
                @empty
                    <div class="col">
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">Belum Ada Data! <i class="fas fa-exclamation-triangle text-danger"></i></div>
                            <div class="card-body justify-content-between align-items-center">
                                <div class="mt-3">
                                    <p class="mb-0">Belum ada tangkapan dari sensor, silakan memulai sistem terlebih dahulu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">Belum Ada Data! <i class="fas fa-exclamation-triangle text-danger"></i></div>
                            <div class="card-body justify-content-between align-items-center">
                                <div class="mt-3">
                                    <p class="mb-0">Belum ada tangkapan dari sensor, silakan memulai sistem terlebih dahulu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">Belum Ada Data! <i class="fas fa-exclamation-triangle text-danger"></i></div>
                            <div class="card-body justify-content-between align-items-center">
                                <div class="mt-3">
                                    <p class="mb-0">Belum ada tangkapan dari sensor, silakan memulai sistem terlebih dahulu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse

                @isset($parameters)
                    @forelse($parameters as $index => $param)
                        @php
                            $value = is_numeric($param['value']) ? $param['value'] : 0;
                            $min = is_numeric($param['min']) ? $param['min'] : 0;
                            $max = is_numeric($param['max']) ? $param['max'] : 100; // Default to avoid errors

                            // Calculate percentage within range
                            $percent = ($value - $min) / ($max - $min) * 100;
                            $percent = max(0, min(100, $percent));

                            // Determine status and colors
                            if ($value < $min) {
                                $status = 'Rendah';  
                                $badgeColor = '#D32F2F'; 
                            } elseif ($value > $max) {
                                $status = 'Tinggi';  
                                $badgeColor = '#FF5722'; 
                            } else {
                                $status = 'Normal';   
                                $badgeColor = '#FFEB3B'; 
                            }

                            // Show warning icon if out of range
                            $warningIcon = ($value < $min || $value > $max) 
                                ? '<i class="fas fa-exclamation-triangle text-danger"></i>' 
                                : '';
                        @endphp

                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                                    {{ $param['name'] }}
                                    {!! $warningIcon !!}
                                </div>
                                <div class="card-body text-center">
                                    <span class="badge mb-2 p-2" style="background-color: {{ $badgeColor }}; color: white;">
                                        {{ $status }}
                                    </span>
                                    <div class="gauge-container">
                                        <canvas id="gauge{{ $index }}"></canvas>
                                    </div>
                                    <h5 class="card-title mt-3 fs-2">{{ $value }}</h5>
                                    <p class="mb-0">Rentang Normal: {{ $min }} - {{ $max }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">Belum Ada Data</div>
                        </div>
                    @endforelse

                    <div class="col">
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Netralisir Air</div>
                            <div class="card-body justify-content-between align-items-center">
                                <div class="text-center mt-4">
                                    <button class="btn btn-warning btn-sm py-4 px-5">Mulai</button>
                                </div>
                                <div class="mt-5">
                                    <p class="mb-0">Sistem netralisir air sudah diatur secara otomatis.</p>
                                    <p class="mb-0">Memulai secara manual hanya bila diperlukan.</p>
                                    <br>
                                    <p class="mb-0">*Sistem tidak akan bekerja bila air menyentuh batas maksimal.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const gaugeConfigs = [
                            { id: "gauge0", value: {{ $reading->ph ?? 0 }}, min: {{ $settings->min_ph ?? 0 }}, max: {{ $settings->max_ph ?? 0 }} },
                            { id: "gauge1", value: {{ $reading->temperature ?? 0 }}, min: {{ $settings->min_temperature ?? 0 }}, max: {{ $settings->max_temperature ?? 0 }} },
                            { id: "gauge2", value: {{ $reading->tds ?? 0 }}, min: {{ $settings->min_tds ?? 0 }}, max: {{ $settings->max_tds ?? 0 }} },
                            { id: "gauge3", value: {{ $reading->conductivity ?? 0 }}, min: {{ $settings->min_conductivity ?? 0 }}, max: {{ $settings->max_conductivity ?? 0 }} },
                            { id: "gauge4", value: {{ $reading->salinity ?? 0 }}, min: {{ $settings->min_salinity ?? 0 }}, max: {{ $settings->max_salinity ?? 0 }} }
                        ];

                        gaugeConfigs.forEach(config => {
                            let target = document.getElementById(config.id);
                            if (target) {
                                let gauge = new Gauge(target).setOptions({
                                    angle: 0.15,  
                                    lineWidth: 0.3,  
                                    radiusScale: 1,
                                    pointer: {
                                        length: 0.6,  
                                        strokeWidth: 0.05,  
                                        color: '#778899'
                                    },
                                    limitMax: false,
                                    limitMin: false,
                                    colorStart: config.value < config.min ? "#D32F2F" : config.value > config.max ? "#FF5722" : "#FFEB3B",
                                    colorStop: config.value < config.min ? "#D32F2F" : config.value > config.max ? "#FF5722" : "#FFEB3B",
                                    strokeColor: "#E8F0FE",
                                    generateGradient: false,  
                                    highDpiSupport: true
                                });
                                gauge.maxValue = config.max;
                                gauge.setMinValue(config.min);
                                gauge.animationSpeed = 32;
                                gauge.set(config.value);
                            }
                        });
                    });

                </script>
            </div>

        </div>

        <div class="tab-pane fade" id="allsensor" role="tabpanel" aria-labelledby="allsensor-tab">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('kolam.show', ['id_pond' => $pond->id_pond]) }}" class="mb-3">
                <div class="row mt-4 justify-content-center d-flex">
                    <div class="col-md-3">
                        <label>Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" onchange="this.form.submit()">
                    </div>
                </div>
            </form>

            <!-- Sensor Readings Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>pH</th>
                        <th>Suhu (°C)</th>
                        <th>TDS (ppm)</th>
                        <th>Konduktivitas (µS/cm)</th>
                        <th>Salinitas (ppt)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sensorReadings as $reading)
                    <tr>
                        <td>{{ $reading->created_at->format('d/m/Y') }}</td>
                        <td>{{ $reading->created_at->format('H:i') }}</td>
                        <td>{{ $reading->ph }}</td>
                        <td>{{ $reading->temperature }}</td>
                        <td>{{ $reading->tds }}</td>
                        <td>{{ $reading->conductivity }}</td>
                        <td>{{ $reading->salinity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">Tidak ada data sensor dalam rentang waktu ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="setting" role="tabpanel" aria-labelledby="setting-tab">
            <div class="container my-3 rounded p-3" style="background-color: lavender;">
                <h2>Pengaturan Nilai Optimal Sensor</h2>
                <form action="{{ route('kolam.updatesensor',$settings->id_pond) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">pH Minimal</label>
                                    <input type="number" step="0.01" class="form-control" name="min_ph" value="{{ $settings->min_ph }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">pH Maksimal</label>
                                    <input type="number" step="0.01" class="form-control" name="max_ph" value="{{ $settings->max_ph }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Temperatur Minimal</label>
                                    <input type="number" step="0.01" class="form-control" name="min_temperature" value="{{ $settings->min_temperature }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Temperatur Maksimal</label>
                                    <input type="number" step="0.01" class="form-control" name="max_temperature" value="{{ $settings->max_temperature }}">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">TDS Minimal</label>
                                    <input type="number" class="form-control" name="min_tds" value="{{ $settings->min_tds }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">TDS Maksimal</label>
                                    <input type="number" class="form-control" name="max_tds" value="{{ $settings->max_tds }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Konduktivitas Air Minimal</label>
                                    <input type="number" class="form-control" name="min_conductivity" value="{{ $settings->min_conductivity }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Konduktivitas Air Maksimal</label>
                                    <input type="number" class="form-control" name="max_conductivity" value="{{ $settings->max_conductivity }}">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Salinitas Minimal</label>
                                    <input type="number" step="0.01" class="form-control" name="min_salinity" value="{{ $settings->min_salinity }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Salinitas Maksimal</label>
                                    <input type="number" step="0.01" class="form-control" name="max_salinity" value="{{ $settings->max_salinity }}">
                                </div>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabKey = 'activeTab';
            const tabTriggerEls = document.querySelectorAll('#myTab button[data-bs-toggle="tab"]');

            // Restore tab from localStorage
            const savedTabId = localStorage.getItem(tabKey);
            if (savedTabId) {
            const tabToShow = document.querySelector(`#${savedTabId}`);
            if (tabToShow) {
                const tab = new bootstrap.Tab(tabToShow);
                tab.show();
            }
            }

            // Save tab to localStorage when clicked
            tabTriggerEls.forEach((tabEl) => {
                tabEl.addEventListener('shown.bs.tab', (e) => {
                    localStorage.setItem(tabKey, e.target.id);
                });
            });
        });
    </script>

</div>
<script>
function controlPump(command) {
    fetch('/pump-control', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ command: command })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            alert(data.status); // misalnya: "Pompa ON"
        } else {
            alert("Gagal: " + data.error);
        }
    })
    .catch(error => {
        alert("Terjadi kesalahan: " + error);
    });
}
</script>
<script>
document.getElementById('startBtn').addEventListener('click', function() {
    const button = this;
    button.disabled = true;
    button.innerText = "Memproses...";

    fetch("{{ url('/manual-feeding') }}")
        .then(response => response.text())
        .then(result => {
            document.getElementById('responseMessage').innerHTML = 
                `<div class="alert alert-success">✅ Pakan berhasil dijalankan!</div>`;
        })
        .catch(error => {
            document.getElementById('responseMessage').innerHTML = 
                `<div class="alert alert-danger">❌ Gagal mengirim pakan: ${error}</div>`;
        })
        .finally(() => {
            button.disabled = false;
            button.innerText = "Mulai";
        });
});
</script>

@endsection