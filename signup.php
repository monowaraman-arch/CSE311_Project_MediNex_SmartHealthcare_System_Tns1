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
    <title>Sign Up</title>
    <style>
        /* use Bootstrap defaults  */
        .form-text {font-size: 0.875rem; color: #6c757d; margin-top: 0.25rem;}
    </style>
</head>
<body class="d-flex flex-column" style="min-height: 100vh; padding-top: 70px;">
    <!-- Navbar  -->
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
                   
                </ul>
            </div>
        </div>
    </nav>
    <?php
    //  PATIENT DASHBOARD & FEATURES - PATIENT REGISTRATION SECTION START 
    session_start();
    $_SESSION["user"]="";          //old system data clear
    $_SESSION["usertype"]="";
    date_default_timezone_set('Asia/Dhaka');
    $_SESSION["date"]=date('Y-m-d');

    if($_POST){
        $_SESSION["personal"]=array(
            'fname'=>$_POST['fname'],
            'lname'=>$_POST['lname'],
            'address'=>$_POST['address'],
            'nic'=>$_POST['nic'],
            'dob'=>$_POST['dob']
        );
        header("location: create-account.php"); /// data transfer to create-account.php
    }
    ?>


    <!-- ========== PATIENT REGISTRATION FORM SECTION START ========== -->
    <div class="d-flex justify-content-center align-items-center flex-grow-1 w-100">
        <div class="w-100" style="max-width: 600px; padding: 20px;">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-2">Let's Get Started</h2>
                    <p class="text-center text-muted mb-4">Add Your Personal Details to Continue</p>
                    <form action="" method="POST" class="needs-validation" novalidate>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fname" class="form-label">First Name:</label>
                            <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" 
                                   pattern="[A-Za-z\s]{2,}" required>
                            <div class="invalid-feedback">First name must be at least 2 letters (letters and spaces only)</div>
                            <div class="form-text">Only letters and spaces, minimum 2 characters</div>
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label">Last Name:</label>
                            <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name" 
                                   pattern="[A-Za-z\s]{2,}" required>
                            <div class="invalid-feedback">Last name must be at least 2 letters (letters and spaces only)</div>
                            <div class="form-text">Only letters and spaces, minimum 2 characters</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address:</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="Address" 
                               minlength="5" required>
                        <div class="invalid-feedback">Address must be at least 5 characters long</div>
                        <div class="form-text">Enter your complete address</div>
                    </div>
                    <div class="mb-3">
                        <label for="nic" class="form-label">NID (Bangladesh):</label>
                        <input type="text" name="nic" id="nic" class="form-control" placeholder="e.g., 1234567890" 
                               pattern="\\d{10}" maxlength="10" required>
                        <div class="invalid-feedback">Bangladesh NID must be exactly 10 digits (numbers only), e.g., 1234567890</div>
                        <div class="form-text">Format: 10 digits only</div>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth:</label>
                        <input type="date" name="dob" id="dob" class="form-control" 
                               max="<?php echo date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">Please select a valid date of birth (cannot be future date)</div>
                        <div class="form-text">Select your date of birth</div>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="reset" value="Reset" class="btn btn-secondary">
                        <input type="submit" value="Next" class="btn btn-primary flex-grow-1">
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p class="mb-0"><small>Already have an account? <a href="login.php" class="text-decoration-none">Login</a></small></p>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PATIENT REGISTRATION FORM SECTION END  -->
    <!--  PATIENT DASHBOARD & FEATURES - PATIENT REGISTRATION SECTION END  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>