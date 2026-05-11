<?php
require_once __DIR__ . '/../includes/header.php';

$base_url = getBaseUrl();
$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF verification
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $message = 'CSRF token validation failed. Please refresh the page.';
        $message_type = 'danger';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'create' || $action === 'update') {
            $first_name = sanitizeForDB($conn, $_POST['first_name'] ?? '');
            $last_name = sanitizeForDB($conn, $_POST['last_name'] ?? '');
            $email = sanitizeForDB($conn, $_POST['email'] ?? '');
            $phone = sanitizeForDB($conn, $_POST['phone'] ?? '');
            $address = sanitizeForDB($conn, $_POST['address'] ?? '');
            $city = sanitizeForDB($conn, $_POST['city'] ?? '');
            $state = sanitizeForDB($conn, $_POST['state'] ?? '');
            $zip_code = sanitizeForDB($conn, $_POST['zip_code'] ?? '');

            if (empty($first_name) || empty($last_name) || empty($email) || empty($phone)) {
                $message = 'Please fill in all required fields.';
                $message_type = 'danger';
            } else {
                if ($action === 'create') {
                    $stmt = mysqli_prepare($conn, "INSERT INTO customers (first_name, last_name, email, phone, address, city, state, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, 'ssssssss', $first_name, $last_name, $email, $phone, $address, $city, $state, $zip_code);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = 'Customer added successfully!';
                        $message_type = 'success';
                        logActivity('create', 'customer', mysqli_insert_id($conn), ['name' => $first_name . ' ' . $last_name, 'email' => $email]);
                    } else {
                        $message = 'Error adding customer: ' . mysqli_error($conn);
                        $message_type = 'danger';
                    }
                    mysqli_stmt_close($stmt);
                    unset($stmt);
                } else {
                    $id = intval($_POST['customer_id']);
                    $stmt = mysqli_prepare($conn, "UPDATE customers SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, city = ?, state = ?, zip_code = ? WHERE customer_id = ?");
                    mysqli_stmt_bind_param($stmt, 'ssssssssi', $first_name, $last_name, $email, $phone, $address, $city, $state, $zip_code, $id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = 'Customer updated successfully!';
                        $message_type = 'success';
                        logActivity('update', 'customer', $id, ['name' => $first_name . ' ' . $last_name, 'email' => $email]);
                    } else {
                        $message = 'Error updating customer: ' . mysqli_error($conn);
                        $message_type = 'danger';
                    }
                    mysqli_stmt_close($stmt);
                    unset($stmt);
                }
            }
        } elseif ($action === 'delete') {
            $id = intval($_POST['customer_id']);
            $stmt = mysqli_prepare($conn, "DELETE FROM customers WHERE customer_id = ?");
            mysqli_stmt_bind_param($stmt, 'i', $id);
            if (mysqli_stmt_execute($stmt)) {
                $message = 'Customer deleted successfully!';
                $message_type = 'success';
                logActivity('delete', 'customer', $id, []);
                } else {
                    $message = 'Error deleting customer: ' . mysqli_error($conn);
                    $message_type = 'danger';
                }
                mysqli_stmt_close($stmt);
                unset($stmt);
        }
    }
}

// Get customers with booking insights and payment info (LEFT JOIN)
 $customers = [];
 $search = $_GET['search'] ?? '';
 $city_filter = $_GET['city'] ?? '';

 $customers = [];
 $sql = "SELECT c.*, 
         COUNT(b.booking_id) AS total_bookings,
         COALESCE(SUM(CASE WHEN b.status IN ('active','completed') THEN b.total_amount ELSE 0 END), 0) AS total_spent,
         MAX(b.booking_date) AS last_booking_date,
         COALESCE(pp.total_paid, 0) AS total_paid
         FROM customers c
         LEFT JOIN bookings b ON c.customer_id = b.customer_id
         LEFT JOIN (
             SELECT bk.customer_id, SUM(py.amount) AS total_paid
             FROM payments py INNER JOIN bookings bk ON py.booking_id = bk.booking_id
             WHERE py.payment_status = 'completed' GROUP BY bk.customer_id
         ) pp ON c.customer_id = pp.customer_id";

 $where_added = false;
 if (!empty($search)) {
     $sql .= " WHERE (c.first_name LIKE ? OR c.last_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
     $search_term = "%$search%";
     $params = [$search_term, $search_term, $search_term, $search_term];
     $types = 'ssss';
     $where_added = true;
 }

 if (!empty($city_filter)) {
     $sql .= ($where_added ? " AND" : " WHERE") . " c.city = ?";
     $params[] = $city_filter;
     $types .= 's';
     $where_added = true;
 }

 $sql .= " GROUP BY c.customer_id ORDER BY c.last_name, c.first_name";

 if ($where_added) {
     $stmt = mysqli_prepare($conn, $sql);
     mysqli_stmt_bind_param($stmt, $types, ...$params);
     mysqli_stmt_execute($stmt);
     $customer_result = mysqli_stmt_get_result($stmt);
 } else {
     $customer_result = mysqli_query($conn, $sql);
 }
 while ($row = mysqli_fetch_assoc($customer_result)) {
     $customers[] = $row;
 }
 if (isset($stmt)) {
     mysqli_stmt_close($stmt);
 }

// Check if editing
$edit_customer = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = intval($_GET['id']);
    $edit_res = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id = $edit_id");
    $edit_customer = mysqli_fetch_assoc($edit_res);
}

