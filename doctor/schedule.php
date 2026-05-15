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
    <link rel="stylesheet" href="../css/doctor-dashboard-theme.css">
    <link rel="stylesheet" href="../css/doctor-schedule.css">
        
    <title>Schedule</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
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
                 <td class="menu-btn menu-icon-dashbord " >
                     <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Dashboard</p></div></a>
                 </td>
             </tr>
             <tr class="menu-row">
                 <td class="menu-btn menu-icon-appoinment  ">
                     <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Appointments</p></div></a>
                 </td>
             </tr>
             
             <tr class="menu-row" >
                 <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                     <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Sessions</p></div></a>
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
                <div class="row mb-3 align-items-center">
                    <div class="col-auto">
                        <a href="schedule.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h5 class="mb-0">My Sessions</h5>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php 
                            date_default_timezone_set('Asia/Dhaka');
                            $today = date('Y-m-d');
                            echo $today;
                            $list110 = $database->query("select  * from  schedule where docid=$userid;");
                        ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
               
                
                <div class="row mb-3">
                    <div class="col">
                        <h6>My Sessions (<?php echo $list110->num_rows; ?>)</h6>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <form action="" method="post" class="d-flex gap-2 align-items-end">
                            <div class="flex-grow-1">
                                <label class="form-label">Date:</label>
                                <input type="date" name="sheduledate" id="date" class="form-control">
                            </div>
                            <div>
                                <button type="submit" name="filter" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <?php

                $sqlmain= "select schedule.scheduleid,schedule.title,doctor.docname,schedule.scheduledate,schedule.scheduletime,schedule.nop from schedule inner join doctor on schedule.docid=doctor.docid where doctor.docid=$userid ";
                    if($_POST){
                        //print_r($_POST);
                        $sqlpt1="";
                        if(!empty($_POST["sheduledate"])){
                            $sheduledate=$_POST["sheduledate"];
                            $sqlmain.=" and schedule.scheduledate='$sheduledate' ";
                        }

                    }

                ?>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Session Title</th>
                                        <th>Scheduled Date & Time</th>
                                        <th>Max num that can be booked</th>
                                        <th>Events</th>
                                    </tr>
                                </thead>
                                <tbody>
                        
                            <?php

                                
                                $result= $database->query($sqlmain);

                                if($result->num_rows==0){
                                    echo '<tr><td colspan="4" class="text-center py-5">
                                        <img src="../img/notfound.svg" width="25%" class="mb-3"><br>
                                        <p class="h5">We couldn\'t find anything related to your keywords!</p>
                                        <a href="schedule.php" class="btn btn-primary mt-3">Show all Sessions</a>
                                    </td></tr>';
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
                                    $row=$result->fetch_assoc();
                                    $scheduleid=$row["scheduleid"];
                                    $title=$row["title"];
                                    $docname=$row["docname"];
                                    $scheduledate=$row["scheduledate"];
                                    $scheduletime=$row["scheduletime"];
                                    $nop=$row["nop"];
                                    echo '<tr>
                                        <td>'.substr($title,0,30).'</td>
                                        <td class="text-center">'.substr($scheduledate,0,10).' '.substr($scheduletime,0,5).'</td>
                                        <td class="text-center">'.$nop.'</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="?action=view&id='.$scheduleid.'" class="btn btn-sm btn-outline-info">View</a>
                                                <a href="?action=drop&id='.$scheduleid.'&name='.$title.'" class="btn btn-sm btn-outline-danger">Cancel Session</a>
                                            </div>
                                        </td>
                                    </tr>';
                                    
                                }
                            }
                                 
                            ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    function doctor_schedule_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
    
    if($_GET){
        $id=isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $action=$_GET["action"] ?? "";
        if($action=='drop'){
            $nameget=$_GET["name"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="schedule.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br>('.substr($nameget,0,40).').
                            
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                        <a href="delete-session.php?id='.$id.'" class="btn btn-primary">Yes</a>
                        <a href="schedule.php" class="btn btn-secondary">No</a>
                        </div>
                    </center>
            </div>
            </div>
            '; 
        }elseif($action=='view'){
            $sqlmain= "select schedule.scheduleid,schedule.title,doctor.docname,schedule.scheduledate,schedule.scheduletime,schedule.nop from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduleid=$id and doctor.docid=$userid";
            $result= $database->query($sqlmain);
            $row=$result ? $result->fetch_assoc() : null;

            if($row){
                $docname=$row["docname"];
                $scheduleid=$row["scheduleid"];
                $title=$row["title"];
                $scheduledate=$row["scheduledate"];
                $scheduletime=$row["scheduletime"];
                $nop=$row['nop'];

                $sqlmain12= "select appointment.apponum, patient.pid, patient.pname, patient.ptel from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.scheduleid=$id;";
                $result12= $database->query($sqlmain12);
                $registered_count=$result12 ? $result12->num_rows : 0;
            ?>
            <div id="popup1" class="overlay doctor-schedule-modal-overlay">
                <div class="popup doctor-schedule-modal doctor-schedule-details-modal">
                    <a class="close doctor-schedule-modal-close" href="schedule.php" aria-label="Close">&times;</a>
                    <div class="doctor-schedule-modal-header">
                        <p>My Sessions</p>
                        <h2>View Details</h2>
                    </div>
                    <div class="doctor-schedule-summary">
                        <div>
                            <span>Session Title</span>
                            <strong><?php echo doctor_schedule_h($title); ?></strong>
                        </div>
                        <div>
                            <span>Doctor</span>
                            <strong><?php echo doctor_schedule_h($docname); ?></strong>
                        </div>
                        <div>
                            <span>Scheduled Date</span>
                            <strong><?php echo doctor_schedule_h($scheduledate); ?></strong>
                        </div>
                        <div>
                            <span>Scheduled Time</span>
                            <strong><?php echo doctor_schedule_h(substr($scheduletime,0,5)); ?></strong>
                        </div>
                        <div>
                            <span>Registered</span>
                            <strong><?php echo doctor_schedule_h($registered_count); ?> / <?php echo doctor_schedule_h($nop); ?></strong>
                        </div>
                        <div>
                            <span>Schedule ID</span>
                            <strong>#<?php echo doctor_schedule_h($scheduleid); ?></strong>
                        </div>
                    </div>
                    <div class="doctor-schedule-patients">
                        <div class="doctor-schedule-section-title">
                            <h3>Registered Patients</h3>
                            <span><?php echo doctor_schedule_h($registered_count); ?> total</span>
                        </div>
                        <?php if($registered_count==0){ ?>
                            <div class="doctor-schedule-empty-state">
                                <img src="../img/notfound.svg" alt="">
                                <p>No patients are registered for this session yet.</p>
                                <a href="appointment.php" class="btn btn-primary-soft">Show all Appointments</a>
                            </div>
                        <?php }else{ ?>
                            <div class="doctor-schedule-table-wrap">
                                <table class="table doctor-schedule-patient-table">
                                    <thead>
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Patient Name</th>
                                            <th>Appointment No.</th>
                                            <th>Patient Telephone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($patient=$result12->fetch_assoc()){ ?>
                                            <tr>
                                                <td>#<?php echo doctor_schedule_h(substr($patient["pid"],0,15)); ?></td>
                                                <td><?php echo doctor_schedule_h(substr($patient["pname"],0,25)); ?></td>
                                                <td><span><?php echo doctor_schedule_h($patient["apponum"]); ?></span></td>
                                                <td><?php echo doctor_schedule_h(substr($patient["ptel"],0,25)); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="doctor-schedule-modal-actions">
                        <a href="schedule.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }else{
            ?>
            <div id="popup1" class="overlay doctor-schedule-modal-overlay">
                <div class="popup doctor-schedule-modal doctor-schedule-confirm-modal">
                    <a class="close doctor-schedule-modal-close" href="schedule.php" aria-label="Close">&times;</a>
                    <h2>Session Not Found</h2>
                    <p>The selected session is not available for your account.</p>
                    <div class="doctor-schedule-confirm-actions">
                        <a href="schedule.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }
    }
}

    ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
