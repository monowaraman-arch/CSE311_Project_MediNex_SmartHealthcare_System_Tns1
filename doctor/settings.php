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
        
        $id=$_GET["id"];
        $action=$_GET["action"];
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
            $row=$result->fetch_assoc();
            $name=$row["docname"];
            $email=$row["docemail"];
            $spe=$row["specialties"];
            
            $spcil_res= $database->query("select sname from specialties where id='$spe'");
            $spcil_array= $spcil_res->fetch_assoc();
            $spcil_name=$spcil_array["sname"];
            $nic=$row['docnic'];
            $tele=$row['doctel'];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup" style="max-height: 90vh; overflow-y: auto;">
                    <center>
                        <h2></h2>
                        <a class="close" href="settings.php">&times;</a>
                        <div class="content" style="padding: 20px;">
                            MediNex Web App<br>
                            
                        </div>
                        <!-- START HERE: Bootstrap Card Section - View Details Popup with Scrollbar -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">View Details</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Name:</label>
                                    <p class="mb-0">'.$name.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email:</label>
                                    <p class="mb-0">'.$email.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">NIC:</label>
                                    <p class="mb-0">'.$nic.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Telephone:</label>
                                    <p class="mb-0">'.$tele.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Specialties:</label>
                                    <p class="mb-0">'.$spcil_name.'</p>
                                </div>
                                <a href="settings.php" class="btn btn-primary">OK</a>
                            </div>
                        </div>
                        <!-- END HERE: Bootstrap Card Section - View Details Popup with Scrollbar -->
                    </center>
                    <br><br>
            </div>
            </div>
            ';
        }elseif($action=='edit'){
            $sqlmain= "select * from doctor where docid='$id'";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $name=$row["docname"];
            $email=$row["docemail"];
            $spe=$row["specialties"];
            
            $spcil_res= $database->query("select sname from specialties where id='$spe'");
            $spcil_array= $spcil_res->fetch_assoc();
            $spcil_name=$spcil_array["sname"];
            $nic=$row['docnic'];
            $tele=$row['doctel'];

            $error_1=$_GET["error"];
                $errorlist= array(
                    '1'=>'<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>',
                    '2'=>'<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>',
                    '3'=>'<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
                    '4'=>"",
                    '0'=>'',

                );

            if($error_1!='4'){
                    echo '
                    <div id="popup1" class="overlay">
                            <div class="popup">
                            <center>
                            
                                <a class="close" href="settings.php">&times;</a> 
                                <!-- START HERE: Bootstrap Card Form Section - Edit Doctor Settings -->
                                <div class="card">
                                    <div class="card-body">
                                        '.$errorlist[$error_1].'
                                        <h5 class="card-title mb-4">Edit Doctor Details</h5>
                                        <p class="text-muted">Doctor ID: '.$id.' (Auto Generated)</p>
                                        <form action="edit-doc.php" method="POST">
                                            <input type="hidden" value="'.$id.'" name="id00">
                                            <input type="hidden" name="oldemail" value="'.$email.'">
                                            <div class="mb-3">
                                                <label for="Email" class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" placeholder="Email Address" value="'.$email.'" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" placeholder="Doctor Name" value="'.$name.'" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nic" class="form-label">NIC</label>
                                                <input type="text" name="nic" class="form-control" placeholder="NIC Number" value="'.$nic.'" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="Tele" class="form-label">Telephone</label>
                                                <input type="tel" name="Tele" class="form-control" placeholder="Telephone Number" value="'.$tele.'" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="spec" class="form-label">Choose specialties (Current: '.$spcil_name.')</label>
                                                <select name="spec" class="form-select">';
                                                
                                                $list11 = $database->query("select * from specialties;");
                                                for ($y=0;$y<$list11->num_rows;$y++){
                                                    $row00=$list11->fetch_assoc();
                                                    echo "<option value=".$row00["id"].">".$row00["sname"]."</option>";
                                                }
                                                
                                echo     '       </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" name="password" class="form-control" placeholder="Define a Password" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="cpassword" class="form-label">Confirm Password</label>
                                                <input type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <input type="reset" value="Reset" class="btn btn-secondary">
                                                <input type="submit" value="Save" class="btn btn-primary">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- END HERE: Bootstrap Card Form Section - Edit Doctor Settings -->
                            </center>
                            <br><br>
                    </div>
                    </div>
                    ';
        }else{
            echo '
                <div id="popup1" class="overlay">
                        <div class="popup">
                        <center>
                        <br><br><br><br>
                            <h2>Edit Successfully!</h2>
                            <a class="close" href="settings.php">&times;</a>
                            <div class="content">
                                If You change your email also Please logout and login again with your new email
                                
                            </div>
                            <!-- START HERE: Bootstrap Button Group - Edit Success -->
                            <div class="d-flex justify-content-center gap-2">
                            <a href="settings.php" class="btn btn-primary">OK</a>
                            <a href="../logout.php" class="btn btn-outline-secondary">Log out</a>
                            </div>
                            <!-- END HERE: Bootstrap Button Group - Edit Success -->
                            <br><br>
                        </center>
                </div>
                </div>
    ';



        }; }

    }
        ?>
<!-- START HERE: Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- END HERE: Bootstrap JS Bundle -->
</body>
</html>
