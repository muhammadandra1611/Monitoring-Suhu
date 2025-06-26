<?php
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Controller</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Control Waterpump</li>
    </ol>
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-power-off me-1"></i>
                    Kontrol Penyiraman
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <button class="btn btn-success btn-lg" id="sensorOn" onclick="controlSensor('on')">
                            <i class="fas fa-power-off me-2"></i>Nyalakan 
                        </button>
                        <button class="btn btn-danger btn-lg" id="sensorOff" onclick="controlSensor('off')">
                            <i class="fas fa-power-off me-2"></i>Matikan 
                        </button>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-info" id="statusMessage">
                            Status: <span id="sensorStatus">Tidak Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cog me-1"></i>
                    Status Sensor
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sensor</th>
                                    <th>Status</th>
                                    <th>Terakhir Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sensor Kelembapan</td>
                                    <td><span class="badge bg-success" id="moistureStatus">Aktif</span></td>
                                    <td id="moistureLastActive">-</td>
                                </tr>
                                <tr>
                                    <td>Sensor Suhu</td>
                                    <td><span class="badge bg-success" id="tempStatus">Aktif</span></td>
                                    <td id="tempLastActive">-</td>
                                </tr>
                                <tr>
                                    <td>Sensor Kualitas Udara</td>
                                    <td><span class="badge bg-success" id="airStatus">Aktif</span></td>
                                    <td id="airLastActive">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function controlSensor(action) {
    // Send control command to Raspberry Pi
    fetch('control_sensor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('sensorStatus').textContent = action === 'on' ? 'Aktif' : 'Tidak Aktif';
        document.getElementById('statusMessage').className = 
            action === 'on' ? 'alert alert-success' : 'alert alert-warning';
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('statusMessage').className = 'alert alert-danger';
        document.getElementById('statusMessage').textContent = 'Error: Gagal mengontrol';
    });
}

// Update component status periodically
setInterval(() => {
    fetch('get_system_status.php')
        .then(response => response.json())
        .then(data => {
            // Update status badges and last active times
            updateComponentStatus(data);
        })
        .catch(error => console.error('Error:', error));
}, 5000);

function updateComponentStatus(data) {
    // Update status badges and timestamps for each component
    const components = ['moisture', 'temp', 'air'];
    components.forEach(component => {
        if (data[component]) {
            document.getElementById(`${component}Status`).className = 
                data[component].active ? 'badge bg-success' : 'badge bg-danger';
            document.getElementById(`${component}LastActive`).textContent = 
                data[component].lastActive;
        }
    });
}
</script> 