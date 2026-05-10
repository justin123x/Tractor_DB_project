<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// Generate CSRF token for all authenticated pages
if (isLoggedIn()) {
    generateCsrfToken();
}

$current_file = basename($_SERVER['PHP_SELF']);
$request_uri = $_SERVER['REQUEST_URI'];
$base_url = getBaseUrl();

// Redirect unauthenticated users from protected pages
$auth_pages = ['login.php', 'register.php', 'login-staff.php', 'register-staff.php'];
if (!isLoggedIn() && !in_array($current_file, $auth_pages)) {
    header('Location: ' . $base_url . '/auth/login.php');
    exit();
}

// Redirect customers from admin dashboard
if (isLoggedIn() && $_SESSION['role'] === 'customer') {
    $is_customer_page = strpos($request_uri, '/customer/') !== false;
    $is_auth_page = strpos($request_uri, '/auth/') !== false;
    if (!$is_customer_page && !$is_auth_page && !in_array($current_file, ['index.php', 'index.html'])) {
        header('Location: ' . $base_url . '/customer/index.php');
        exit();
    }
    // Auto-fetch customer_id for customers
    if (!isset($_SESSION['customer_id'])) {
        $stmt = mysqli_prepare($conn, "SELECT customer_id FROM customers WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $_SESSION['email']);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($res)) {
            $_SESSION['customer_id'] = $row['customer_id'];
        } else {
            // Create customer record
            $names = explode(' ', $_SESSION['full_name'], 2);
            $first = $names[0];
            $last = isset($names[1]) ? $names[1] : 'N/A';
            $ins = "INSERT INTO customers (first_name, last_name, email) VALUES (?, ?, ?)";
            $stmt2 = mysqli_prepare($conn, $ins);
            mysqli_stmt_bind_param($stmt2, 'sss', $first, $last, $_SESSION['email']);
            mysqli_stmt_execute($stmt2);
            $_SESSION['customer_id'] = mysqli_insert_id($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRACKTOR - Tractor Booking System</title>
    <link rel="stylesheet" href="/tracktor/css/style.css?v=2">
    <link rel="icon" type="image/png" href="/tracktor/logo.png">
</head>
<body>
<?php if (isLoggedIn()): ?>
<nav class="navbar">
    <div class="nav-container">
        <?php if ($_SESSION['role'] === 'customer'): ?>
            <a href="<?php echo $base_url; ?>/customer/index.php" class="nav-brand">
                <span class="brand-icon">
                    <img src="/tracktor/logo.png" alt="TRACKTOR" class="brand-logo-img">
                </span>
                TRACKTOR
            </a>
            <ul class="nav-links">
                <li><a href="<?php echo $base_url; ?>/customer/index.php" class="<?php echo strpos($request_uri, '/customer/index.php') !== false ? 'active' : ''; ?>"><span class="nav-icon">🏠</span> Home</a></li>
                <li><a href="<?php echo $base_url; ?>/customer/tractors.php" class="<?php echo $current_file == 'tractors.php' ? 'active' : ''; ?>"><span class="nav-icon">🚜</span> Browse</a></li>
                <li><a href="<?php echo $base_url; ?>/customer/bookings.php" class="<?php echo $current_file == 'bookings.php' ? 'active' : ''; ?>"><span class="nav-icon">📅</span> Bookings</a></li>
                <li><a href="<?php echo $base_url; ?>/customer/history.php" class="<?php echo $current_file == 'history.php' ? 'active' : ''; ?>"><span class="nav-icon">📜</span> History</a></li>
            </ul>
        <?php else: ?>
            <a href="<?php echo $base_url; ?>/dashboard/index.php" class="nav-brand">
                <span class="brand-icon">
                    <img src="/tracktor/logo.png" alt="TRACKTOR" class="brand-logo-img">
                </span>
                TRACKTOR
            </a>
            <ul class="nav-links">
                <li><a href="<?php echo $base_url; ?>/dashboard/index.php" class="<?php echo $current_file == 'index.php' && strpos($request_uri, '/dashboard/') !== false ? 'active' : ''; ?>"><span class="nav-icon">📊</span> Dashboard</a></li>
                <li><a href="<?php echo $base_url; ?>/dashboard/tractors.php" class="<?php echo $current_file == 'tractors.php' ? 'active' : ''; ?>"><span class="nav-icon">🚜</span> Tractors</a></li>
                <li><a href="<?php echo $base_url; ?>/dashboard/bookings.php" class="<?php echo $current_file == 'bookings.php' ? 'active' : ''; ?>"><span class="nav-icon">📅</span> Bookings</a></li>
                <li><a href="<?php echo $base_url; ?>/dashboard/customers.php" class="<?php echo $current_file == 'customers.php' ? 'active' : ''; ?>"><span class="nav-icon">👥</span> Customers</a></li>
                <li><a href="<?php echo $base_url; ?>/dashboard/reports.php" class="<?php echo $current_file == 'reports.php' ? 'active' : ''; ?>"><span class="nav-icon">📊</span> Reports</a></li>
            </ul>
        <?php endif; ?>
        
        <div class="nav-user">
            <div class="nav-user-info">
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($_SESSION['role'] ?? 'staff'); ?></div>
            </div>
            <a href="<?php echo $base_url; ?>/auth/logout.php" class="btn-logout">⏻ Logout</a>
        </div>
    </div>
</nav>
<?php endif; ?>
