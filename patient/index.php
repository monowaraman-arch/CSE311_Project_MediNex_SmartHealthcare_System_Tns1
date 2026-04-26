<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Dashboard</title>
    <style>
        /* Animations disabled */
    </style>
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();
 //User valid
    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");

    $sqlmain= "select * from patient where pemail=?"; // Get patient record based on session email to display user info and for use in features
    $stmt = $database->prepare($sqlmain);  //prepare statement
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    //get user id and name from patient table for use ul
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];


    ?>
    <!--  PATIENT DASHBOARD SECTION START  -->
    <div class="container">
        <div class="menu">
            <table class="menu-container" >
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-home menu-active menu-icon-home-active" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Home</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></div></a>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="prescriptions.php" class="non-style-link-menu"><div><p class="menu-text">My Prescriptions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
                
            </table>
        </div>
        <!--  DASHBOARD HEADER SECTION START  -->
        <div class="dash-body mt-3">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col">
                        <h4 class="mb-0">Home</h4>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php 
                            date_default_timezone_set('Asia/Dhaka');
                            $today = date('Y-m-d');
                            echo $today;
                            $patientrow = $database->query("select * from patient;"); // Get total patient count for dashboard stats
                            $doctorrow = $database->query("select * from doctor;"); // Get total doctor count for dashboard stats
                            $appointmentrow = $database->query("select * from appointment where appodate>='$today';"); // Get upcoming appointment count for dashboard stats
                            $schedulerow = $database->query("select * from schedule where scheduledate='$today';"); // Get today's schedule count for dashboard stats
                        ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
                <!--  DASHBOARD HEADER SECTION END -->
                <!--  DOCTOR SEARCH SECTION START  -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">Channel a Doctor Here</h5>
                                <form action="schedule.php" method="post" class="d-flex gap-2">
                                    <input type="search" name="search" class="form-control" placeholder="Search Doctor and We will Find The Session Available" list="doctors">
                                    <?php
                                        echo '<datalist id="doctors">'; //Doctor search input auto-suggestion
                                        $list11 = $database->query("select docname,docemail from doctor;"); // Get all doctor names and emails for datalist options
                                        for ($y=0;$y<$list11->num_rows;$y++){
                                            $row00=$list11->fetch_assoc();
                                            echo "<option value='".$row00["docname"]."'>";
                                        }
                                        echo '</datalist>';
                                    ?>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DOCTOR SEARCH SECTION END  -->
                <!--  WELCOME SECTION START  -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>Welcome!</h3>
                                <h1><?php echo $username; ?>.</h1>
                                <p>Haven't any idea about doctors? No problem, let's jump to 
                                    <a href="doctors.php" class="text-decoration-none fw-bold">"All Doctors"</a> section or 
                                    <a href="schedule.php" class="text-decoration-none fw-bold">"Sessions"</a><br>
                                    Track your past and future appointments history.<br>Also find out the expected arrival time of your doctor or medical consultant.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- WELCOME SECTION END  -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Status</h5>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $doctorrow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted">All Doctors</p>
                                </div>
                                <div class="dashboard-icons">
                                    <img src="../img/icons/doctors-hover.svg" alt="Doctors" class="dashboard-icon-img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $patientrow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted">All Patients</p>
                                </div>
                                <div class="dashboard-icons">
                                    <img src="../img/icons/patients-hover.svg" alt="Patients" class="dashboard-icon-img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $appointmentrow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted">NewBooking</p>
                                </div>
                                <div class="dashboard-icons">
                                    <img src="../img/icons/book-hover.svg" alt="Bookings" class="dashboard-icon-img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $schedulerow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted small">Today Sessions</p>
                                </div>
                                <div class="dashboard-icons">
                                    <img src="../img/icons/session-iceblue.svg" alt="Sessions" class="dashboard-icon-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!--  PATIENT DASHBOARD SECTION END -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
