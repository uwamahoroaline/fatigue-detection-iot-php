fatigue-detection-iot-php/
├── database.sql
├── api/
│   ├── alert.php         // IoT -> PHP : send alert
│   └── get_alerts.php    // Dashboard -> get alerts
├── dashboard/
│   └── index.php         // Fleet operator dashboard
├── iot/
│   └── esp32_alert.ino   // ESP32 buzzer + LED
└── detection/
    └── detect.py         // Python + OpenCV fatigue detection
