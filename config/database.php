<?php
/**
 * ============================================================
 * TRACKTOR: Tractor Booking System
 * Database Configuration File
 * ============================================================
 * Configure your XAMPP MySQL connection settings below.
 * Default XAMPP credentials: root / no password
 * ============================================================
 */

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'tractor_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // Default XAMPP has no password

/**
 * Get PDO database connection
 * Uses PDO for secure, prepared-statement-based queries
 * 
 * @return PDO|null Returns PDO connection object or null on failure
 */
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Get MySQLi connection (used for some legacy-style queries)
 * 
 * @return mysqli|null Returns mysqli connection object or null on failure
 */
function getMySQLiConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Auto-connect to database
$conn = getMySQLiConnection();
?>