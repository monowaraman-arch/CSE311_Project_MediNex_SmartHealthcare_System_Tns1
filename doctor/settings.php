<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- START HERE: Bootstrap CDN Links Section -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- END HERE: Bootstrap CDN Links Section -->
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/doctor-dashboard-theme.css">
    <link rel="stylesheet" href="../css/doctor-settings.css">
        


    <title>Settings</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-X  0.5s;
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
    if(!function_exists('doctor_settings_h')){
        function doctor_settings_h($value){
            return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        }
    }
    $userrow = $database->query("select * from doctor where docemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["docid"];
    $username=$userfetch["docname"];


    //echo $userid;
    //echo $username;
    
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
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Dashboard</p></div></a>
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
                    <td class="menu-btn menu-icon-settings  menu-active menu-icon-settings-active">
                        <a href="settings.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
                
            </table>
        </div>
        <!-- START HERE: Bootstrap Layout Section - Header -->
        <div class="dash-body">
            <div class="container-fluid mt-3">
                <div class="row mb-3 align-items-center">
                    <div class="col-auto">
                        <a href="settings.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h5 class="mb-0">Settings</h5>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php 
                            date_default_timezone_set('Asia/Dhaka');
                            $today = date('Y-m-d');
                            echo $today;
                            $patientrow = $database->query("select  * from  patient;");
                            $doctorrow = $database->query("select  * from  doctor;");
                            $appointmentrow = $database->query("select  * from  appointment where appodate>='$today';");
                            $schedulerow = $database->query("select  * from  schedule where scheduledate='$today';");
                        ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
        <!-- END HERE: Bootstrap Layout Section - Header -->
                <!-- START HERE: Bootstrap Cards Section - Settings Options -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="?action=edit&id=<?php echo $userid ?>&error=0" class="text-decoration-none">
                            <div class="card dashboard-items setting-tabs h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="btn-icon-back dashboard-icons-setting me-3" style="background-image: url('../img/icons/doctors-hover.svg');"></div>
                                    <div>
                                        <h5 class="mb-1">Account Settings</h5>
                                        <p class="mb-0 text-muted small">Edit your Account Details & Change Password</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="?action=view&id=<?php echo $userid ?>" class="text-decoration-none">
                            <div class="card dashboard-items setting-tabs h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="btn-icon-back dashboard-icons-setting me-3" style="background-image: url('../img/icons/view-iceblue.svg');"></div>
                                    <div>
                                        <h5 class="mb-1">View Account Details</h5>
                                        <p class="mb-0 text-muted small">View Personal information About Your Account</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="?action=drop&id=<?php echo $userid.'&name='.$username ?>" class="text-decoration-none">
                            <div class="card dashboard-items setting-tabs h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="btn-icon-back dashboard-icons-setting me-3" style="background-image: url('../img/icons/patients-hover.svg');"></div>
                                    <div>
                                        <h5 class="mb-1 text-danger">Delete Account</h5>
                                        <p class="mb-0 text-muted small">Will Permanently Remove your Account</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- END HERE: Bootstrap Cards Section - Settings Options -->
            </div>
        </div>
    </div>
    <?php 
    if($_GET){
        
        $id=isset($_GET["id"]) ? (int) $_GET["id"] : 0;
        $action=$_GET["action"] ?? "";
        if($action=='drop'){
            $nameget=$_GET["name"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="settings.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br>('.substr($nameget,0,40).').
                            
                        </div>
                        <!-- START HERE: Bootstrap Button Group - Delete Confirmation -->
                        <div class="d-flex justify-content-center gap-2">
                        <a href="delete-doctor.php?id='.$id.'" class="btn btn-primary">Yes</a>
                        <a href="settings.php" class="btn btn-secondary">No</a>
                        </div>
                        <!-- END HERE: Bootstrap Button Group - Delete Confirmation -->
                    </center>
            </div>
            </div>
            ';
        }elseif($action=='view'){
            $sqlmain= "select * from doctor where docid='$id'";
            $result= $database->query($sqlmain);
            $row=$result ? $result->fetch_assoc() : null;

            if($row){
                $name=$row["docname"];
                $email=$row["docemail"];
                $spe=(int)$row["specialties"];
                
                $spcil_res= $database->query("select sname from specialties where id='$spe'");
                $spcil_array= $spcil_res ? $spcil_res->fetch_assoc() : null;
                $spcil_name=$spcil_array["sname"] ?? "Not assigned";
                $nic=$row['docnic'];
                $tele=$row['doctel'];
            ?>
            <div id="popup1" class="overlay doctor-settings-modal-overlay">
                <div class="popup doctor-settings-modal">
                    <a class="close doctor-settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                    <div class="doctor-settings-modal-header">
                        <p>MediNex Web App</p>
                        <h2>View Details</h2>
                    </div>
                    <div class="doctor-settings-profile-card">
                        <span><?php echo doctor_settings_h(substr($name, 0, 1)); ?></span>
                        <div>
                            <h3><?php echo doctor_settings_h($name); ?></h3>
                            <p><?php echo doctor_settings_h($email); ?></p>
                        </div>
                    </div>
                    <div class="doctor-settings-detail-grid">
                        <div>
                            <span>Name</span>
                            <strong><?php echo doctor_settings_h($name); ?></strong>
                        </div>
                        <div>
                            <span>Email</span>
                            <strong><?php echo doctor_settings_h($email); ?></strong>
                        </div>
                        <div>
                            <span>NIC</span>
                            <strong><?php echo doctor_settings_h($nic); ?></strong>
                        </div>
                        <div>
                            <span>Telephone</span>
                            <strong><?php echo doctor_settings_h($tele); ?></strong>
                        </div>
                        <div class="doctor-settings-detail-wide">
                            <span>Specialties</span>
                            <strong><?php echo doctor_settings_h($spcil_name); ?></strong>
                        </div>
                    </div>
                    <div class="doctor-settings-modal-actions">
                        <a href="settings.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }else{
            ?>
            <div id="popup1" class="overlay doctor-settings-modal-overlay">
                <div class="popup doctor-settings-modal doctor-settings-success">
                    <a class="close doctor-settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                    <h2>Doctor Not Found</h2>
                    <p>The selected doctor account is not available.</p>
                    <div class="doctor-settings-modal-actions">
                        <a href="settings.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }
        }elseif($action=='edit'){
            $sqlmain= "select * from doctor where docid='$id'";
            $result= $database->query($sqlmain);
            $row=$result ? $result->fetch_assoc() : null;

            if($row){
                $name=$row["docname"];
                $email=$row["docemail"];
                $spe=(int)$row["specialties"];
                
                $spcil_res= $database->query("select sname from specialties where id='$spe'");
                $spcil_array= $spcil_res ? $spcil_res->fetch_assoc() : null;
                $spcil_name=$spcil_array["sname"] ?? "Not assigned";
                $nic=$row['docnic'];
                $tele=$row['doctel'];

                $error_1=$_GET["error"] ?? "0";
                $errorlist= array(
                    '1'=>'Already have an account for this Email address.',
                    '2'=>'Password Confirmation Error! Reconfirm Password.',
                    '3'=>'',
                    '4'=>'',
                    '0'=>'',
                );

                if($error_1!='4'){
            ?>
            <div id="popup1" class="overlay doctor-settings-modal-overlay">
                <div class="popup doctor-settings-modal doctor-settings-edit-modal">
                    <a class="close doctor-settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                    <div class="doctor-settings-modal-header">
                        <p>Account Settings</p>
                        <h2>Edit Doctor Details</h2>
                        <span>Doctor ID: <?php echo doctor_settings_h($id); ?> (Auto Generated)</span>
                    </div>
                    <form action="edit-doc.php" method="POST" class="doctor-settings-form">
                        <?php if(!empty($errorlist[$error_1])){ ?>
                            <div class="doctor-settings-alert"><?php echo doctor_settings_h($errorlist[$error_1]); ?></div>
                        <?php } ?>
                        <input type="hidden" value="<?php echo doctor_settings_h($id); ?>" name="id00">
                        <input type="hidden" name="oldemail" value="<?php echo doctor_settings_h($email); ?>">
                        <div class="doctor-settings-profile-card">
                            <span><?php echo doctor_settings_h(substr($name, 0, 1)); ?></span>
                            <div>
                                <h3><?php echo doctor_settings_h($name); ?></h3>
                                <p><?php echo doctor_settings_h($email); ?></p>
                            </div>
                        </div>
                        <div class="doctor-settings-form-grid">
                            <div class="doctor-settings-field">
                                <label for="doctor-settings-email">Email</label>
                                <input id="doctor-settings-email" type="email" name="email" class="form-control" placeholder="Email Address" value="<?php echo doctor_settings_h($email); ?>" required>
                            </div>
                            <div class="doctor-settings-field">
                                <label for="doctor-settings-name">Name</label>
                                <input id="doctor-settings-name" type="text" name="name" class="form-control" placeholder="Doctor Name" value="<?php echo doctor_settings_h($name); ?>" required>
                            </div>
                            <div class="doctor-settings-field">
                                <label for="doctor-settings-nic">NIC</label>
                                <input id="doctor-settings-nic" type="text" name="nic" class="form-control" placeholder="NIC Number" value="<?php echo doctor_settings_h($nic); ?>" required>
                            </div>
                            <div class="doctor-settings-field">
                                <label for="doctor-settings-telephone">Telephone</label>
                                <input id="doctor-settings-telephone" type="tel" name="Tele" class="form-control" placeholder="Telephone Number" value="<?php echo doctor_settings_h($tele); ?>" required>
                            </div>
                            <div class="doctor-settings-field doctor-settings-field-wide">
                                <label for="doctor-settings-spec">Specialties <span>Current: <?php echo doctor_settings_h($spcil_name); ?></span></label>
                                <select id="doctor-settings-spec" name="spec" class="form-select" required>
                                    <?php
                                    $list11 = $database->query("select * from specialties order by sname asc;");
                                    if($list11){
                                        while($row00=$list11->fetch_assoc()){
                                            $specialty_id=(int)$row00["id"];
                                            $selected=$specialty_id===$spe ? "selected" : "";
                                    ?>
                                    <option value="<?php echo $specialty_id; ?>" <?php echo $selected; ?>><?php echo doctor_settings_h($row00["sname"]); ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="doctor-settings-field">
                                <label for="doctor-settings-password">Password</label>
                                <input id="doctor-settings-password" type="password" name="password" class="form-control" placeholder="Define a Password" required>
                            </div>
                            <div class="doctor-settings-field">
                                <label for="doctor-settings-confirm-password">Confirm Password</label>
                                <input id="doctor-settings-confirm-password" type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required>
                            </div>
                        </div>
                        <div class="doctor-settings-form-actions">
                            <input type="reset" value="Reset" class="btn btn-secondary">
                            <input type="submit" value="Save" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
            <?php
                }else{
            ?>
            <div id="popup1" class="overlay doctor-settings-modal-overlay">
                <div class="popup doctor-settings-modal doctor-settings-success">
                    <a class="close doctor-settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                    <div class="doctor-settings-success-icon">
                        <i class="bi bi-check2"></i>
                    </div>
                    <h2>Edit Successfully!</h2>
                    <p>If you changed your email, please log out and log in again with your new email.</p>
                    <div class="doctor-settings-form-actions justify-content-center">
                        <a href="settings.php" class="btn btn-primary">OK</a>
                        <a href="../logout.php" class="btn btn-outline-secondary">Log out</a>
                    </div>
                </div>
            </div>
            <?php
                }
            }else{
            ?>
            <div id="popup1" class="overlay doctor-settings-modal-overlay">
                <div class="popup doctor-settings-modal doctor-settings-success">
                    <a class="close doctor-settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                    <h2>Doctor Not Found</h2>
                    <p>The selected doctor account is not available.</p>
                    <div class="doctor-settings-form-actions justify-content-center">
                        <a href="settings.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }
        }

    }
        ?>
<!-- START HERE: Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- END HERE: Bootstrap JS Bundle -->
</body>
</html>
