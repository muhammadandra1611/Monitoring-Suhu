<?php
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">History</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Sensor Measurement History</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data Historis Sensor
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="col-md-3">
                    <label for="endDate" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-primary d-block" onclick="filterData()">Filter</button>
                </div>
            </div>
            <table id="historyTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Kelembapan Tanah (%)</th>
                        <th>Suhu (°C)</th>
                        <th>Kualitas Udara (PPM)</th>
                    </tr>
                </thead>
                <tbody id="historyData">
                    <!-- Data will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default date range (last 7 days)
    const today = new Date();
    const lastWeek = new Date(today.getTime() - (7 * 24 * 60 * 60 * 1000));
    
    document.getElementById('startDate').value = lastWeek.toISOString().split('T')[0];
    document.getElementById('endDate').value = today.toISOString().split('T')[0];
    
    // Initial load of data
    loadHistoryData();
});

function filterData() {
    loadHistoryData();
}

function loadHistoryData() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    fetch(`get_history.php?start=${startDate}&end=${endDate}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('historyData');
            tbody.innerHTML = '';
            
            data.forEach(record => {
                const row = document.createElement('tr');
                const date = new Date(record.timestamp);
                
                row.innerHTML = `
                    <td>${date.toLocaleDateString()}</td>
                    <td>${date.toLocaleTimeString()}</td>
                    <td>${record.soil_moisture}</td>
                    <td>${record.temperature}</td>
                    <td>${record.air_quality}</td>
                `;
                
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}
</script> 