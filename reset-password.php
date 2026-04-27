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
    <link rel="stylesheet" href="css/login.css">
    <title>Reset Password</title>
</head>
<body class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <?php
    // ========== AUTHENTICATION & SECURITY - PASSWORD RESET SYSTEM SECTION START ==========
    session_start();
    date_default_timezone_set('Asia/Dhaka');
    include("connection.php");
    include("includes/auth-helper.php");

    $error = '<div class="alert alert-info text-center" style="display:none;"></div>';
    $token = trim($_GET['token'] ?? '');
    $tokenData = null;
    $showForm = true;

    if ($token !== '') {
        $tokenStmt = $database->prepare("
            SELECT token_id, email
            FROM password_reset_tokens
            WHERE token = ? AND used = 0 AND expires_at > NOW()
            LIMIT 1
        ");
        $tokenStmt->bind_param("s", $token);
        $tokenStmt->execute();
        $tokenResult = $tokenStmt->get_result();

        if ($tokenResult->num_rows === 1) {
            $tokenData = $tokenResult->fetch_assoc();
        } else {
            $error = '<div class="alert alert-danger text-center">Invalid or expired reset link.</div>';
            $showForm = false;
        }
    } else {
        $error = '<div class="alert alert-danger text-center">Invalid reset link.</div>';
        $showForm = false;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $tokenData) {
        // ========== DATABASE RELATIONS USED: Password_Reset_Tokens, WebUser, Patient, Doctor, Admin ==========
        // Password_Reset_Tokens: Verify token validity and mark as used
        // WebUser: Get user type to determine which table to update
        // Patient/Doctor/Admin: Update password based on user type
        // ========== PASSWORD RESET PROCESSING START ==========
        $newpassword = $_POST['newpassword'] ?? '';
        $cpassword = $_POST['cpassword'] ?? '';

        if (empty($newpassword) || empty($cpassword)) {
            $error = '<div class="alert alert-danger text-center">Please fill in both password fields.</div>';
        } elseif ($newpassword !== $cpassword) {
            $error = '<div class="alert alert-danger text-center">Passwords do not match!</div>';
        } else {
            $userStmt = $database->prepare("SELECT usertype FROM webuser WHERE email = ? LIMIT 1");
            $userStmt->bind_param("s", $tokenData['email']);
            $userStmt->execute();
            $userResult = $userStmt->get_result();

            if ($userResult->num_rows === 1) {
                $usertype = $userResult->fetch_assoc()['usertype'];
                $hashed = hashPassword($newpassword);
                $tableMap = [
                    'p' => ['table' => 'patient', 'email_field' => 'pemail', 'password_field' => 'ppassword'],
                    'd' => ['table' => 'doctor', 'email_field' => 'docemail', 'password_field' => 'docpassword'],
                    'a' => ['table' => 'admin', 'email_field' => 'aemail', 'password_field' => 'apassword'],
                ];

                if (isset($tableMap[$usertype])) {
                    $tableConfig = $tableMap[$usertype];
                    $updatePasswordStmt = $database->prepare("
                        UPDATE {$tableConfig['table']}
                        SET {$tableConfig['password_field']} = ?
                        WHERE {$tableConfig['email_field']} = ?
                    ");
                    $updatePasswordStmt->bind_param("ss", $hashed, $tokenData['email']);
                    $passwordUpdated = $updatePasswordStmt->execute();

                    if ($passwordUpdated && $updatePasswordStmt->affected_rows === 1) {
                        $markUsedStmt = $database->prepare("
                            UPDATE password_reset_tokens
                            SET used = 1
                            WHERE email = ?
                        ");
                        $markUsedStmt->bind_param("s", $tokenData['email']);

                        if ($markUsedStmt->execute()) {
                            $error = '<div class="alert alert-success text-center">Password reset successful! <a href="login.php" class="text-decoration-none">Login now</a></div>';
                            $showForm = false;
                        } else {
                            $error = '<div class="alert alert-danger text-center">Password was updated, but the reset token could not be finalized. Please contact support.</div>';
                        }
                    } else {
                        $error = '<div class="alert alert-danger text-center">We could not update your password. Please try again.</div>';
                    }
                } else {
                    $error = '<div class="alert alert-danger text-center">Unknown user type for this account.</div>';
                }
            } else {
                $error = '<div class="alert alert-danger text-center">No account was found for this reset request.</div>';
            }
        }
        // ========== PASSWORD RESET PROCESSING END ==========
    }
    ?>

    <!-- ========== PASSWORD RESET FORM SECTION START ========== -->
    <div class="w-100" style="max-width: 500px; padding: 20px;">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4">Reset Password</h2>
                <p class="text-center text-muted mb-4">Enter your new password</p>
                <?php echo $error; ?>
                <?php if ($showForm) { ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="newpassword" class="form-label">New Password:</label>
                        <input type="password" name="newpassword" class="form-control" placeholder="New Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="cpassword" class="form-label">Confirm Password:</label>
                        <input type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Reset Password</button>
                </form>
                <?php } ?>
                <div class="text-center">
                    <p class="mb-0"><small><a href="login.php" class="text-decoration-none">Back to Login</a></small></p>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== PASSWORD RESET FORM SECTION END ========== -->
    <!-- ========== AUTHENTICATION & SECURITY - PASSWORD RESET SYSTEM SECTION END ========== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
