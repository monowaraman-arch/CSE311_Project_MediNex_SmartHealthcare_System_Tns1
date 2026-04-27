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
    <link rel="stylesheet" href="css/signup.css">
    <title>Create Account</title>
    <style>
        .card{animation: transitionIn-X 0.5s;}
        
        .form-text {font-size: 0.875rem; color: #6c757d; margin-top: 0.25rem;}
    </style>
</head>
<body class="d-flex flex-column" style="min-height: 100vh; padding-top: 70px;">
<nav class="navbar navbar-expand-lg navbar-light fixed-top ">
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
                        <a class="nav-link  px-4 ms-2" href="signup.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
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
    //  DATABASE RELATIONS USED: WebUser, Patient 
    // WebUser: Check if email already exists, then insert new user record
    // Patient: Insert new patient registration data (pemail, pname, ppassword, paddress, pnic, pdob, ptel)

    $result= $database->query("select * from webuser");

    $fname=$_SESSION['personal']['fname'];
    $lname=$_SESSION['personal']['lname'];
    $name=$fname." ".$lname;
    $address=$_SESSION['personal']['address'];
    $nic=$_SESSION['personal']['nic'];
    $dob=$_SESSION['personal']['dob'];
    $email=$_POST['newemail'];
    $tele=$_POST['tele'];
    $newpassword=$_POST['newpassword'];
    $cpassword=$_POST['cpassword'];
    
    if ($newpassword==$cpassword){
        $sqlmain= "select * from webuser where email=?;";  ///email extising check database
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("s",$email); 
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows==1){
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        }else{
            // Insert patient data
            // Age is calculated dynamically from date of birth (pdob) when displaying in account details
            $hashed_password = hashPassword($newpassword);
            $database->query("insert into patient(pemail,pname,ppassword, paddress, pnic,pdob,ptel) 
                              values('$email','$name','$hashed_password','$address','$nic','$dob','$tele');");
            $database->query("insert into webuser values('$email','p')");

            //print_r("insert into patient values($pid,'$email','$fname','$lname','$newpassword','$address','$nic','$dob','$tele');");
            $_SESSION["user"]=$email;
            $_SESSION["usertype"]="p";
            $_SESSION["username"]=$fname;

            header('Location: patient/index.php');
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>';
        }
        
    }else{
        $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>';
    }



    
}else{
    //header('location: signup.php');
    $error='<label for="promter" class="form-label"></label>';
}

?>


    <!-- ACCOUNT CREATION FORM SECTION START here-->
    <div class="d-flex justify-content-center align-items-center flex-grow-1 w-100">
        <div class="w-100" style="max-width: 600px; padding: 20px;">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-2">Let's Get Started</h2>
                    <p class="text-center text-muted mb-4">It's Okay, Now Create User Account</p>
                    <form action="" method="POST" class="needs-validation" novalidate>
                    <?php echo $error; ?>
                    <div class="mb-3">
                        <label for="newemail" class="form-label">Email:</label>
                        <input type="email" name="newemail" id="newemail" class="form-control" placeholder="Email Address" 
                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required>
                        <div class="invalid-feedback">Please enter a valid email address (e.g., example@email.com)</div>
                        <div class="form-text">Format: example@email.com</div>
                    </div>
                    <div class="mb-3">
                        <label for="tele" class="form-label">Mobile Number (BD):</label>
                        <input type="tel" name="tele" id="tele" class="form-control" placeholder="ex: 01712345678" 
                               pattern="01[3-9][0-9]{8}" maxlength="11" required>
                        <div class="invalid-feedback">Bangladesh format: 11 digits, starts with 01, third digit 3-9 (e.g., 01712345678)</div>
                        <div class="form-text">Format: 01[3-9]xxxxxxxx (11 digits, e.g., 01712345678)</div>
                    </div>
                    <div class="mb-3">
                        <label for="newpassword" class="form-label">Create New Password:</label>
                        <input type="password" name="newpassword" id="newpassword" class="form-control" placeholder="New Password" 
                               minlength="6" required>
                        <div class="invalid-feedback">Password must be at least 6 characters long</div>
                        <div class="form-text">Minimum 6 characters required</div>
                    </div>
                    <div class="mb-3">
                        <label for="cpassword" class="form-label">Confirm Password:</label>
                        <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password" 
                               minlength="6" required>
                        <div class="invalid-feedback">Passwords do not match</div>
                        <div class="form-text">Re-enter your password to confirm</div>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="reset" value="Reset" class="btn btn-secondary">
                        <input type="submit" value="Sign Up" class="btn btn-primary flex-grow-1">
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p class="mb-0"><small>Already have an account? <a href="login.php" class="text-decoration-none">Login</a></small></p>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!--  ACCOUNT CREATION FORM SECTION END  -->
    <!-- PATIENT DASHBOARD & FEATURES - PATIENT REGISTRATION SECTION END -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Bootstrap native validation - minimal JS only for Bootstrap's built-in validation
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
</body>
</html>