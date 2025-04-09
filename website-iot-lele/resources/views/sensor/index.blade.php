@extends('app')

@section('content')
<div class="container my-3 rounded p-3" style="background-color: lavender;">
    <h2>Pengaturan Nilai Optimal Sensor</h2>
    <form action="{{ route('sensor-settings.update') }}" method="POST">
        @csrf
        
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
                        <label class="form-label">Temperature Minimal</label>
                        <input type="number" step="0.01" class="form-control" name="min_temperature" value="{{ $settings->min_temperature }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Temperature Maksimal</label>
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
        <button type="submit" class="btn btn-success">Save Settings</button>
    </form>
</div>
@endsection