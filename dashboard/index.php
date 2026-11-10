<?php
$conn = new mysqli("localhost", "root", "", "fatigue_db");
$result = $conn->query("SELECT a.*, d.name, d.plate_no 
FROM alerts a JOIN drivers d ON a.driver_id = d.id 
ORDER BY a.alert_time DESC LIMIT 20");
?>
<!DOCTYPE html>
<html>
<head><title>Fleet Fatigue Dashboard</title>
<meta http-equiv="refresh" content="5">
<style>
body{font-family:Arial; padding:20px; background:#f4f4f4}
.alert{background:#ffdddd; border-left:6px solid red; padding:15px; margin:10px 0; border-radius:8px}
.ok{background:#ddffdd; border-left:6px solid green}
h1{color:#333}
</style>
</head>
<body>
<h1>🚨 Driver Fatigue Monitoring - Real Time</h1>
<p>Auto-refresh every 5 seconds</p>

<?php while($row = $result->fetch_assoc()): ?>
<div class="alert">
  <b>Driver:</b> <?= $row['name'] ?> (<?= $row['plate_no'] ?>)<br>
  <b>Alert:</b> <?= $row['alert_type'] ?> | EAR: <?= $row['ear_value'] ?><br>
  <b>Time:</b> <?= $row['alert_time'] ?> | <b>Status:</b> <?= $row['status'] ?>
</div>
<?php endwhile; ?>
</body>
</html>
