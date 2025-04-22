@extends('app')

@section('content')

<div class="container mt-3">
    <h4 class="page-title">Dasbor</h4>
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6 d-flex flex-column">
            <div class="card border-0 shadow-sm rounded-3 flex-grow-1 h-100">
                <div class="card-header bg-light border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">📊 Grafik Kualitas Air</h5>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.statistics') }}" class="mb-4">
                        <div class="row g-3 align-items-center">
                            <!-- Date Range -->
                            <div class="col-12 col-md-auto">
                                <label class="form-label mb-1">Start Date:</label>
                                <input class="form-control" type="date" name="start_date"
                                    value="{{ request('start_date', now()->subMonth()->toDateString()) }}"
                                    onchange="this.form.submit()">
                            </div>

                            <div class="col-12 col-md-auto">
                                <label class="form-label mb-1">End Date:</label>
                                <input class="form-control" type="date" name="end_date"
                                    value="{{ request('end_date', now()->toDateString()) }}"
                                    onchange="this.form.submit()">
                            </div>

                            <!-- Pond Dropdown -->
                            <div class="col-12 col-md-auto">
                                <label class="form-label mb-1">Kolam:</label>
                                <select class="form-select" name="id_pond" onchange="this.form.submit()">
                                    @foreach($ponds as $pond)
                                        <option value="{{ $pond->id_pond }}"
                                            {{ request('id_pond', $selectedPondId) == $pond->id_pond ? 'selected' : '' }}>
                                            {{ $pond->name_pond }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <div style="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><canvas id="chartPh"></canvas></div>
                    <div><canvas id="chartTemp"></canvas></div>
                    <div><canvas id="chartTds"></canvas></div>
                    <div><canvas id="chartCond"></canvas></div>
                    <div><canvas id="chartSalinity"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6 d-flex flex-column" style="height: 100%;">
            <!-- Wrapper Flex -->
            <div class="d-flex flex-column h-100">

                <!-- Top Card: Usia & Jumlah Ikan -->
                <div class="card border-0 shadow-sm rounded-3 mb-3 flex-grow-1">
                    <div class="card-header bg-light border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">🐟 Usia & Jumlah Ikan</h5>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <canvas id="fishPieChart" style="max-height: 200px;"></canvas>
                        <ul class="mt-4 list-unstyled d-flex flex-wrap justify-content-center text-center" style="max-width: 600px;">
                            @foreach ($agechart as $data)
                                <li style="flex: 0 0 50%; padding: 4px 0;">
                                    <strong>{{ $data->name_pond }}</strong> - {{ $data->age_fish }} hari - {{ $data->total }} ikan
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Bottom Card: Status Feeder -->
                <div class="card border-0 shadow-sm" style="min-height: 200px;">
                    <div class="card-header bg-light border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">🥫 Status Tempat Pakan</h5>
                    </div>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>Nama Kolam</th>
                                <th>Status Tempat Pakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($feeders as $feeder)
                                <tr class="text-center">
                                    <td>{{ $feeder->name_pond }}</td>
                                    <td>
                                        @if ($feeder->feeder_status === 'Kosong')
                                            <span class="badge bg-danger">Kosong</span>
                                        @elseif ($feeder->feeder_status === 'Terisi')
                                            <span class="badge bg-success">Terisi</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $feeder->feeder_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
</div>

<!-- Chart Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
     const waterData = @json($readings);

     const waterLabels = waterData.map(r => {
        const date = new Date(r.created_at);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: '2-digit' }) + ' ' +
            date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
    });

    function generateChart(canvasId, label, data, color) {
        new Chart(document.getElementById(canvasId).getContext('2d'), {
            type: 'line',
            data: {
                labels: waterLabels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: color,
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 10,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        ticks: { font: { size: 10 }, maxRotation: 45 },
                        grid: { display: false }
                    },
                    y: {
                        title: { display: true, text: 'Nilai', font: { size: 13 } },
                        ticks: { font: { size: 14 } },
                        grid: { color: '#f1f1f1' }
                    }
                }
            }
        });
    }

    // Generate each chart
    generateChart('chartPh', 'pH', waterData.map(r => r.ph), '#007bff');
    generateChart('chartTemp', 'Suhu (°C)', waterData.map(r => r.temperature), '#dc3545');
    generateChart('chartTds', 'TDS (ppm)', waterData.map(r => r.tds), '#28a745');
    generateChart('chartCond', 'Konduktivitas (µS/cm)', waterData.map(r => r.conductivity), '#fd7e14');
    generateChart('chartSalinity', 'Salinitas (ppt)', waterData.map(r => r.salinity), '#6f42c1'); // purple-ish
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('fishPieChart').getContext('2d');

    const labels = {!! json_encode($agechart->pluck('name_pond')) !!};
    const data = {!! json_encode($agechart->pluck('total')) !!};

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#17a2b8', '#6610f2',
                    '#fd7e14', '#dc3545', '#6c757d', '#20c997', '#ff66cc'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.label}: ${context.parsed} ekor`;
                        }
                    }
                }
            },
            layout: {
                padding: 10
            }
        }
    });
});
</script>

<style>
    .card {
        min-height: 100%;
    }

    @media (max-width: 767.98px) {
        .card {
            min-height: auto;
        }
    }
</style>

@endsection