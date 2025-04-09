@extends('app')

@section('content')

<div class="header text-center">
            <h1 class="title-container" style="font-family: 'Play-Bold';">SISTEM PANTAU KOLAM DIGITAL</h1>

                <div class="btn-group mt-4">
                    <a href="{{ route('feeding.index') }}" class="btn btn-light px-4 fw-bold {{ request()->routeIs('feeding.index') ? 'active' : '' }}">Jadwal Pakan</a>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-light px-4 fw-bold {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">Status Harian</a>
                    <a href="{{ route('dashboard.statistics') }}" class="btn btn-light px-4 fw-bold {{ request()->routeIs('dashboard.statistics') ? 'active' : '' }}">Status Periode</a>
                    <a href="{{ route('sensor-settings.index') }}" class="btn btn-light px-4 fw-bold {{ request()->routeIs('sensor-settings.index') ? 'active' : '' }}">Atur Sensor</a>

                </div>
            </div>

<div class="container mt-4">

    <div class="row">

        <div class="row row-cols-1 row-cols-md-3 g-3">
            @foreach($sensorReadings as $reading)
                @php
                    $parameters = [
                        ['name' => 'pH Air', 'value' => $reading->ph, 'min' => $settings->min_ph, 'max' => $settings->max_ph, 'status' => $reading->ph_status],
                        ['name' => 'Suhu Air (°C)', 'value' => $reading->temperature, 'min' => $settings->min_temperature, 'max' => $settings->max_temperature, 'status' => $reading->temperature_status],
                        ['name' => 'TDS/Zat Terlarut (ppm)', 'value' => $reading->tds, 'min' => $settings->min_tds, 'max' => $settings->max_tds, 'status' => $reading->tds_status],
                        ['name' => 'Konduktivitas Air (µS/cm)', 'value' => $reading->conductivity, 'min' => $settings->min_conductivity, 'max' => $settings->max_conductivity, 'status' => $reading->conductivity_status],
                        ['name' => 'Salinitas (ppt)', 'value' => $reading->salinity, 'min' => $settings->min_salinity, 'max' => $settings->max_salinity, 'status' => $reading->salinity_status]
                    ];
                @endphp
            @endforeach

                @foreach($parameters as $index => $param)
                    @php
                        $percent = ($param['value'] - $param['min']) / ($param['max'] - $param['min']) * 100;
                        $percent = max(0, min(100, $percent));
                        $color = $param['status'] == 'Normal' ? 'green' : ($param['status'] == 'Rendah' ? 'red' : 'orange');
                        $warningIcon = ($param['value'] < $param['min'] || $param['value'] > $param['max']) ? '<i class="fas fa-exclamation-triangle warning-icon"></i>' : '';
                    @endphp

                    <div class="col">
                        <div class="card mb-3">
                            <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                                {{ $param['name'] }}
                                {!! $warningIcon !!}
                            </div>
                            <div class="card-body text-center">
                                <span class="badge mb-3 text-bg-{{ $param['status'] == 'Normal' ? 'success' : ($param['status'] == 'Rendah' ? 'danger' : 'warning') }} p-2">{{ $param['status'] }}</span>
                                <div class="gauge-container">
                                    <canvas id="gauge{{ $index }}"></canvas>
                                </div>
                                <h5 class="card-title mt-4 fs-2">{{ $param['value'] }}</h5>
                                <p class="mb-0">Rentang Normal: {{ $param['min'] }} - {{ $param['max'] }}</p>
                                
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-header fw-bold">Netralisir Air</div>
                        <div class="card-body justify-content-between align-items-center">
                            <div class="text-center mt-4">
                                <button class="btn btn-warning btn-sm py-4 px-5">Mulai</button>
                            </div>
                            <div class="mt-5">
                                <p class="mb-0">Sistem netralisir air sudah diatur secara otomatis.</p>
                                <p class="mb-0">Memulai secara manual hanya bila diperlukan.</p>
                                <p class="mb-0">*Sistem tidak akan bekerja bila air menyentuh batas maksimal.</p>
                            </div>
                        </div>
                    </div>
                </div>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const gaugeConfigs = [
                    { id: "gauge0", value: {{ $reading->ph }}, min: {{ $settings->min_ph }}, max: {{ $settings->max_ph }} },
                    { id: "gauge1", value: {{ $reading->temperature }}, min: {{ $settings->min_temperature }}, max: {{ $settings->max_temperature }} },
                    { id: "gauge2", value: {{ $reading->tds }}, min: {{ $settings->min_tds }}, max: {{ $settings->max_tds }} },
                    { id: "gauge3", value: {{ $reading->conductivity }}, min: {{ $settings->min_conductivity }}, max: {{ $settings->max_conductivity }} },
                    { id: "gauge4", value: {{ $reading->salinity }}, min: {{ $settings->min_salinity }}, max: {{ $settings->max_salinity }} }
                ];

                gaugeConfigs.forEach(config => {
                    let target = document.getElementById(config.id);
                    if (target) {
                        let gauge = new Gauge(target).setOptions({
                            angle: 0.10,
                            lineWidth: 0.2,
                            radiusScale: 1,
                            pointer: { length: 0.6, strokeWidth: 0.03, color: '#000000' },
                            limitMax: false,
                            limitMin: false,
                            colorStart: config.value < config.min ? "#dc3545" : config.value > config.max ? "#ffc107" : "#198754",
                            colorStop: config.value < config.min ? "#dc3545" : config.value > config.max ? "#ffc107" : "#198754",
                            strokeColor: "#E0E0E0",
                            generateGradient: true,
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

</div>

@endsection