<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Forgot Password</title>
</head>
<body class="d-flex flex-column" style="min-height: 100vh; padding-top: 70px;">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <i class="bi bi-heart-pulse-fill"></i> MediNex
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light rounded-pill px-4 ms-2" href="signup.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<?php
// ========== AUTHENTICATION & SECURITY - PASSWORD RESET SYSTEM SECTION START ==========
session_start();
date_default_timezone_set('Asia/Dhaka');
include("connection.php");
include("includes/auth-helper.php");

$error = '<div class="alert alert-info text-center" style="display:none;"></div>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ========== DATABASE RELATIONS USED: WebUser, Password_Reset_Tokens ==========
    // WebUser: Verify email exists in system
    // Password_Reset_Tokens: Generate and store reset token with expiration

    $email = trim($_POST['useremail']);

    if (empty($email)) {
        $error = '<div class="alert alert-danger text-center">Please enter your email address.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '<div class="alert alert-danger text-center">Please enter a valid email address.</div>';
    } else {
        // Check if email exists
        $stmt = $database->prepare("SELECT * FROM webuser WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Insert or update token
            $stmt2 = $database->prepare("
                INSERT INTO password_reset_tokens (email, token, expires_at, used)
                VALUES (?, ?, ?, 0)
                ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at), used = 0
            ");
            $stmt2->bind_param("sss", $email, $token, $expires);
            $stmt2->execute();

            // Localhost testing reset link
            $reset_link = "http://localhost/Healthcare/reset-password.php?token=" . urlencode($token);

            $error = '<div class="alert alert-success text-center">
                        Password reset link generated successfully.<br>
                        <strong>Click this link to reset password:</strong><br>
                        <a href="' . $reset_link . '" target="_blank">' . $reset_link . '</a><br><br>
                        <small class="text-danger">For localhost testing only. In production, this link should be sent by email.</small>
                      </div>';
        } else {
            $error = '<div class="alert alert-danger text-center">Email not found in our system.</div>';
        }
    }
}
// ========== AUTHENTICATION & SECURITY - PASSWORD RESET SYSTEM SECTION END ==========
?>

    <!-- ========== PASSWORD RESET FORM SECTION START ========== -->
    <div class="d-flex justify-content-center align-items-center flex-grow-1 w-100 px-3">
        <div class="w-100" style="max-width: 500px;">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">Forgot Password?</h2>
                    <p class="text-center text-muted mb-4">Enter your email to reset password</p>

                    <form action="" method="POST" class="needs-validation" novalidate>
                        <?php echo $error; ?>

                        <div class="mb-3">
                            <label for="useremail" class="form-label">Email:</label>
                            <input type="email" name="useremail" class="form-control" placeholder="Email Address" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Send Reset Link</button>
                    </form>

                    <div class="text-center">
                        <p class="mb-0">
                            <small>Remember your password? <a href="login.php" class="text-decoration-none">Login</a></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== PASSWORD RESET FORM SECTION END ========== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>