$show_form = isset($_GET['action']) && in_array($_GET['action'], ['new', 'edit']);
?>

<div class="main-content">
    <?php echo breadcrumb(['Customers' => '']); ?>

    <div class="page-header">
        <h1><span class="icon">👥</span> Customers</h1>
        <div class="d-flex gap-2">
            <?php if ($show_form): ?>
                <a href="<?php echo $base_url; ?>/dashboard/customers.php" class="btn btn-secondary">← Back to List</a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>/dashboard/customers.php?action=new" class="btn btn-primary">+ Add Customer</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($message): ?>
        <?php echo showAlert($message, $message_type); ?>
    <?php endif; ?>

    <?php if ($show_form): ?>
    <!-- Customer Form -->
    <div class="form-container animate-fade-in" style="max-width: 700px;">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
            <?php echo $edit_customer ? '✏️ Edit Customer' : '➕ Add Customer'; ?>
        </h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="<?php echo $edit_customer ? 'update' : 'create'; ?>">
            <?php echo csrfInput(); ?>
            <?php if ($edit_customer): ?>
                <input type="hidden" name="customer_id" value="<?php echo $edit_customer['customer_id']; ?>">
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required
                           value="<?php echo htmlspecialchars($edit_customer['first_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required
                           value="<?php echo htmlspecialchars($edit_customer['last_name'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" required
                           value="<?php echo htmlspecialchars($edit_customer['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone *</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required
                           value="<?php echo htmlspecialchars($edit_customer['phone'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control"
                       value="<?php echo htmlspecialchars($edit_customer['address'] ?? ''); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control"
                           value="<?php echo htmlspecialchars($edit_customer['city'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" class="form-control"
                           value="<?php echo htmlspecialchars($edit_customer['state'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="zip_code">ZIP Code</label>
                <input type="text" id="zip_code" name="zip_code" class="form-control"
                       value="<?php echo htmlspecialchars($edit_customer['zip_code'] ?? ''); ?>">
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <?php echo $edit_customer ? '💾 Update Customer' : '➕ Add Customer'; ?>
                </button>
                <a href="<?php echo $base_url; ?>/dashboard/customers.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

<?php else: ?>
   <?php if (!$show_form): ?>
   <div class="filter-bar" style="margin-bottom: 1.5rem; padding: 1rem; background: var(--dark-card); border-radius: var(--radius-md); border: 1px solid var(--dark-border);">
       <form method="GET" action="" class="filter-form" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
           <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
               <label for="search">Search</label>
               <input type="text" id="search" name="search" class="form-control" 
                      placeholder="Search by name, email, phone..."
                      value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
           </div>
           <div class="form-group" style="margin-bottom: 0; width: 150px;">
               <label for="city">City</label>
               <input type="text" id="city" name="city" class="form-control" 
                      placeholder="City"
                      value="<?php echo htmlspecialchars($_GET['city'] ?? ''); ?>">
           </div>
           <div class="d-flex gap-2" style="margin-bottom: 0;">
               <button type="submit" class="btn btn-primary">🔍 Search</button>
               <?php if (!empty($_GET['search']) || !empty($_GET['city'])): ?>
                   <a href="dashboard/customers.php" class="btn btn-secondary">Clear</a>
               <?php endif; ?>
           </div>
       </form>
   </div>
   <?php endif; ?>
     <!-- Customers Table -->
     <div class="table-container">
        <div class="table-header">
            <h3>👥 Customer Directory (LEFT JOIN: customers + bookings + payments)</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Bookings</th>
                    <th>Total Spent</th>
                    <th>Total Paid</th>
                    <th>Balance</th>
                    <th>Last Booking</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr><td colspan="10" class="text-center text-muted" style="padding: 2rem;">No customers found</td></tr>
                <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                        <?php $balance = $c['total_spent'] - $c['total_paid']; ?>
                        <tr>
                            <td><strong>#<?php echo $c['customer_id']; ?></strong></td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem;"><?php echo htmlspecialchars($c['email']); ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?php echo htmlspecialchars($c['phone']); ?></div>
                            </td>
                            <td>
                                <?php if (!empty($c['city'])): ?>
                                    <div style="font-size: 0.85rem;"><?php echo htmlspecialchars($c['city'] . ', ' . $c['state']); ?></div>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo $c['total_bookings']; ?></strong></td>
                            <td><strong class="text-gold"><?php echo formatCurrency($c['total_spent']); ?></strong></td>
                            <td class="text-success"><?php echo formatCurrency($c['total_paid']); ?></td>
                            <td>
                                <?php if ($balance > 0): ?>
                                    <span class="balance-owed"><strong class="text-danger"><?php echo formatCurrency($balance); ?></strong></span>
                                <?php else: ?>
                                    <span class="balance-paid"><strong class="text-success">✓</strong></span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 0.85rem;"><?php echo formatDate($c['last_booking_date']); ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo $base_url; ?>/dashboard/rental-history.php?customer_id=<?php echo $c['customer_id']; ?>" 
                                       class="btn btn-sm btn-secondary" title="View Rental History">📜</a>
                                    <a href="<?php echo $base_url; ?>/dashboard/customers.php?action=edit&id=<?php echo $c['customer_id']; ?>" 
                                       class="btn btn-sm btn-secondary" title="Edit">✏️</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this customer?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                        <input type="hidden" name="customer_id" value="<?php echo $c['customer_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>