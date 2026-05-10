<?php
require_once __DIR__ . '/config/database.php';

// Fix tractor statuses
$sql = "UPDATE tractors SET status = 'available' WHERE status = '' OR status IS NULL";
if (mysqli_query($conn, $sql)) {
    echo "Successfully updated " . mysqli_affected_rows($conn) . " tractors to 'available' status.<br>";
} else {
    echo "Error updating tractors: " . mysqli_error($conn) . "<br>";
}

// Check current statuses
$sql = "SELECT tractor_id, tractor_name, brand, status FROM tractors ORDER BY tractor_id";
$result = mysqli_query($conn, $sql);

echo "<h2>Current Tractor Statuses:</h2>";
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
}
echo "</table>";

// Count available tractors
$sql = "SELECT COUNT(*) as count FROM tractors WHERE status = 'available'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo "<p>Total available tractors: " . $row['count'] . "</p>";
?>