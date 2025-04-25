@extends('app')

@section('content')

<div class="container mt-3">
    <!-- Title & Search Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Daftar Kolam Terhapus</h4>

        <input type="text" id="searchDeactivatedPond" class="form-control rounded-pill w-50 ms-auto"
            placeholder="Cari nama kolam..." style="max-width: 300px;">
    </div>

    <!-- Content -->
    <div class="table-responsive">
        <table class="table mb-0 table-centered">
            <thead class="table-light">
                <tr>
                    <th>Nama Kolam</th>
                    <th>Tanggal Buat Kolam</th>
                    <th>Tanggal Hapus Kolam</th>
                    <th>Alasan</th>
                    <th>Rekap Data</th>
                </tr>
            </thead>
            <tbody id="deactivatedPondTableBody">
                @forelse ($deactivatedPonds as $pond)
                    <tr class="deactivated-pond-row" data-name="{{ strtolower($pond->name_pond) }}">
                        <td>{{ $pond->name_pond }}</td>
                        <td>{{ \Carbon\Carbon::parse($pond->created_at)->format('d M Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($pond->updated_at)->format('d M Y H:i') }}</td>
                        <td>{{ $pond->deact_reason }}</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-primary btn-sm show-sensor-modal rounded"
                                data-pond-id="{{ $pond->id_pond }}"
                                data-pond-name="{{ $pond->name_pond }}"
                                data-fish-age="{{ $pond->history_formatted_age }}"
                                data-fish-total="{{ $pond->total_fish }}"
                                data-bs-toggle="modal"
                                data-bs-target="#sensorModal">
                                <i class="fas fa-eye px-2"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum Ada Data</td>
                    </tr>
                @endforelse

                <!-- Row untuk "tidak ditemukan", hidden by default -->
                <tr id="noResultRow" class="text-muted d-none">
                    <td colspan="5" class="text-center">Kolam tidak ditemukan</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sensorModal" tabindex="-1" aria-labelledby="sensorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="sensorModalTitle">Rekap Data 
                        <span id="lastSensorTime" class="text-muted small"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center mb-3">
                        <h5 class="fw-semibold" id="pondName"></h5>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <h5 class="fw-semibold" id="fishAge"></h5>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <h5 class="fw-semibold" id="fishTotal"></h5>
                    </div>

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

                    <!-- Dropdown Filter -->
                    <div class="mt-3 text-center">
                        <label class="form-label fw-bold">Menampilkan data selama</label>
                        <select class="form-select w-auto d-inline-block ms-2" id="sensorRangeFilter">
                            <option value="1">1 bulan</option>
                            <option value="3">3 bulan</option>
                            <option value="6">6 bulan</option>
                            <option value="12">1 tahun</option>
                        </select>
                        <label class="form-label fw-bold">&nbsp; terakhir</label>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Search Bar Script -->
    <script>
        document.getElementById('searchDeactivatedPond').addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('.deactivated-pond-row');
            let visible = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                if (name.startsWith(keyword)) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Tampilkan atau sembunyikan baris "tidak ditemukan"
            document.getElementById('noResultRow').classList.toggle('d-none', visible > 0);
        });
    </script>

    <!-- Average Data Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let selectedPondId = null;

            document.querySelectorAll(".show-sensor-modal").forEach(button => {
                button.addEventListener("click", function () {
                    selectedPondId = this.getAttribute("data-pond-id");

                    // Info kolam
                    document.getElementById("pondName").innerText = "Kolam : " + this.getAttribute("data-pond-name");
                    document.getElementById("fishAge").innerText = "Umur : " + this.getAttribute("data-fish-age");
                    document.getElementById("fishTotal").innerText = "Total : " + this.getAttribute("data-fish-total") + " ikan";

                    // Default 1 bulan
                    getAverageSensor(1);
                    document.getElementById("sensorRangeFilter").value = "1";
                });
            });

            document.getElementById("sensorRangeFilter").addEventListener("change", function () {
                const range = this.value;
                getAverageSensor(range);
            });

            function formatDateTime(datetime) {
                const date = new Date(datetime);
                return date.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                });
            }

            function getAverageSensor(months) {
                if (!selectedPondId) return;

                fetch(`/riwayat/${selectedPondId}/average-sensor?range=${months}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById("phValue").innerText = data.avg_ph ?? "N/A";
                        document.getElementById("temperatureValue").innerText = data.avg_temperature ?? "N/A";
                        document.getElementById("tdsValue").innerText = data.avg_tds ?? "N/A";
                        document.getElementById("conductivityValue").innerText = data.avg_conductivity ?? "N/A";
                        document.getElementById("salinityValue").innerText = data.avg_salinity ?? "N/A";

                        if (data.last_reading_time) {
                            document.getElementById("lastSensorTime").innerText = `(Status terakhir: ${formatDateTime(data.last_reading_time)})`;
                        } else {
                            document.getElementById("lastSensorTime").innerText = "";
                        }
                    })
                    .catch(err => {
                        console.error("Gagal memuat data rata-rata sensor:", err);
                    });
            }
        });
    </script>
</div>

@endsection