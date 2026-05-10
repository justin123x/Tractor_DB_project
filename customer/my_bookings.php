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
$customer_id = $_SESSION['user_id'];

// Fetch customer's bookings
$bookings = [];
$sql = "SELECT b.*, t.tractor_name, t.brand, t.image_path FROM bookings b 
        JOIN tractors t ON b.tractor_id = t.tractor_id 
        WHERE b.customer_id = ? 
        ORDER BY b.created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $customer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - TRACKTOR</title>
    <link rel="stylesheet" href="/tracktor/css/style.css">
    <link rel="icon" type="image/png" href="/tracktor/logo.png">
    <style>
        .bookings-section {
            padding: 4rem 2rem;
            background-color: var(--light-bg);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .bookings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .booking-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .booking-header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .booking-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 0.5rem;
        }
        
        .status-pending { background: #ffc107; color: #212529; }
        .status-confirmed { background: #28a745; color: white; }
        .status-active { background: #17a2b8; color: white; }
        .status-completed { background: #6c757d; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        
        .booking-content {
            flex: 1;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .booking-tractor-info {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .booking-tractor-image {
            width: 80px;
            height: 80px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            margin-right: 1.5rem;
        }
        
        .booking-tractor-details {
            flex: 1;
        }
        
        .booking-tractor-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.3rem;
        }
        
        .booking-tractor-brand {
            font-size: 1rem;
            color: var(--text-muted);
        }
        
        .booking-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .booking-detail-label {
            font-weight: 600;
        }
        
        .booking-detail-value {
            font-weight: normal;
        }
        
        .booking-actions {
            display: flex;
            gap: 1rem;
            margin-top: auto;
            padding: 1.5rem;
            border-top: 1px solid var(--light-border);
        }
        
        .btn-action {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-cancel {
            background: #dc3545;
            color: white;
        }
        
        .btn-cancel:hover {
            background: #c82333;
        }
        
        .btn-complete {
            background: #28a745;
            color: white;
        }
        
        .btn-complete:hover {
            background: #218838;
        }
        
        .no-bookings {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }
        
        @media (max-width: 768px) {
            .bookings-section {
                padding: 2rem 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .bookings-grid {
                grid-template-columns: 1fr;
            }
            
            .booking-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/tracktor/" class="nav-brand">
                <img src="/tracktor/logo.png" alt="TRACKTOR" class="brand-logo-img">
                TRACKTOR
            </a>
            <div class="nav-cta">
                <a href="/tracktor/auth/logout.php" class="btn btn-outline btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Bookings Section -->
    <section class="bookings-section">
        <div class="section-header">
            <h1 class="section-title">My Bookings</h1>
            <p class="section-subtitle">View and manage your tractor bookings</p>
        </div>
        
        <?php if (empty($bookings)): ?>
            <div class="no-bookings">
                <h3>No Bookings Found</h3>
                <p>You haven't made any bookings yet. Browse available tractors to get started!</p>
                <a href="/tracktor/customer/tractors.php" class="btn btn-primary btn-lg btn-hover mt-3">
                    <span>View Available Tractors</span>
                    <span>→</span>
                </a>
            </div>
        <?php else: ?>
            <div class="bookings-grid">
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <h3>Booking #<?php echo $booking['booking_id']; ?></h3>
                            <span class="booking-status status-<?php echo strtolower($booking['status']); ?>">
                                <?php echo ucfirst($booking['status']); ?>
                            </span>
                        </div>
                        <div class="booking-content">
                            <div class="booking-tractor-info">
                                <div class="booking-tractor-image" 
                                     style="background-image: url('/tracktor/<?php echo htmlspecialchars($booking['image_path'] ?? ''); ?>');">
                                </div>
                                <div class="booking-tractor-details">
                                    <div class="booking-tractor-name"><?php echo htmlspecialchars($booking['tractor_name']); ?></div>
                                    <div class="booking-tractor-brand"><?php echo htmlspecialchars($booking['brand']); ?></div>
                                </div>
                            </div>
                            
                            <div class="booking-details">
                                <div class="booking-detail">
                                    <span class="booking-detail-label">Booking Date:</span>
                                    <span class="booking-detail-value"><?php echo htmlspecialchars($booking['booking_date']); ?></span>
                                </div>
                                <div class="booking-detail">
                                    <span class="booking-detail-label">Start Date:</span>
                                    <span class="booking-detail-value"><?php echo htmlspecialchars($booking['start_date']); ?></span>
                                </div>
                                <div class="booking-detail">
                                    <span class="booking-detail-label">End Date:</span>
                                    <span class="booking-detail-value"><?php echo htmlspecialchars($booking['end_date']); ?></span>
                                </div>
                                <div class="booking-detail">
                                    <span class="booking-detail-label">Total Amount:</span>
                                    <span class="booking-detail-value">$<?php echo number_format($booking['total_amount'], 2); ?></span>
                                </div>
                                <div class="booking-detail">
                                    <span class="booking-detail-label">Rental Type:</span>
                                    <span class="booking-detail-value"><?php echo ucfirst($booking['rental_type']); ?></span>
                                </div>
                                <div class="booking-detail">
                                    <span class="booking-detail-label">Created:</span>
                                    <span class="booking-detail-value"><?php echo htmlspecialchars($booking['created_at']); ?></span>
                                </div>
                            </div>
                            
                            <div class="booking-actions">
                                <?php if ($booking['status'] === 'pending' || $booking['status'] === 'confirmed'): ?>
                                    <a href="/tracktor/customer/cancel_booking.php?id=<?php echo $booking['booking_id']; ?>" 
                                       class="btn-action btn-cancel">Cancel Booking</a>
                                <?php endif; ?>
                                
                                <?php if ($booking['status'] === 'active'): ?>
                                    <a href="/tracktor/customer/complete_booking.php?id=<?php echo $booking['booking_id']; ?>" 
                                       class="btn-action btn-complete">Mark as Completed</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>