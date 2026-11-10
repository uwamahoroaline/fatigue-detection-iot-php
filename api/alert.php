<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// DB connection
$conn = new mysqli("localhost", "root", "", "fatigue_db");
if ($conn->connect_error) {
    die(json_encode(["status"=>"error", "message"=>"DB failed"]));
}

$driver_id = $_POST['driver_id'] ?? 1;
$alert_type = $_POST['alert_type'] ?? 'EYE_CLOSED';
$ear_value = $_POST['ear_value'] ?? 0;

$stmt = $conn->prepare("INSERT INTO alerts (driver_id, alert_type, ear_value) VALUES (?, ?, ?)");
$stmt->bind_param("isd", $driver_id, $alert_type, $ear_value);
$stmt->execute();

echo json_encode([
    "status" => "success",
    "message" => "Alert saved! Notification sent to operator.",
    "alert_id" => $stmt->insert_id
]);
?>
