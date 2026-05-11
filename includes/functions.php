<?php
/**
 * TRACKTOR - Helper Functions
 */

// ---------- CSRF Protection ----------

function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        die('CSRF token validation failed. Please refresh the page and try again.');
    }
}

function csrfInput() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">';
}

// ---------- Session Helpers ----------

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . getBaseUrl() . '/auth/login.php');
        exit();
    }
}

function requireAdmin() {
    if (!isLoggedIn() || (isset($_SESSION['role']) ? $_SESSION['role'] : '') !== 'admin') {
        header('Location: ' . getBaseUrl() . '/dashboard/index.php');
        exit();
    }
}

// ---------- URL Helpers ----------

function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
    // Go up from includes/ or dashboard/ or auth/ or customer/ to the project root
    $base = dirname($script);
    // If the script is in a subdirectory, go up one more to get the project root
    $lastDir = basename($base);
    if (in_array($lastDir, ['includes', 'dashboard', 'auth', 'customer'], true)) {
        $base = dirname($base);
    }
    return $protocol . '://' . $host . $base;
}

// ---------- Sanitization ----------

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function sanitizeForDB($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

// ---------- Formatting ----------

function formatCurrency($amount) {
    return '₱' . number_format((float)$amount, 2);
}

function formatDate($date) {
    if (empty($date) || $date == '0000-00-00') return 'N/A';
    return date('M d, Y', strtotime($date));
}

function formatDateTime($datetime) {
    if (empty($datetime)) return 'N/A';
    return date('M d, Y h:i A', strtotime($datetime));
}

// ---------- Status Badges ----------

function statusBadge($status) {
    $status = strtolower($status);
    $icons = [
        'available' => '✓',
        'booked' => '📅',
        'maintenance' => '🔧',
        'retired' => '⛔',
        'pending' => '⏳',
        'confirmed' => '✓',
        'active' => '▶',
        'completed' => '✅',
        'cancelled' => '✕',
    ];
    $icon = isset($icons[$status]) ? $icons[$status] : '●';
    return '<span class="badge badge-' . $status . '">' . $icon . ' ' . ucfirst($status) . '</span>';
}

// ---------- Alert Messages ----------

function showAlert($message, $type = 'info', $autoDismiss = false) {
    $icons = [
        'success' => '✅',
        'danger' => '❌',
        'warning' => '⚠️',
        'info' => 'ℹ️',
    ];
    $icon = isset($icons[$type]) ? $icons[$type] : 'ℹ️';
    $autoDismissAttr = $autoDismiss ? ' data-auto-dismiss="3000"' : '';
    return '<div class="alert alert-' . $type . '"' . $autoDismissAttr . '>' . $icon . ' ' . sanitize($message) . '</div>';
}

// ---------- Breadcrumb ----------

function breadcrumb($items) {
    $html = '<nav class="breadcrumb">';
    $html .= '<a href="' . getBaseUrl() . '/dashboard/index.php">🏠 Home</a>';
    foreach ($items as $label => $url) {
        $html .= '<span class="separator">›</span>';
        if ($url) {
            $html .= '<a href="' . $url . '">' . sanitize($label) . '</a>';
        } else {
            $html .= '<span class="current">' . sanitize($label) . '</span>';
        }
    }
    $html .= '</nav>';
    return $html;
}

// ---------- Image Upload ----------

function uploadTractorImage($file, $existing_path = '') {
    // Validate file
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded or upload error.'];
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Allowed: JPG, PNG, GIF, WebP.'];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File too large. Maximum size: 5MB.'];
    }

    // Ensure upload directory exists and is writable
    $upload_dir = __DIR__ . '/../uploads/tractors/';
    if (!ensureUploadDir()) {
        return ['success' => false, 'message' => 'Upload directory is not writable. Please check permissions.'];
    }

    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'tractor_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target_path = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Delete old image if exists
        if (!empty($existing_path)) {
            $old_file = __DIR__ . '/../' . $existing_path;
            if (file_exists($old_file) && strpos($existing_path, 'uploads/') === 0) {
                unlink($old_file);
            }
        }
        return ['success' => true, 'path' => 'uploads/tractors/' . $filename];
    }

    return ['success' => false, 'message' => 'Failed to move uploaded file.'];
}

function getTractorImageUrl($image_path, $image_url = '') {
    $base_url = getBaseUrl();
    // Priority: uploaded image > external URL > placeholder
    if (!empty($image_path)) {
        // Check if file exists on disk
        $full_path = __DIR__ . '/../' . $image_path;
        if (file_exists($full_path)) {
            return $base_url . '/' . $image_path;
        }
        // If file doesn't exist, don't return broken URL - fall through to next option
    }
    if (!empty($image_url)) {
        return $image_url;
    }
    return null; // Will show placeholder icon
}

/**
 * Ensure the upload directory exists and is writable.
 * Call this before any image upload operation.
 */
