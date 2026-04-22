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

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");
    $userrow = $database->query("select * from doctor where docemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["docid"];
    $username=$userfetch["docname"];


    //echo $userid;
    //echo $username;
    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
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
                    <td class="menu-btn menu-icon-dashbord menu-active menu-icon-dashbord-active" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Dashboard</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Appointments</p></div></a>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">My Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">My Patients</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body">
            <div class="container-fluid mt-3">
                <div class="row mb-3">
                    <div class="col">
                        <h4 class="mb-0">Dashboard</h4>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php 
                            date_default_timezone_set('Asia/Dhaka');
                            $today = date('Y-m-d');
                            echo $today;
                            // Get doctor-specific statistics
                            $mypatientrow = $database->query("select distinct patient.* from patient inner join appointment on patient.pid=appointment.pid inner join schedule on appointment.scheduleid=schedule.scheduleid where schedule.docid=$userid;");
                            $myappointmentrow = $database->query("select * from appointment inner join schedule on appointment.scheduleid=schedule.scheduleid where schedule.docid=$userid and appointment.appodate>='$today';");
                            $myschedulerow = $database->query("select * from schedule where docid=$userid and scheduledate='$today';");
                            $myprescriptionrow = $database->query("select * from prescriptions where doctor_id=$userid;");
                        ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h3>Welcome!</h3>
                                <h1><?php echo $username; ?>.</h1>
                                <p>Thanks for joining with us. We are always trying to get you a complete service<br>
                                You can view your daily schedule, Reach Patients Appointment at home!<br><br>
                                </p>
                                <a href="appointment.php" class="btn btn-primary">View My Appointments</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">Status</h5>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $mypatientrow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted">My Patients</p>
                                </div>
                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/patients-hover.svg'); background-repeat: no-repeat; background-position: center; background-size: 40px 40px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $myappointmentrow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted">New Booking</p>
                                </div>
                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/book-hover.svg'); background-repeat: no-repeat; background-position: center; background-size: 40px 40px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $myschedulerow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted small">Today Sessions</p>
                                </div>
                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/session-iceblue.svg'); background-repeat: no-repeat; background-position: center; background-size: 40px 40px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $myprescriptionrow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted">Prescriptions</p>
                                </div>
                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/book-hover.svg'); background-repeat: no-repeat; background-position: center; background-size: 40px 40px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
