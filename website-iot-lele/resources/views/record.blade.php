@extends('app')

@section('content')

<div class="container bg-light rounded mt-4">
    <h2>Water Quality Statistics</h2>
    <form method="GET" action="{{ route('dashboard.statistics') }}">
        <label>Start Date: <input type="date" name="start_date" value="{{ request('start_date', now()->subMonth()->toDateString()) }}"></label>
        <label>End Date: <input type="date" name="end_date" value="{{ request('end_date', now()->toDateString()) }}"></label>
        <button type="submit">Filter</button>
    </form>
    <canvas id="waterQualityChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('waterQualityChart').getContext('2d');
    const data = @json($readings); // Ensure the readings contain tds, conductivity, and salinity
    const labels = data.map(r => new Date(r.created_at).toLocaleDateString());
    
    // Extracting values for all parameters
    const phValues = data.map(r => r.ph);
    const tempValues = data.map(r => r.temperature);
    const tdsValues = data.map(r => r.tds); // Assuming `tds` is in your data
    const conductivityValues = data.map(r => r.conductivity); // Assuming `conductivity` is in your data
    const salinityValues = data.map(r => r.salinity); // Assuming `salinity` is in your data

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'pH Levels', data: phValues, borderColor: 'blue', fill: false },
                { label: 'Temperature (°C)', data: tempValues, borderColor: 'red', fill: false },
                { label: 'TDS (ppm)', data: tdsValues, borderColor: 'green', fill: false },
                { label: 'Conductivity (µS/cm)', data: conductivityValues, borderColor: 'orange', fill: false },
                { label: 'Salinity (ppt)', data: salinityValues, borderColor: 'purple', fill: false }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: false, // Optional: Adjust to suit your data
                    title: {
                        display: true,
                        text: 'Measurement Values'
                    }
                }
            }
        }
    });
</script>


@endsection