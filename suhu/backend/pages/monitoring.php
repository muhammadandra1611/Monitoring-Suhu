<?php
// Ambil data dari database
$result = mysqli_query($koneksi, "SELECT * FROM sensor_data ORDER BY waktu DESC LIMIT 10");

// Ambil data terakhir untuk ditampilkan di kartu
$latestData = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM sensor_data ORDER BY waktu DESC LIMIT 1"));
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Monitoring</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Sensor Parameters</li>
    </ol>

    <!-- Tabel Riwayat Data -->
   <!-- <table class="table table-striped"> -->
        <!-- <thead>
            <tr>
                <th>Waktu</th>
                <th>Suhu (°C)</th>
                <th>Kelembaban Udara (%)</th>
                <th>Kelembaban Tanah (%)</th>
            </tr>
        </thead> -->
        <!--<tbody> -->
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <?php error_reporting(E_ERROR | E_PARSE); ?>
                    <td><?= $row['']; ?></td>
                    <td><?= $row['']; ?></td>
                    <td><?= $row['']; ?></td>
                    <td><?= $row['']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Kartu Monitoring -->
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-water me-1"></i> Kelembaban Tanah</div>
                <div class="card-body text-center">
                    <h2 id="soil-moisture">   <p>Persentase (<?= $latestData[''] ?? '--'; ?> %)</p></h2>
                 
                    <canvas id="soilMoistureChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-temperature-high me-1"></i> Suhu</div>
                <div class="card-body text-center">
                    <?php 


// Ambil data dari database
$result2 = mysqli_query($koneksi, "SELECT * FROM  tb_sensor_dht22 ORDER BY waktu DESC LIMIT 10");

// Ambil data terakhir untuk ditampilkan di kartu
$dht22 = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM  tb_sensor_dht22 ORDER BY waktu DESC LIMIT 1"));

                    ?>
                    <h2 id="temperature"> <p>Celsius (<?= $dht22['suhu'] ?? '--'; ?> °C)</p></h2>
                   
                    <canvas id="temperatureChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-wind me-1"></i> Kelembapan Udara</div>
                <div class="card-body text-center">
                    <h2 id="air-quality"><p>Celsius (<?= $dht22['kelembaban'] ?? '--'; ?> °C)</p></h2>
           
                            
                    <canvas id="airQualityChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Real-time -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-chart-line me-1"></i> Grafik Real-time</div>
                <div class="card-body">
                    <canvas id="realTimeChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk update data realtime -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function updateSensorValues() {
        console.log('Mengambil data sensor...');

        $.getJSON('pages/get_sensor_data.php', function (data) {
            console.log('Data terbaru:', data);

            $('#soil-moisture').html(`<p>Persentase (${data.soil_moisture ?? '--'} %)</p>`);
            $('#temperature').html(`<p>Celsius (${data.temperature ?? '--'} °C)</p>`);
            $('#air-quality').html(`<p>Celsius (${data.air_quality ?? '--'} %)</p>`);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error('Gagal ambil data:', textStatus, errorThrown);
        });
    }

    // Update tiap 5 detik
    setInterval(updateSensorValues, 2000);
});
</script>

