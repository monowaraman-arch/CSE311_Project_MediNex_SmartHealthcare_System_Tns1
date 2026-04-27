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
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");

    
    ?>
    <div class="container app-shell">
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
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">admin@MediNex.com</p>
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
                    <td class="menu-btn menu-icon-doctor ">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Doctors</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">Patients</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="reports.php" class="non-style-link-menu"><div><p class="menu-text">Reports</p></div></a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="dash-body mt-3">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col">
                        <form action="doctors.php" method="post" class="d-flex gap-2">
                            <input type="search" name="search" class="form-control" placeholder="Search Doctor name or Email" list="doctors">
                            <?php
                                echo '<datalist id="doctors">';
                                $list11 = $database->query("select docname,docemail from doctor;");
                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    echo "<option value='".$row00["docname"]."'>";
                                    echo "<option value='".$row00["docemail"]."'>";
                                }
                                echo '</datalist>';
                            ?>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php 
                            date_default_timezone_set('Asia/Dhaka');
                            $today = date('Y-m-d');
                            echo $today;
                            $patientrow = $database->query("select * from patient;");
                            $doctorrow = $database->query("select * from doctor;");
                            $appointmentrow = $database->query("select * from appointment where appodate>='$today';");
                            $schedulerow = $database->query("select * from schedule where scheduledate='$today';");
                        ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
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
                                    <h2 class="mb-0"><?php echo $doctorrow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted">Doctors</p>
                                </div>
                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/doctors-hover.svg'); background-repeat: no-repeat; background-position: center; background-size: 40px 40px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-0"><?php echo $patientrow->num_rows; ?></h2>
                                    <p class="mb-0 text-muted">Patients</p>
                                </div>
                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/patients-hover.svg'); background-repeat: no-repeat; background-position: center; background-size: 40px 40px;"></div>
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
                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/book-hover.svg'); background-repeat: no-repeat; background-position: center; background-size: 40px 40px;"></div>
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
                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/session-iceblue.svg'); background-repeat: no-repeat; background-position: center; background-size: 40px 40px;"></div>
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
