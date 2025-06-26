# 🪴 Monitoring Suhu & Kelembaban Tanaman Aglonema

Sistem ini dirancang untuk memantau kondisi **lingkungan tanaman Aglonema** secara real-time, seperti **suhu** dan **kelembaban**, menggunakan sensor DHT11/DHT22 + ESP8266, dengan tampilan web berbasis **PHP + MySQL** dan peringatan otomatis melalui **Bot Telegram**.

## 📷 Contoh Antarmuka
![aglonema bot tele](https://github.com/user-attachments/assets/58327027-852a-4176-96d8-b1320c8e5a2a)


## 🌿 Mengapa Penting?

Aglonema adalah tanaman hias tropis yang sangat sensitif terhadap suhu dan kelembaban. Pemantauan otomatis membantu:
- Mencegah layu akibat suhu terlalu panas
- Menjaga kelembaban ideal di lingkungan sekitarnya
- Memberikan notifikasi dini ketika kondisi tidak optimal

---

## 🛠️ Fitur

- ✅ Pantau suhu dan kelembaban secara real-time
- ✅ Simpan data ke MySQL
- ✅ Tampilan web auto-refresh dengan AJAX
- ✅ Notifikasi otomatis ke Telegram jika suhu terlalu tinggi

---

## 🔧 Teknologi

- Sensor: DHT11 / DHT22
- Mikrokontroler: ESP8266 / NodeMCU
- Backend: PHP + MySQL
- Frontend: HTML + CSS + JS
- Realtime Update: AJAX
- Bot Telegram (Alert System)

---

## 📁 Struktur Folder

/suhu/
├── assets/
│ ├── demo/ # File demo JS untuk grafik
│ ├── img/ # Gambar dan ikon UI
│ └── function.php # Fungsi umum
│
├── backend/
│ ├── pages/
│ │ ├── controller.php # Routing / logic
│ │ ├── dashboard.php # Tampilan dashboard suhu
│ │ ├── get_sensor_data.php # API data sensor untuk AJAX
│ │ ├── history.php # Riwayat data suhu
│ │ ├── insert.php # Endpoint untuk kirim data dari sensor
│ │ ├── monitoring.php # Realtime monitor
│ │ ├── receiver.php # (opsional) penerima data dari alat
│ │ └── status_cache.json # Cache status terakhir
│ ├── index.php
│ └── logout.php
│
├── css/
│ └── styles.css # Styling utama
│
├── js/
│ ├── datatables-simple-demo.js
│ ├── monitoring.js
│ └── scripts.js
│
└── index.php # Halaman awal / landing page
