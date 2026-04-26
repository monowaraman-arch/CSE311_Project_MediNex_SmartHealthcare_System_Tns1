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
    <title>Appointments</title>
    <style>
        .popup{animation: transitionIn-Y-bottom 0.5s;}
        .sub-table{animation: transitionIn-Y-bottom 0.5s;}
    </style>
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();

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
    include("../includes/auth-helper.php");
    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];


    //echo $userid;
    //echo $username;


    //  DATABASE RELATIONS USED: Appointment, Schedule, Patient, Doctor, Specialties 
    // Appointment: Get all appointments for logged-in patient (appoid, apponum, appodate, status)
    // Schedule: Join to get session details (scheduleid, title, scheduledate, scheduletime)
    // Patient: Filter by patient ID (pid)
    // Doctor: Join to get doctor name (docname)
    // Specialties: Used in appointment details to show doctor specialty
    $sqlmain= "select appointment.appoid,schedule.scheduleid,schedule.title,
                      doctor.docname,patient.pname,schedule.scheduledate,
                      schedule.scheduletime,appointment.apponum,appointment.appodate 
                from schedule 
                inner join appointment on schedule.scheduleid=appointment.scheduleid 
                inner join patient on patient.pid=appointment.pid 
                inner join doctor on schedule.docid=doctor.docid  
                where  patient.pid=$userid ";

    if($_POST){
        //print_r($_POST);  
        if(!empty($_POST["sheduledate"])){
            $sheduledate=$_POST["sheduledate"];
            $sqlmain.=" and schedule.scheduledate='$sheduledate' ";
        };
        //echo $sqlmain;
    }

    $sqlmain.="order by appointment.appodate  asc";
    $result= $database->query($sqlmain);
    ?>
    <div class="container">
        <div class="menu">
        <table class="menu-container" >
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table  class="profile-container">
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
                    <td class="menu-btn menu-icon-home" >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></div></a>
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
                    <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Bookings</p></div></a>
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
        <!-- APPOINTMENT MANAGEMENT HEADER SECTION START -->
        <div class="dash-body">
            <div class="container-fluid mt-3">
                <div class="row mb-3">
                    <div class="col-auto">
                        <a href="appointment.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h4 class="mb-0">My Bookings history</h4>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php date_default_timezone_set('Asia/Dhaka'); echo date('Y-m-d'); ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <h6>My Bookings (<?php echo $result->num_rows; ?>)</h6>
                    </div>
                </div>
                <!--  APPOINTMENT FILTER SECTION START-->
                <div class="row mb-3">
                    <div class="col">
                        <form action="" method="post" class="row g-3 align-items-end">
                            <div class="col-auto">
                                <label class="form-label">Date:</label>
                                <input type="date" name="sheduledate" class="form-control">
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--  APPOINTMENT FILTER SECTION END  -->
                <!--  APPOINTMENT MANAGEMENT HEADER SECTION END  -->
                
                <!-- APPOINTMENT CARDS DISPLAY SECTION START  -->
                <div class="row">
                    <?php
                    if($result->num_rows==0){
                        echo '<div class="col-12 text-center py-5">
                            <img src="../img/notfound.svg" width="25%" class="mb-3"><br>
                            <p class="h5">We couldn\'t find anything related to your keywords!</p>
                            <a href="appointment.php" class="btn btn-primary mt-3">Show all Appointments</a>
                        </div>';
                    } else {
                        for ($x=0; $x<$result->num_rows;$x++){
                            $row=$result->fetch_assoc();  //এক এক করে row fetch করা associative array হিসেবে
                            if (!isset($row)) break;
                            $scheduleid=$row["scheduleid"];
                            $title=$row["title"];
                            $docname=$row["docname"];
                            $scheduledate=$row["scheduledate"];
                            $scheduletime=$row["scheduletime"];
                            $apponum=$row["apponum"];
                            $appodate=$row["appodate"];
                            $appoid=$row["appoid"];
                            if($scheduleid=="") break;
                            
                            echo '<div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <small class="text-muted d-block mb-2">Booking Date: '.substr($appodate,0,30).'</small>
                                        <small class="text-muted d-block mb-2">Reference: OC-000-'.$appoid.'</small>
                                        <h5 class="card-title">'.substr($title,0,21).'</h5>
                                        <p class="mb-2"><strong>Appointment #:</strong> 0'.$apponum.'</p>
                                        <p class="mb-2"><strong>Doctor:</strong> '.substr($docname,0,30).'</p>
                                        <p class="mb-3"><small>Scheduled: '.$scheduledate.' @ '.substr($scheduletime,0,5).' (24h)</small></p>
                                        <div class="d-grid gap-2">
                                            <a href="?action=reschedule&id='.$appoid.'&scheduleid='.$scheduleid.'" class="btn btn-outline-primary btn-sm">Reschedule</a>
                                            <a href="?action=drop&id='.$appoid.'&title='.$title.'&doc='.$docname.'" class="btn btn-outline-danger btn-sm">Cancel Booking</a>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        }
                    }
                    ?>
                </div>
                <!-- APPOINTMENT CARDS DISPLAY SECTION END  -->
            </div>
        </div>
    </div>
    <?php
    
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        if($action=='booking-added'){
            // booking-added popup start
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Booking Successfully.</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                        Your Appointment number is '.$id.'.<br><br>
                            
                        </div>
                        <div class="d-flex justify-content-center">
                        <a href="appointment.php" class="btn btn-primary">OK</a>
                        </div>
                    </center>
            </div>
            </div>
            ';
            // booking-added popup end
        }elseif($action=='drop'){
            $title=$_GET["title"];
            $docname=$_GET["doc"];
            
            // drop (cancel appointment) confirmation popup start
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            You want to Cancel this Appointment?<br><br>
                            Session Name: &nbsp;<b>'.substr($title,0,40).'</b><br>
                            Doctor name&nbsp; : <b>'.substr($docname,0,40).'</b><br><br>
                            
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                        <a href="delete-appointment.php?id='.$id.'" class="btn btn-primary">Yes</a>
                        <a href="appointment.php" class="btn btn-secondary">No</a>
                        </div>
                    </center>
            </div>
            </div>
            '; 
            // drop (cancel appointment) confirmation popup end
        }elseif($action=='view'){
            $sqlmain= "select * from doctor where docid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row=$result->fetch_assoc();
            $name=$row["docname"];
            $email=$row["docemail"];
            $spe=$row["specialties"];
            
            $sqlmain= "select sname from specialties where id=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s",$spe);
            $stmt->execute();
            $spcil_res = $stmt->get_result();
            $spcil_array= $spcil_res->fetch_assoc();
            $spcil_name=$spcil_array["sname"];
            $nic=$row['docnic'];
            $tele=$row['doctel'];
            // doctor details view popup start
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2></h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            MediNex Web App<br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$name.'<br><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$email.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$nic.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$tele.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Specialties: </label>
                                    
                                </td>
                            </tr>
                            <tr>
                            <td class="label-td" colspan="2">
                            '.$spcil_name.'<br><br>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                
                                    
                                </td>
                
                            </tr>
                           

                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
            // doctor details view popup end
        }elseif($action=='reschedule'){
            $appoid = $_GET['id'];
            $current_scheduleid = $_GET['scheduleid'] ?? null;
            
            // Get current appointment details
            $app_sql = "select appointment.*, schedule.scheduledate,
                               schedule.scheduletime, schedule.title,
                               doctor.docname 
                        from appointment 
                        inner join schedule on appointment.scheduleid=schedule.scheduleid 
                        inner join doctor on schedule.docid=doctor.docid 
                        where appointment.appoid=$appoid and appointment.pid=$userid";
            
                        $app_result = $database->query($app_sql);
            if($app_result->num_rows==1){
                $app_data = $app_result->fetch_assoc();
                $current_date = $app_data['scheduledate'];
                $current_time = $app_data['scheduletime'];
                $docname = $app_data['docname'];
                $title = $app_data['title'];
                
                // Get available schedules for the same doctor
                $doc_sql = "select schedule.* from schedule 
                            inner join appointment on schedule.scheduleid=appointment.scheduleid 
                            where appointment.appoid=$appoid";

                $doc_result = $database->query($doc_sql);
                $doc_data = $doc_result->fetch_assoc();
                $docid = $doc_data['docid'];
                
                // Get other available schedules
                $schedules_sql = "select * from schedule 
                                  where docid=$docid and scheduledate >= CURDATE() 
                                  order by scheduledate, scheduletime";

                $schedules_result = $database->query($schedules_sql);
                
                $error_msg = '';
                if(isset($_GET['error'])){
                    if($_GET['error'] == 'overlap'){
                        $error_msg = '<label style="color:rgb(255, 62, 62);">This time slot is already booked!</label><br>';
                    } elseif($_GET['error'] == 'full'){
                        $error_msg = '<label style="color:rgb(255, 62, 62);">This session is full!</label><br>';
                    }
                }
                
                // reschedule form popup start
                echo '
                <div id="popup1" class="overlay">
                        <div class="popup">
                        <center>
                            <h2>Reschedule Appointment</h2>
                            <a class="close" href="appointment.php">&times;</a>
                            <div class="content">
                                <form action="reschedule-appointment.php" method="POST">
                                <input type="hidden" name="appoid" value="'.$appoid.'">
                                '.$error_msg.'
                                <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label class="form-label">Current Appointment: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <b>'.$title.'</b> with <b>'.$docname.'</b><br>
                                            Date: '.$current_date.' at '.substr($current_time,0,5).'<br><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label for="new_scheduleid" class="form-label">Select New Schedule: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <select name="new_scheduleid" class="input-text" required>
                                                <option value="">Choose a new schedule</option>';
                                                
                                                while($sched = $schedules_result->fetch_assoc()){
                                                    if($sched['scheduleid'] != $current_scheduleid){
                                                        $booked = getScheduleBookedCount($database, $sched['scheduleid']);
                                                        $max = getScheduleMaxPatients($database, $sched['scheduleid']);
                                                        $available = $max - $booked;
                                                        if($available > 0){
                                                            echo '<option value="'.$sched['scheduleid'].'">'.$sched['scheduledate'].' @ '.substr($sched['scheduletime'],0,5).' ('.$sched['title'].' - '.$available.' slots available)</option>';
                                                        }
                                                    }
                                                }
                                                
                                        echo '</select><br><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label for="new_date" class="form-label">New Appointment Date: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <input type="date" name="new_date" class="input-text" min="'.date('Y-m-d').'" required><br><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <input type="submit" name="reschedule" value="Reschedule" class="login-btn btn-primary btn">
                                            <a href="appointment.php"><input type="button" value="Cancel" class="login-btn btn-primary-soft btn"></a>
                                        </td>
                                    </tr>
                                </table>
                                </form>
                            </div>
                        </center>
                        <br><br>
                </div>
                </div>
                ';
                // reschedule form popup end
            }
        }elseif($action=='rescheduled'){
            // rescheduled success popup start
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Appointment Rescheduled Successfully.</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                        Your appointment has been rescheduled.<br><br>
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                        </div>
                    </center>
            </div>
            </div>
            ';
            // rescheduled success popup end
    }
}

        ?>
    <!--PATIENT DASHBOARD & FEATURES - APPOINTMENT MANAGEMENT SECTION END  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
