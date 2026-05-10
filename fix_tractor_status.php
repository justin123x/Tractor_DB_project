<?php
// Database connection
$host = 'localhost';
$dbname = 'tracktor_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.<br>";
    
    // First, let's see what tractors we have and their statuses
    $stmt = $pdo->query("SELECT tractor_id, tractor_name, brand, status FROM tractors");
    $tractors = $stmt->fetchAll();
    
    echo "<h2>Current tractor statuses:</h2>";
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Brand</th><th>Status</th></tr>";
    foreach ($tractors as $tractor) {
        echo "<tr>";
        echo "<td>" . $tractor['tractor_id'] . "</td>";
        echo "<td>" . htmlspecialchars($tractor['tractor_name']) . "</td>";
        echo "<td>" . htmlspecialchars($tractor['brand']) . "</td>";
        echo "<td>" . htmlspecialchars($tractor['status']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count tractors with empty/null status
    $stmt = $pdo->query("SELECT COUNT(*) FROM tractors WHERE status = '' OR status IS NULL");
    $emptyCount = $stmt->fetchColumn();
    echo "<p>Found $emptyCount tractors with empty or NULL status.</p>";
    
    // Update empty/null statuses to 'available'
    if ($emptyCount > 0) {
        $stmt = $pdo->prepare("UPDATE tractors SET status = 'available' WHERE status = '' OR status IS NULL");
        $stmt->execute();
        echo "<p>Successfully updated " . $stmt->rowCount() . " tractors to 'available' status.</p>";
    } else {
        echo "<p>No tractors need updating.</p>";
    }
    
    // Check again after update
    $stmt = $pdo->query("SELECT tractor_id, tractor_name, brand, status FROM tractors");
    $tractors = $stmt->fetchAll();
    
    echo "<h2>Tractor statuses after update:</h2>";
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Brand</th><th>Status</th></tr>";
    foreach ($tractors as $tractor) {
        echo "<tr>";
        echo "<td>" . $tractor['tractor_id'] . "</td>";
        echo "<td>" . htmlspecialchars($tractor['tractor_name']) . "</td>";
        echo "<td>" . htmlspecialchars($tractor['brand']) . "</td>";
        echo "<td>" . htmlspecialchars($tractor['status']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count available tractors
    $stmt = $pdo->query("SELECT COUNT(*) FROM tractors WHERE status = 'available'");
    $availableCount = $stmt->fetchColumn();
    echo "<p>Total available tractors: $availableCount</p>";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>