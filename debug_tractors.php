<?php
require_once __DIR__ . '/config/database.php';

// Check tractor statuses
$sql = "SELECT tractor_id, tractor_name, brand, status, image_path FROM tractors";
$result = mysqli_query($conn, $sql);

echo "<h2>All Tractors:</h2>";
echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Brand</th><th>Status</th><th>Image Path</th></tr>";
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['tractor_id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['tractor_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['image_path']) . "</td>";
        echo "</tr>";
    }
}
echo "</table>";

// Check available tractors specifically
$sql = "SELECT COUNT(*) as count FROM tractors WHERE status = 'available'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo "<p>Available tractors count: " . $row['count'] . "</p>";

// Update any empty statuses
$sql = "UPDATE tractors SET status = 'available' WHERE status = '' OR status IS NULL";
if (mysqli_query($conn, $sql)) {
    echo "<p>Updated " . mysqli_affected_rows($conn) . " tractors to 'available'.</p>";
} else {
    echo "<p>Error: " . mysqli_error($conn) . "</p>";
}

// Check again after update
$sql = "SELECT tractor_id, tractor_name, brand, status FROM tractors WHERE status = 'available'";
$result = mysqli_query($conn, $sql);
echo "<h2>Available Tractors After Update:</h2>";
echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Brand</th><th>Status</th></tr>";
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['tractor_id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['tractor_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<p>No available tractors found.</p>";
}
echo "</table>";
?>