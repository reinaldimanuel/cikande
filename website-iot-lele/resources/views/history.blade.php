@extends('app')

@section('content')

<div class="container mt-4">
    <h4 class="page-title">Daftar Kolam Terhapus</h4>
    <div class="table-responsive">
        <table class="table  mb-0 table-centered">
            <thead class="table-light">
                <tr>
                    <th>Nama Kolam</th>
                    <th>Tanggal Buat Kolam</th>
                    <th>Tanggal Hapus Kolam</th>
                    <th>Lihat Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deactivatedPonds as $pond)
                    <tr>
                        <td>{{ $pond->name_pond }}</td>
                        <td>{{ \Carbon\Carbon::parse($pond->created_at)->format('d M Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($pond->updated_at)->format('d M Y H:i') }}</td>
                        <td class="text-center">
                        <a href="#" class="btn btn-primary btn-sm show-sensor-modal"
                            data-pond-name="{{ $pond->name_pond }}"
                            data-ph="{{ $pond->latestReading->ph ?? 'N/A' }}"
                            data-temperature="{{ $pond->latestReading->temperature ?? 'N/A' }}"
                            data-tds="{{ $pond->latestReading->tds ?? 'N/A' }}"
                            data-conductivity="{{ $pond->latestReading->conductivity ?? 'N/A' }}"
                            data-salinity="{{ $pond->latestReading->salinity ?? 'N/A' }}"
                            data-bs-toggle="modal"
                            data-bs-target="#sensorModal">
                            <i class="fas fa-eye"></i> Lihat Status Terakhir
                        </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sensorModal" tabindex="-1" aria-labelledby="sensorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sensorModalTitle">Tangkapan Terakhir oleh Sensor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h5 id="pondName"></h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>pH Air</td><td id="phValue">-</td></tr>
                            <tr><td>Suhu Air (°C)</td><td id="temperatureValue">-</td></tr>
                            <tr><td>TDS (ppm)</td><td id="tdsValue">-</td></tr>
                            <tr><td>Konduktivitas (µS/cm)</td><td id="conductivityValue">-</td></tr>
                            <tr><td>Salinitas (ppt)</td><td id="salinityValue">-</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select all buttons that trigger the modal
            document.querySelectorAll(".show-sensor-modal").forEach(button => {
                button.addEventListener("click", function() {
                    // Get data attributes from the clicked button
                    let pondName = this.getAttribute("data-pond-name");
                    let ph = this.getAttribute("data-ph");
                    let temperature = this.getAttribute("data-temperature");
                    let tds = this.getAttribute("data-tds");
                    let conductivity = this.getAttribute("data-conductivity");
                    let salinity = this.getAttribute("data-salinity");

                    // Update modal content
                    document.getElementById("pondName").innerText = "Kolam: " + pondName;
                    document.getElementById("phValue").innerText = ph;
                    document.getElementById("temperatureValue").innerText = temperature;
                    document.getElementById("tdsValue").innerText = tds;
                    document.getElementById("conductivityValue").innerText = conductivity;
                    document.getElementById("salinityValue").innerText = salinity;
                });
            });
        });
    </script>
</div>

@endsection