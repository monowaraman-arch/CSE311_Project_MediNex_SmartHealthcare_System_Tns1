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
    <title>Login</title>
</head>
<body class="d-flex flex-column" style="min-height: 100vh; padding-top: 70px;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container ">
            <a class="navbar-brand" href="index.html">
                <i class="bi bi-heart-pulse-fill"></i> MediNex
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link px-4 ms-2 " href="signup.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Navbar end -->
    </nav>
    <?php

    //learn from w3schools.com
    //Unset all the server side variables

    session_start();

    $_SESSION["user"]="";
    $_SESSION["usertype"]="";
    
    // Set the new timezone
    date_default_timezone_set('Asia/Dhaka');
    $date = date('Y-m-d');

    $_SESSION["date"]=$date;
    

    //import database
    include("connection.php");
    include("includes/auth-helper.php");

    



    if($_POST){
        //  DATABASE RELATIONS USED: WebUser, Patient, Doctor, Admin 
        // WebUser: Check user type (p=patient, d=doctor, a=admin)
        // Patient: Verify patient credentials and update password hash
        // Doctor: Verify doctor credentials and update password hash  
        // Admin: Verify admin credentials and update password hash

        $email=$_POST['useremail'];
        $password=$_POST['userpassword'];
        
        $error='<label for="errors" class="form-label"></label>';
             /// check email from webuser table
        $result= $database->query("select * from webuser where email='$email'");
        if($result->num_rows==1){
            $utype=$result->fetch_assoc()['usertype'];
            if ($utype=='p'){
                
                $checker = $database->query("select * from patient where pemail='$email'");
                if ($checker->num_rows==1){
                    $userdata = $checker->fetch_assoc();
                    $stored_password = $userdata['ppassword'];
                    
                    // Check if password is hashed or plain text 
                    $password_valid = false;
                    if (password_verify($password, $stored_password)) {
                        // Password is hashed and matches
                        $password_valid = true;
                    } elseif ($stored_password == $password) {
                        // Plain text password old system - update to hash
                        $password_valid = true;
                        $hashed = hashPassword($password);
                        $database->query("update patient set ppassword='$hashed' where pemail='$email'");
                    }
                    
                    if ($password_valid){
                        //   Patient dashbord
                        $_SESSION['user']=$email;
                        $_SESSION['usertype']='p';
                        
                        header('location: patient/index.php');
                    } else {
                        $error='<label for="errors" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }

                }else{
                    $error='<label for="errors" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }
             // check admin from admin table
            }elseif($utype=='a'){
            
                $checker = $database->query("select * from admin where aemail='$email'");
                if ($checker->num_rows==1){
                    $userdata = $checker->fetch_assoc();
                    $stored_password = $userdata['apassword'];
                    
                    // Check if password is hashed or plain text 
                    $password_valid = false;
                    if (password_verify($password, $stored_password)) {
                        $password_valid = true;
                    } elseif ($stored_password == $password) {   //plain text password old system - update to hash
                        $password_valid = true;
                        $hashed = hashPassword($password);
                        $database->query("update admin set apassword='$hashed' where aemail='$email'");
                    }
                    
                    if ($password_valid){
                        //   Admin dashbord
                        $_SESSION['user']=$email;
                        $_SESSION['usertype']='a';
                        
                        header('location: admin/index.php');
                    } else {
                        $error='<label for="errors" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }

                }else{
                    $error='<label for="errors" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }

            // check doctor from doctor table
            }elseif($utype=='d'){
                
                $checker = $database->query("select * from doctor where docemail='$email'");
                if ($checker->num_rows==1){
                    $userdata = $checker->fetch_assoc();
                    $stored_password = $userdata['docpassword'];
                    
                    // Check if password is hashed or plain text
                    $password_valid = false;
                    if (password_verify($password, $stored_password)) {
                        $password_valid = true;
                    } elseif ($stored_password == $password) {   //plain text password old system - update to hash
                        $password_valid = true;
                        $hashed = hashPassword($password);
                        $database->query("update doctor set docpassword='$hashed' where docemail='$email'");
                    }
                    
                    if ($password_valid){
                        //   doctor dashbord
                        $_SESSION['user']=$email;
                        $_SESSION['usertype']='d';
                        header('location: doctor/index.php');
                    } else {
                        $error='<label for="errors" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }

                }else{
                    $error='<label for="errors" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }

            }
            
        }else{
            $error='<label for="errors" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We cant found any acount for this email.</label>';
        }
   
    }else{
        $error='<label for="errors" class="form-label">&nbsp;</label>';
    }

    ?>

    <!--  LOGIN FORM SECTION START = -->
    <div class="d-flex justify-content-center align-items-center flex-grow-1 w-100 px-3">
        <div class="w-100" style="max-width: 500px;">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">Welcome Back!</h2>
                    <p class="text-center text-muted mb-4">Login with your details to continue</p>
                    <form action="" method="POST" class="needs-validation" novalidate>
                        <?php echo $error; ?>
                        <div class="mb-3">
                            <label for="useremail" class="form-label">Email:</label>
                            <input type="email" name="useremail" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="mb-3">
                            <label for="userpassword" class="form-label">Password:</label>
                            <input type="password" name="userpassword" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                    </form>
                    <div class="text-center">
                        <p class="mb-2"><small>Don't have an account? <a href="signup.php" class="text-decoration-none">Sign Up</a></small></p>
                        <p class="mb-0"><small>Forgot password? <a href="forgot-password.php" class="text-decoration-none">Reset Password</a></small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  LOGIN FORM SECTION END -->
    <!--  AUTHENTICATION & SECURITY - LOGIN SYSTEM SECTION END  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
