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

    $error='<div class="alert alert-info text-center" style="display:none;"></div>';
    $token = $_GET['token'] ?? '';

    if($_POST && $token){
        // ========== DATABASE RELATIONS USED: Password_Reset_Tokens, WebUser, Patient, Doctor, Admin ==========
        // Password_Reset_Tokens: Verify token validity and mark as used
        // WebUser: Get user type to determine which table to update
        // Patient/Doctor/Admin: Update password based on user type
        // ========== PASSWORD RESET PROCESSING START ==========
        $newpassword=$_POST['newpassword'];
        $cpassword=$_POST['cpassword'];
        if($newpassword == $cpassword){
            $result = $database->query("SELECT * FROM password_reset_tokens WHERE token='$token' AND used=0 AND expires_at > NOW()");
            if($result->num_rows==1){
                $token_data = $result->fetch_assoc();
                $email = $token_data['email'];
                $user_result = $database->query("SELECT * FROM webuser WHERE email='$email'");
                if($user_result->num_rows==1){
                    $usertype = $user_result->fetch_assoc()['usertype'];
                    $hashed = hashPassword($newpassword);
                    $table = ($usertype == 'p') ? 'patient' : (($usertype == 'd') ? 'doctor' : 'admin');
                    $email_field = ($usertype == 'p') ? 'pemail' : (($usertype == 'd') ? 'docemail' : 'aemail');
                    $password_field = ($usertype == 'p') ? 'ppassword' : (($usertype == 'd') ? 'docpassword' : 'apassword');
                    $database->query("UPDATE $table SET $password_field='$hashed' WHERE $email_field='$email'");
                    $database->query("UPDATE password_reset_tokens SET used=1 WHERE token='$token'");
                    $error='<div class="alert alert-success text-center">Password reset successful! <a href="login.php" class="text-decoration-none">Login now</a></div>';
                }
            } else {
                $error='<div class="alert alert-danger text-center">Invalid or expired reset token.</div>';
            }
        } else {
            $error='<div class="alert alert-danger text-center">Passwords do not match!</div>';
        }
        // ========== PASSWORD RESET PROCESSING END ==========
    }
    if(!$token){
        $error='<div class="alert alert-danger text-center">Invalid reset link.</div>';
    }
    ?>

    <!-- ========== PASSWORD RESET FORM SECTION START ========== -->
    <div class="w-100" style="max-width: 500px; padding: 20px;">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4">Reset Password</h2>
                <p class="text-center text-muted mb-4">Enter your new password</p>
                <form action="" method="POST">
                    <?php echo $error; ?>
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

