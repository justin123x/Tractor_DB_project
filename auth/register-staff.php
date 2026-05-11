<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    header('Location: ' . getBaseUrl() . '/dashboard/index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeForDB($conn, $_POST['username'] ?? '');
    $email = sanitizeForDB($conn, $_POST['email'] ?? '');
    $full_name = sanitizeForDB($conn, $_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'staff';

    $allowed_roles = ['staff', 'admin'];
    if (!in_array($role, $allowed_roles)) {
        $role = 'staff';
    }

    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $check_sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, 'ss', $username, $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = 'Username or email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (username, email, full_name, password, role) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, 'sssss', $username, $email, $full_name, $hashed_password, $role);

            if (mysqli_stmt_execute($insert_stmt)) {
                $_SESSION['success_message'] = 'Account created successfully! Please sign in.';
                header('Location: ' . getBaseUrl() . '/auth/login-staff.php');
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}

$base_url = getBaseUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration - TRACKTOR</title>
    <link rel="stylesheet" href="/tracktor/css/style.css?v=2">
    <link rel="icon" type="image/png" href="/tracktor/logo.png">
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1f2e 100%);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .auth-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/tracktor/assets/image/2026-05-05/n6gqnaqaafnq/tractor-utility-red.png');
            background-size: cover;
            background-position: center;
            z-index: 0;
        }

        .auth-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(22, 27, 34, 0.85) 0%, rgba(28, 35, 51, 0.8) 100%);
            z-index: 1;
        }

        .auth-card {
            position: relative;
            z-index: 2;
            background: rgba(28, 35, 51, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5),
                        0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .auth-logo-img {
            width: 200px;
            height: auto;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .auth-logo h2 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 3px;
            color: var(--primary);
            margin: 0 0 0.5rem 0;
            text-shadow: 0 2px 10px rgba(212, 46, 18, 0.3);
        }

        .auth-logo p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin: 0;
            font-weight: 500;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(22, 27, 34, 0.8);
            border: 2px solid var(--dark-border);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(212, 46, 18, 0.15),
                        0 4px 12px rgba(0, 0, 0, 0.2);
            background: rgba(22, 27, 34, 1);
        }

        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.6;
        }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 46, 18, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .alert {
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid transparent;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: rgba(248, 81, 73, 0.1);
            border-color: rgba(248, 81, 73, 0.3);
            color: var(--danger);
        }

        .alert-success {
            background: rgba(63, 185, 80, 0.1);
            border-color: rgba(63, 185, 80, 0.3);
            color: var(--success);
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--dark-border);
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .auth-footer .back-link {
            color: var(--text-secondary);
            text-decoration: none;
            margin-right: 0.75rem;
            transition: color 0.2s;
        }

        .auth-footer .back-link:hover {
            color: var(--primary);
        }

        .auth-footer a[href*="login"] {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-footer a[href*="login"]:hover {
            color: var(--primary);
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 2rem 1.25rem;
            }

            .auth-logo h2 {
                font-size: 1.6rem;
            }

            .auth-logo-img {
                width: 160px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-bg"></div>
        <div class="auth-overlay"></div>
        <div class="auth-card">
            <div class="auth-logo">
                <img src="/tracktor/logo.png" alt="TRACKTOR" class="auth-logo-img">
                <h2>TRACKTOR</h2>
                <p>Staff/Admin Registration</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control"
                           placeholder="Enter your full name" required
                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control"
                               placeholder="Choose username" required
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="Enter email" required
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Min 6 chars" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                               placeholder="Repeat password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Account Type</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="staff" <?php echo ($_POST['role'] ?? 'staff') === 'staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="admin" <?php echo ($_POST['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <small style="color: var(--text-muted); font-size: 0.75rem; margin-top: 4px; display: block;">
                        Note: Staff accounts may require admin approval
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    Create Account
                </button>
            </form>

            <div class="auth-footer">
                <a href="<?php echo $base_url; ?>" class="back-link">← Back to Home</a>
                <span style="margin: 0 0.75rem; color: var(--dark-border);">|</span>
                Already have an account? <a href="<?php echo $base_url; ?>/auth/login-staff.php">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
