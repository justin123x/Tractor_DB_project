<?php
$base_url = getBaseUrl();
?>
<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-brand">
            <h3>🚜 TRACKTOR</h3>
            <p>Professional tractor booking and fleet management system. Powering farms and agricultural businesses with efficient equipment rental solutions.</p>
        </div>
        <?php if (isset($_SESSION['user_id']) && isLoggedIn()): ?>
        <div class="footer-section">
            <h4>Navigation</h4>
            <a href="<?php echo $base_url; ?>/dashboard/index.php">Dashboard</a>
            <a href="<?php echo $base_url; ?>/dashboard/tractors.php">Tractors</a>
            <a href="<?php echo $base_url; ?>/dashboard/bookings.php">Bookings</a>
            <a href="<?php echo $base_url; ?>/dashboard/customers.php">Customers</a>
        </div>
        <div class="footer-section">
            <h4>Resources</h4>
            <a href="<?php echo $base_url; ?>/dashboard/reports.php">Reports & Analytics</a>
            <a href="#">Documentation</a>
            <a href="#">API Reference</a>
        </div>
        <div class="footer-section">
            <h4>Support</h4>
            <a href="#">Help Center</a>
            <a href="#">Contact Us</a>
            <a href="#">Privacy Policy</a>
        </div>
        <?php else: ?>
        <div class="footer-section">
            <h4>Get Started</h4>
            <a href="<?php echo $base_url; ?>/auth/login.php">Customer Sign In</a>
            <a href="<?php echo $base_url; ?>/auth/register.php">Customer Registration</a>
            <a href="<?php echo $base_url; ?>/auth/login-staff.php">Staff/Admin Login</a>
            <a href="<?php echo $base_url; ?>/auth/register-staff.php">Staff Registration</a>
        </div>
        <div class="footer-section">
            <h4>Features</h4>
            <a href="#">Fleet Management</a>
            <a href="#">Booking System</a>
            <a href="#">Analytics</a>
        </div>
        <div class="footer-section">
            <h4>Support</h4>
            <a href="#">Help Center</a>
            <a href="#">Contact Us</a>
            <a href="#">Privacy Policy</a>
        </div>
        <?php endif; ?>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?php echo date('Y'); ?> TRACKTOR. All rights reserved.</span>
        <span>Powered by TRACKTOR Fleet Management</span>
    </div>
</footer>
</body>
</html>