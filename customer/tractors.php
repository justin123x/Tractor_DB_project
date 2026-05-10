<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

echo "<h1>Available Tractors for Customer</h1>";

// Check if user is logged in (for testing, we'll simulate a customer)
if (!isset($_SESSION['user_id'])) {
    // Simulate login for testing - in real scenario, user would need to login
    $_SESSION['user_id'] = 10;  // Using existing customer ID from db.sql
    $_SESSION['role'] = 'customer';
    echo "<p><em>Simulated login as customer (ID: 10)</em></p>";
}

// Check if user is logged in and is a customer
if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    header('Location: ' . getBaseUrl() . '/auth/login.php');
    exit();
}

$base_url = getBaseUrl();

// Fetch available tractors
$tractors = [];
$sql = "SELECT t.*, c.category_name FROM tractors t LEFT JOIN categories c ON t.category_id = c.category_id WHERE t.status = 'available'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tractors[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Tractors - TRACKTOR</title>
    <link rel="stylesheet" href="/tracktor/css/style.css">
    <link rel="icon" type="image/png" href="/tracktor/logo.png">
    <style>
        .tractors-section {
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
        
        .tractors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .tractor-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .tractor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .tractor-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .tractor-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .tractor-content {
            flex: 1;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .tractor-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .tractor-brand {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }
        
        .tractor-specs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .tractor-spec {
            display: flex;
            align-items: center;
        }
        
        .tractor-spec-icon {
            margin-right: 0.5rem;
        }
        
        .tractor-description {
            flex: 1;
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .tractor-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--light-border);
        }
        
        .tractor-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .btn-book {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .btn-book:hover {
            background: var(--primary-dark);
        }
        
        .no-tractors {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }
        
        @media (max-width: 768px) {
            .tractors-section {
                padding: 2rem 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .tractors-grid {
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

    <!-- Tractors Section -->
    <section class="tractors-section">
        <div class="section-header">
            <h1 class="section-title">Available Tractors</h1>
            <p class="section-subtitle">Browse our fleet of available tractors for your farming needs</p>
        </div>
        
        <?php if (empty($tractors)): ?>
            <div class="no-tractors">
                <h3>No Tractors Available</h3>
                <p>Currently there are no tractors available for booking. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="tractors-grid">
                <?php foreach ($tractors as $tractor): ?>
                    <div class="tractor-card">
                        <div class="tractor-image" style="background-image: url('/tracktor/<?php echo htmlspecialchars($tractor['image_path'] ?? ''); ?>');">
                            <span class="tractor-badge"><?php echo htmlspecialchars($tractor['status']); ?></span>
                        </div>
                        <div class="tractor-content">
                            <h2 class="tractor-name"><?php echo htmlspecialchars($tractor['tractor_name']); ?></h2>
                            <p class="tractor-brand"><?php echo htmlspecialchars($tractor['brand']); ?></p>
                            <div class="tractor-specs">
                                <div class="tractor-spec">
                                    <span class="tractor-spec-icon">⛽</span>
                                    <span><?php echo htmlspecialchars($tractor['horsepower']); ?> HP</span>
                                </div>
                                <div class="tractor-spec">
                                    <span class="tractor-spec-icon">📅</span>
                                    <span><?php echo htmlspecialchars($tractor['year_manufactured']); ?></span>
                                </div>
                            </div>
                            <p class="tractor-description"><?php echo htmlspecialchars($tractor['description'] ?? 'No description available.'); ?></p>
                            <div class="tractor-footer">
                                <div class="tractor-price">$<?php echo number_format($tractor['daily_rate'], 2); ?>/day</div>
                                <form method="POST" action="/tracktor/customer/book_tractor.php">
                                    <input type="hidden" name="tractor_id" value="<?php echo $tractor['tractor_id']; ?>">
                                    <button type="submit" class="btn-book">Book Now</button>
                                </form>
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