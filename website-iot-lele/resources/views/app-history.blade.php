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
                    <th>Lihat Data</th>
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
                                data-fish-age="{{ $pond->age_fish }}"
                                data-fish-total="{{ $pond->total_fish }}"
                                data-bs-toggle="modal"
                                data-bs-target="#sensorModal">
                                <i class="fas fa-eye"></i>&nbsp;&nbsp;Lihat Status Terakhir
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
                    <h5 class="modal-title" id="sensorModalTitle">Status Terakhir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center mb-3">
                        <h5 id="pondName"></h5>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <h5 id="fishAge"></h5>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <h5 id="fishTotal"></h5>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let selectedPondId = null;

            // Fungsi untuk update isi modal
            function updateModalInfo(button) {
                selectedPondId = button.getAttribute("data-pond-id");
                document.getElementById("pondName").innerText = "Kolam : " + button.getAttribute("data-pond-name");
                document.getElementById("fishAge").innerText = "Umur : " + button.getAttribute("data-fish-age") + " hari";
                document.getElementById("fishTotal").innerText = "Total : " + button.getAttribute("data-fish-total") + " ikan";
            }

            // Fungsi ambil dan tampilkan average sensor
            function loadAverageSensor(range) {
                if (!selectedPondId) return;

                fetch(`/riwayat/${selectedPondId}/average-sensor?range=${range}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.average) {
                            document.getElementById("phValue").innerText = data.average.avg_ph ?? "N/A";
                            document.getElementById("temperatureValue").innerText = data.average.avg_temperature ?? "N/A";
                            document.getElementById("tdsValue").innerText = data.average.avg_tds ?? "N/A";
                            document.getElementById("conductivityValue").innerText = data.average.avg_conductivity ?? "N/A";
                            document.getElementById("salinityValue").innerText = data.average.avg_salinity ?? "N/A";
                        } else {
                            // Kalau tidak ada data, tampilkan N/A semua
                            ["phValue", "temperatureValue", "tdsValue", "conductivityValue", "salinityValue"].forEach(id => {
                                document.getElementById(id).innerText = "N/A";
                            });
                        }
                    })
                    .catch(err => {
                        console.error("Error:", err);
                    });
            }

            // Event: klik tombol lihat sensor
            document.querySelectorAll(".show-sensor-modal").forEach(button => {
                button.addEventListener("click", () => {
                    updateModalInfo(button);
                    document.getElementById("sensorRangeFilter").value = "1"; // set default
                    loadAverageSensor(1);
                });
            });

            // Event: ubah filter bulan
            document.getElementById("sensorRangeFilter").addEventListener("change", function () {
                loadAverageSensor(this.value);
            });
        });
    </script>
</div>

@endsection