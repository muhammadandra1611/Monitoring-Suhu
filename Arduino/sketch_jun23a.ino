#include <ESP8266WiFi.h>
#include <DHT.h>
#include <ESP8266HTTPClient.h>

#define DHTPIN 4          // Pin DHT22 terhubung ke D2 (GPIO4)
#define DHTTYPE DHT22

const char* ssid = "WAWAN 6969";        // Ganti dengan SSID WiFi kamu
const char* password = "HERMAWAN";      // Ganti dengan password WiFi kamu

const char* serverName = "http://192.168.1.13:8080/suhu/backend/pages/insert.php";

DHT dht(DHTPIN, DHTTYPE);

void setup() {
  Serial.begin(9600);
  WiFi.begin(ssid, password);
  dht.begin();

  Serial.print("Menghubungkan WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nTerhubung ke WiFi!");
}

void loop() {
  float suhu = dht.readTemperature();
  float kelembaban = dht.readHumidity();
  int kelembabanTanah = analogRead(A0); // Membaca sensor tanah dari A0

  if (isnan(suhu) || isnan(kelembaban)) {
    Serial.println("❌ Gagal membaca dari sensor DHT!");
    delay(2000);
    return;
  }

  // Tampilkan ke serial monitor
  Serial.println("📡 Mengirim data ke server:");
  Serial.print("Suhu: "); Serial.print(suhu); Serial.println(" °C");
  Serial.print("Kelembaban: "); Serial.print(kelembaban); Serial.println(" %");
  Serial.print("Kelembaban Tanah: "); Serial.println(kelembabanTanah);

  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    // Format URL dengan parameter GET
    String serverPath = String(serverName) + 
      "?suhu=" + String(suhu) + 
      "&kelembaban=" + String(kelembaban) + 
      "&kelembaban_tanah=" + String(kelembabanTanah);

    http.begin(client, serverPath);  // Kirim data lewat URL
    int httpResponseCode = http.GET();

    Serial.print("✅ Response: ");
    Serial.println(httpResponseCode);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("📝 Response dari server:");
      Serial.println(response);
    } else {
      Serial.println("❌ Gagal mengirim request");
    }

    http.end();
  } else {
    Serial.println("⚠️ WiFi tidak terhubung!");
  }

  delay(2000);  // Kirim data setiap 10 detik
}
