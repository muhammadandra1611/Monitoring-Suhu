<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fungsi koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "db_monitoring2");

if (mysqli_connect_errno()) {
    echo "❌ Gagal koneksi ke MySQL: " . mysqli_connect_error();
    exit();
}

// Ambil data dari URL
$suhu = isset($_GET['suhu']) ? floatval($_GET['suhu']) : null;
$kelembaban = isset($_GET['kelembaban']) ? floatval($_GET['kelembaban']) : null;
$kelembaban_tanah = isset($_GET['kelembaban_tanah']) ? floatval($_GET['kelembaban_tanah']) : null;

// Validasi
if ($suhu !== null && $kelembaban !== null && $kelembaban_tanah !== null) {

    // Simpan data
    $query = "INSERT INTO tb_sensor_dht22 (suhu, kelembaban, kelembaban_tanah, waktu) 
              VALUES ('$suhu', '$kelembaban', '$kelembaban_tanah', NOW())";

    if (mysqli_query($koneksi, $query)) {
        echo "✅ Data berhasil disimpan!";

        // ================== NOTIF TELEGRAM ==================
        $token = "8127136990:AAEwZlBtf3hU9ReviYv8iSPXpNmO1gZfDiI";
        $chat_id = "8089786075";

        // Status sebelumnya disimpan dalam file JSON
        $status_file = __DIR__ . '/status_cache.json';
        $status = file_exists($status_file) ? json_decode(file_get_contents($status_file), true) : [];

        $notif = "";
        $send = false;

        if ($suhu > 30 && empty($status['suhu'])) {
            $notif .= "🔥 SUHU TINGGI: {$suhu}°C\n";
            $status['suhu'] = true;
            $send = true;
        } elseif ($suhu <= 30) {
            $status['suhu'] = false;
        }

        if ($kelembaban < 50 && empty($status['kelembaban'])) {
            $notif .= "💨 KELEMBABAN UDARA RENDAH: {$kelembaban}%\n";
            $status['kelembaban'] = true;
            $send = true;
        } elseif ($kelembaban >= 50) {
            $status['kelembaban'] = false;
        }

        if ($kelembaban_tanah < 50 && empty($status['tanah'])) {
            $notif .= "🌱 KELEMBABAN TANAH RENDAH: {$kelembaban_tanah}%\n";
            $status['tanah'] = true;
            $send = true;
        } elseif ($kelembaban_tanah >= 50) {
            $status['tanah'] = false;
        }

        // Kirim jika ada peringatan baru
        if ($send) {
            $url = "https://api.telegram.org/bot$token/sendMessage";

            $post = [
                'chat_id' => $chat_id,
                'text' => $notif
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            curl_close($ch);
        }

        // Simpan status
        file_put_contents($status_file, json_encode($status));

        // ================== LIMIT DATA MAKSIMAL 100 ==================
        $data_terbaru = mysqli_query($koneksi, "
            SELECT suhu, kelembaban, kelembaban_tanah, waktu 
            FROM tb_sensor_dht22 
            ORDER BY waktu DESC LIMIT 100
        ");

        $data_array = [];
        while ($row = mysqli_fetch_assoc($data_terbaru)) {
            $data_array[] = $row;
        }

        // Truncate dan insert ulang
        mysqli_query($koneksi, "TRUNCATE TABLE tb_sensor_dht22");

        $data_array = array_reverse($data_array); // dari lama ke baru
        foreach ($data_array as $row) {
            mysqli_query($koneksi, "
                INSERT INTO tb_sensor_dht22 (suhu, kelembaban, kelembaban_tanah, waktu)
                VALUES ('{$row['suhu']}', '{$row['kelembaban']}', '{$row['kelembaban_tanah']}', '{$row['waktu']}')
            ");
        }

        echo " 🔁 Data dibatasi maksimal 100 baris dan ID direset.";

    } else {
        echo "❌ Gagal menyimpan data: " . mysqli_error($koneksi);
    }

} else {
    echo "⚠️ Parameter tidak lengkap!";
}

mysqli_close($koneksi);
?>
