CREATE DATABASE fatigue_db;
USE fatigue_db;

CREATE TABLE drivers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  plate_no VARCHAR(20),
  phone VARCHAR(20)
);

CREATE TABLE alerts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  driver_id INT,
  alert_type VARCHAR(50), -- 'EYE_CLOSED' or 'YAWNING'
  ear_value FLOAT,
  alert_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(20) DEFAULT 'NEW',
  FOREIGN KEY (driver_id) REFERENCES drivers(id)
);

INSERT INTO drivers (name, plate_no, phone) 
VALUES ('Jean Bosco', 'RAB 123A', '0780000000');