function ensureUploadDir() {
    $upload_dir = __DIR__ . '/../uploads/tractors/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    // Create .htaccess to allow image display but prevent script execution
    $htaccess = $upload_dir . '.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, "Options -ExecCGI\nAddHandler cgi-script .php .pl .py .jsp .asp .sh .cgi\nDeny from all\n<FilesMatch \"\\.(jpg|jpeg|png|gif|webp)$\">\n  Allow from all\n</FilesMatch>\n");
    }
    return is_writable($upload_dir);
}

// ---------- Error Logging ----------

/**
 * Log an error message to file with timestamp
 * 
 * @param string $message The error message
 * @param string $level Error level (ERROR, WARNING, INFO, DEBUG)
 * @param mixed $context Additional context (array or string)
 */
function logError($message, $level = 'ERROR', $context = null) {
    $log_dir = __DIR__ . '/../logs/';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_file = $log_dir . 'error.log';
    $timestamp = date('Y-m-d H:i:s');
    $user_info = '';
    
     if (isset($_SESSION['user_id'])) {
         $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'N/A';
         $user_info = " [User: {$_SESSION['user_id']}, Role: {$role}]";
     }
    
    $context_str = '';
    if ($context !== null) {
        if (is_array($context)) {
            $context_str = ' | Context: ' . json_encode($context);
        } else {
            $context_str = ' | Context: ' . $context;
        }
    }
    
    $log_entry = "[$timestamp] [$level]$user_info $message$context_str" . PHP_EOL;
    
    // Write to log file (append)
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    
    // Also log to PHP error log for system integration
    error_log("[$level] Tracktor: $message$user_info$context_str");
}

/**
 * Log an activity/audit event
 * 
 * @param string $action The action performed
 * @param string $entity_type Type of entity (tractor, booking, customer, payment, user)
 * @param int $entity_id Entity ID
 * @param array $details Additional details
 */
function logActivity($action, $entity_type, $entity_id = 0, $details = []) {
    global $conn;
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    
    $details_json = json_encode($details);
    
    $stmt = mysqli_prepare($conn, "INSERT INTO activity_log (user_id, action, entity_type, entity_id, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ississs', $user_id, $action, $entity_type, $entity_id, $details_json, $ip_address, $user_agent);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// ---------- Database Query Helpers ----------

function getDashboardStats($conn) {
    $stats = [];

    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tractors");
    $stats['total_tractors'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tractors WHERE status = 'available'");
    $stats['available_tractors'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings");
    $stats['total_bookings'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status IN ('active', 'confirmed', 'pending')");
    $stats['active_bookings'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) as total FROM bookings WHERE status IN ('active','completed')");
    $stats['total_revenue'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) as total FROM bookings WHERE status IN ('active','completed','pending','confirmed')");
    $stats['total_booking_value'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM customers");
    $stats['total_customers'] = mysqli_fetch_assoc($result)['total'];

    return $stats;
}

function getRecentBookings($conn, $limit = 5) {
    $sql = "SELECT b.*, t.tractor_name, t.brand, 
            CONCAT(c.first_name, ' ', c.last_name) AS customer_name
            FROM bookings b
            INNER JOIN tractors t ON b.tractor_id = t.tractor_id
            INNER JOIN customers c ON b.customer_id = c.customer_id
            ORDER BY b.created_at DESC LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $bookings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
    return $bookings;
}

// ---------- Booking Time Remaining ----------

function getRemainingTime($end_date, $status = 'active') {
    if ($status === 'completed' || $status === 'cancelled') {
        return '<span class="text-muted">—</span>';
    }
    
    $end_timestamp = strtotime($end_date);
    $now = time();
    $diff = $end_timestamp - $now;
    
    if ($diff <= 0) {
        return '<span class="text-danger">⏰ Overdue</span>';
    }
    
    $days = floor($diff / 86400);
    $hours = floor(($diff % 86400) / 3600);
    $minutes = floor(($diff % 3600) / 60);
    
    if ($days > 0) {
        return '<span class="text-info">' . $days . 'd ' . $hours . 'h left</span>';
    } elseif ($hours > 0) {
        return '<span class="text-warning">' . $hours . 'h ' . $minutes . 'm left</span>';
    } else {
        return '<span class="text-danger">' . $minutes . ' minutes left</span>';
    }
}

function getDueDateCountdown($end_date, $status = 'active') {
    if ($status === 'completed' || $status === 'cancelled') {
        return '<span class="text-muted">N/A</span>';
    }
    
    $end_timestamp = strtotime($end_date);
    $now = time();
    $diff = $end_timestamp - $now;
    
    if ($diff <= 0) {
        return '<span class="text-danger">Overdue by ' . floor(abs($diff) / 86400) . ' days</span>';
    }
    
    $days = floor($diff / 86400);
    return '<span class="text-success">' . $days . ' days remaining</span>';
}
?>