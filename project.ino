#include <DHT.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <ESP8266WiFi.h>

#define DHT_PIN D1
#define DHT_TYPE DHT22
#define RELAY_PIN D2
#define SOIL_MOISTURE_PIN A0

const char *apiUrl = "http://192.168.225.127/pro_iot/reddist.php";
const int SOIL_MOISTURE_THRESHOLD_DEFAULT = 500; // ค่าความชื้นในดินที่กำหนดให้รดน้ำ (ค่าเริ่มต้น)
const float TEMPERATURE_THRESHOLD = 30.0;        // อุณหภูมิที่กำหนดให้รดน้ำ

const char *ssid = "Onizuka";
const char *password = "Por091111";

DHT dht(DHT_PIN, DHT_TYPE);

void setup() {
  Serial.begin(115200);

  // เชื่อมต่อ WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(250);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");

  pinMode(RELAY_PIN, OUTPUT);
  dht.begin();
}

void loop() {
  delay(1000);
  // ทำ HTTP GET request
  HTTPClient http;
  WiFiClient client;
  http.begin(client, apiUrl);

  int httpCode = http.GET();

  if (httpCode == HTTP_CODE_OK) {
    String payload = http.getString();
    deserializeAndHandleJson(payload);
  } else {
    Serial.printf("[HTTP] GET request failed, error: %s\n", http.errorToString(httpCode).c_str());
  }

  http.end();

  // Remove the following delay if it's not necessary
  delay(5000);  // รอ 5 วินาที
}


void deserializeAndHandleJson(String payload) {
  const size_t capacity = JSON_ARRAY_SIZE(1) + JSON_OBJECT_SIZE(2) + 100;
StaticJsonDocument<capacity> doc;


  // แปลงข้อมูล JSON
  DeserializationError error = deserializeJson(doc, payload);

  if (error) {
    Serial.print(F("Failed to parse JSON: "));
    Serial.println(error.c_str());
    return;
  }

  // ตรวจสอบว่าอยู่ในรูปแบบที่ถูกต้อง
  if (doc.is<JsonArray>()) {
    JsonArray array = doc.as<JsonArray>();
    
    // ตรวจสอบว่ามีข้อมูลในอาร์เรย์
    if (array.size() > 0) {
      // ดึงค่า humidity จาก JSON
      int humidityThreshold = array[0]["humidity"];
      Serial.print("Humidity Threshold: ");
      Serial.println(humidityThreshold);

      // ตรวจสอบค่าความชื้นเพื่อตัดสินใจ
      float h = dht.readHumidity();
      float t = dht.readTemperature();
      float soilMoisture = analogRead(SOIL_MOISTURE_PIN);

      Serial.print("Humidity: ");
      Serial.print(h);
      Serial.print(" %\t");
      Serial.print("Temperature: ");
      Serial.print(t);
      Serial.print(" °C\t");
      Serial.print("Soil Moisture: ");
      Serial.print(soilMoisture, 2);
      Serial.println(" %");

      // ตรวจสอบความชื้นในดินและการรดน้ำ
      if (soilMoisture > humidityThreshold) {
        digitalWrite(RELAY_PIN, LOW);
        Serial.println("Water the plant!");
      } else {
        digitalWrite(RELAY_PIN, HIGH);
        Serial.println("No need to water.");
      }
    } else {
      Serial.println(F("Empty array."));
    }
  } else {
    Serial.println(F("JSON is not an array."));
  }
}
