<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if user is logged in and is a customer
if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    header('Location: ' . getBaseUrl() . '/auth/login.php');
    exit();
}

$base_url = getBaseUrl();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tractor_id = (int)$_POST['tractor_id'] ?? 0;
    $customer_id = $_SESSION['user_id'] ?? 0;
    
    if ($tractor_id > 0 && $customer_id > 0) {
        // Check if tractor is available
        $sql = "SELECT t.tractor_name, t.status FROM tractors t WHERE t.tractor_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $tractor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($tractor = mysqli_fetch_assoc($result)) {
            if ($tractor['status'] === 'available') {
                // Create booking for today with 1 day rental
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime('+1 day'));
                $total_amount = 0; // Would need to get daily rate from tractor
                
                // Get daily rate for calculation
                $rate_sql = "SELECT daily_rate FROM tractors WHERE tractor_id = ?";
                $rate_stmt = mysqli_prepare($conn, $rate_sql);
                mysqli_stmt_bind_param($rate_stmt, 'i', $tractor_id);
                mysqli_stmt_execute($rate_stmt);
                $rate_result = mysqli_stmt_get_result($rate_stmt);
                
                if ($rate_row = mysqli_fetch_assoc($rate_result)) {
                    $total_amount = $rate_row['daily_rate'];
                }
                
                // Insert booking
                $insert_sql = "INSERT INTO bookings (tractor_id, customer_id, booking_date, start_date, end_date, total_amount, status) 
                              VALUES (?, ?, ?, ?, ?, ?, 'pending')";
                $insert_stmt = mysqli_prepare($conn, $insert_sql);
                mysqli_stmt_bind_param($insert_stmt, 'iisssd', 
                    $tractor_id, $customer_id, date('Y-m-d'), $start_date, $end_date, $total_amount);
                
                if (mysqli_stmt_execute($insert_stmt)) {
                    // Update tractor status to booked
                    $update_sql = "UPDATE tractors SET status = 'booked' WHERE tractor_id = ?";
                    $update_stmt = mysqli_prepare($conn, $update_sql);
                    mysqli_stmt_bind_param($update_stmt, 'i', $tractor_id);
                    mysqli_stmt_execute($update_stmt);
                    
                    $_SESSION['success_message'] = "Successfully booked " . htmlspecialchars($tractor['tractor_name']) . "! Your booking is pending confirmation.";
                    header('Location: ' . $base_url . '/customer/my_bookings.php');
                    exit();
                } else {
                    $_SESSION['error_message'] = "Failed to create booking. Please try again.";
                }
                
                mysqli_stmt_close($insert_stmt);
                mysqli_stmt_close($update_stmt);
            } else {
                $_SESSION['error_message'] = "Sorry, this tractor is no longer available.";
            }
            
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($rate_stmt);
        } else {
            $_SESSION['error_message'] = "Tractor not found.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid request.";
    }
    
    header('Location: ' . $base_url . '/customer/tractors.php');
    exit();
} else {
    header('Location: ' . $base_url . '/customer/tractors.php');
    exit();
}