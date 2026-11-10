#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "YOUR_WIFI_NAME";
const char* password = "YOUR_WIFI_PASSWORD";
// Change to your server IP
const char* serverURL = "http://192.168.1.100/fatigue/api/alert.php";

#define BUZZER 13
#define LED_RED 12
#define BUTTON 14 // Simulated fatigue trigger for testing

void setup() {
  Serial.begin(115200);
  pinMode(BUZZER, OUTPUT);
  pinMode(LED_RED, OUTPUT);
  pinMode(BUTTON, INPUT_PULLUP);
  WiFi.begin(ssid, password);
  Serial.print("Connecting WiFi");
  while (WiFi.status() != WL_CONNECTED) { delay(500); Serial.print("."); }
  Serial.println("\nConnected!");
}

void triggerAlert(String type, float ear) {
  // 1. Local alert in vehicle
  digitalWrite(LED_RED, HIGH);
  for(int i=0; i<3; i++){
    digitalWrite(BUZZER, HIGH); delay(300);
    digitalWrite(BUZZER, LOW); delay(200);
  }
  digitalWrite(LED_RED, LOW);

  // 2. Send to PHP backend for real-time notification
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverURL);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String data = "driver_id=1&alert_type=" + type + "&ear_value=" + String(ear);
    int code = http.POST(data);
    Serial.println("PHP Response: " + String(code) + " " + http.getString());
    http.end();
  }
}

void loop() {
  // In real system, this is called by Raspberry Pi / Python via Serial
  // For testing, press button to simulate fatigue
  if (digitalRead(BUTTON) == LOW) {
    triggerAlert("EYE_CLOSED", 0.15);
    delay(2000);
  }
}
