@extends('app')

@section('content')

<div class="container mt-4">
    <h4 class="page-title">Dasbor</h4>
    <div class="row g-3 align-items-stretch">

        <!-- Left Column -->
        <div class="col-md-8 d-flex flex-column">
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

                    <div style="overflow-x: auto;">
                        <canvas id="waterQualityChart" style="min-width: 600px; height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4 d-flex flex-column">
            <!-- Top Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-light border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">🐟 Usia & Jumlah Ikan</h5>
                </div>

                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="w-100" style="max-width: 300px; margin: auto;">
                        <canvas id="fishPieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bottom Card -->
            <div class="card p-3 border-0 shadow-sm bg-warning-subtle text-dark flex-grow-1">
                <div class="fw-semibold mb-2">📌 Info Tambahan</div>
                <p class="mb-0 small">Tambahkan detail atau informasi penting lainnya di sini. Misalnya: total pakan, peringatan, atau status kolam.</p>
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

    new Chart(document.getElementById('waterQualityChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: waterLabels,
            datasets: [
                { label: 'pH', data: waterData.map(r => r.ph), borderColor: '#007bff', tension: 0.4 },
                { label: 'Suhu (°C)', data: waterData.map(r => r.temperature), borderColor: '#dc3545', tension: 0.4 },
                { label: 'TDS (ppm)', data: waterData.map(r => r.tds), borderColor: '#28a745', tension: 0.4 },
                { label: 'Konduktivitas (µS/cm)', data: waterData.map(r => r.conductivity), borderColor: '#fd7e14', tension: 0.4 }
            ]
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
                    ticks: { font: { size: 10 } },
                    grid: { color: '#f1f1f1' }
                }
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('fishPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($agechart->pluck('age_fish')->map(fn($age) => $age . " Bulan")) !!},
                datasets: [{
                    data: {!! json_encode($agechart->pluck('total')) !!},
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#6610f2', '#fd7e14'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 12 } }
                    },
                    datalabels: {
                        color: '#fff',
                        font: { size: 13, weight: 'bold' },
                        formatter: (value) => value
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.parsed} ekor`;
                            }
                        }
                    }
                },
                layout: { padding: 10 },
                cutout: '60%'
            },
            plugins: [ChartDataLabels]
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