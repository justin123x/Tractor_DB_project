<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$sql = "SELECT t.tractor_id, t.tractor_name, t.brand, t.status, t.image_path FROM tractors t";
$result = mysqli_query($conn, $sql);

echo "All tractors:<br>";
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['tractor_id'] . ", Name: " . $row['tractor_name'] . ", Brand: " . $row['brand'] . ", Status: '" . $row['status'] . "', Image: " . $row['image_path'] . "<br>";
    }
} else {
    echo "No tractors found<br>";
}

$sql = "SELECT t.tractor_id, t.tractor_name, t.brand, t.status, t.image_path FROM tractors t WHERE t.status = 'available'";
$result = mysqli_query($conn, $sql);

echo "<br>Available tractors:<br>";
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['tractor_id'] . ", Name: " . $row['tractor_name'] . ", Brand: " . $row['brand'] . ", Status: '" . $row['status'] . "', Image: " . $row['image_path'] . "<br>";
    }
} else {
    echo "No available tractors found<br>";
}