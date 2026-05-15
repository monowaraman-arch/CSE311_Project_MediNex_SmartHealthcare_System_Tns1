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
    <link rel="stylesheet" href="../css/doctor-patient.css">
        
    <title>Patients</title>
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
                    <td class="menu-btn menu-icon-patient menu-active menu-icon-patient-active">
                        <a href="patient.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">My Patients</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings   ">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
                
            </table>
        </div>
        <?php       

                    $selecttype="My";
                    $current="My patients Only";
                    if($_POST){

                        if(isset($_POST["search"])){
                            $keyword=$_POST["search12"];
                            
                            $sqlmain= "select * from patient where pemail='$keyword' or pname='$keyword' or pname like '$keyword%' or pname like '%$keyword' or pname like '%$keyword%' ";
                            $selecttype="my";
                        }
                        
                        if(isset($_POST["filter"])){
                            if($_POST["showonly"]=='all'){
                                $sqlmain= "select * from patient";
                                $selecttype="All";
                                $current="All patients";
                            }else{
                                $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
                                $selecttype="My";
                                $current="My patients Only";
                            }
                        }
                    }else{
                        $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
                        $selecttype="My";
                    }



                ?>
        <div class="dash-body">
            <div class="container-fluid mt-3">
                <div class="row mb-3 align-items-center">
                    <div class="col-auto">
                        <a href="patient.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <form action="" method="post" class="d-flex gap-2">
                            <input type="search" name="search12" class="form-control" placeholder="Search Patient name or Email" list="patient">
                            <?php
                                echo '<datalist id="patient">';
                                $list11 = $database->query($sqlmain);
                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["pname"];
                                    $c=$row00["pemail"];
                                    echo "<option value='$d'>";
                                    echo "<option value='$c'>";
                                };
                                echo '</datalist>';
                            ?>
                            <button type="submit" name="search" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
                        </form>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php 
                            date_default_timezone_set('Asia/Dhaka');
                            $date = date('Y-m-d');
                            echo $date;
                        ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
               
                
                <div class="row mb-3">
                    <div class="col">
                        <h6><?php echo $selecttype." Patients (".$list11->num_rows.")"; ?></h6>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <form action="" method="post" class="d-flex gap-2 align-items-end">
                            <div class="col-auto">
                                <label class="form-label">Show Details About:</label>
                            </div>
                            <div class="col-auto">
                                <select name="showonly" class="form-select">
                                    <option value="" disabled selected hidden><?php echo $current   ?></option>
                                    <option value="my">My Patients Only</option>
                                    <option value="all">All Patients</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="filter" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>NIC</th>
                                        <th>Telephone</th>
                                        <th>Email</th>
                                        <th>Date of Birth</th>
                                        <th>Events</th>
                                    </tr>
                                </thead>
                                <tbody>
                        
                            <?php

                                
                                $result= $database->query($sqlmain);
                                //echo $sqlmain;
                                if($result->num_rows==0){
                                    echo '<tr><td colspan="6" class="text-center py-5">
                                        <img src="../img/notfound.svg" width="25%" class="mb-3"><br>
                                        <p class="h5">We couldn\'t find anything related to your keywords!</p>
                                        <a href="patient.php" class="btn btn-primary mt-3">Show all Patients</a>
                                    </td></tr>';
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
                                    $row=$result->fetch_assoc();
                                    $pid=$row["pid"];
                                    $name=$row["pname"];
                                    $email=$row["pemail"];
                                    $nic=$row["pnic"];
                                    $dob=$row["pdob"];
                                    $tel=$row["ptel"];
                                    
                                    echo '<tr>
                                        <td>'.substr($name,0,35).'</td>
                                        <td>'.substr($nic,0,12).'</td>
                                        <td>'.substr($tel,0,10).'</td>
                                        <td>'.substr($email,0,20).'</td>
                                        <td>'.substr($dob,0,10).'</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="?action=view&id='.$pid.'" class="btn btn-sm btn-outline-info">View</a>
                                                <a href="medical-history.php?patient_id='.$pid.'" class="btn btn-sm btn-outline-primary">History</a>
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
    function doctor_patient_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    if($_GET){
        
        $id=isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $action=$_GET["action"] ?? "";
        if($action=='view'){
            $sqlmain= "select * from patient where pid='$id'";
            $result= $database->query($sqlmain);
            $row=$result ? $result->fetch_assoc() : null;

            if($row){
                $name=$row["pname"];
                $email=$row["pemail"];
                $nic=$row["pnic"];
                $dob=$row["pdob"];
                $tele=$row["ptel"];
                $address=$row["paddress"];
            ?>
            <div id="popup1" class="overlay doctor-patient-modal-overlay">
                <div class="popup doctor-patient-modal doctor-patient-details-modal">
                    <a class="close doctor-patient-modal-close" href="patient.php" aria-label="Close">&times;</a>
                    <div class="doctor-patient-modal-header">
                        <p>My Patients</p>
                        <h2>View Details</h2>
                    </div>
                    <div class="doctor-patient-profile-card">
                        <span><i class="bi bi-person-heart"></i></span>
                        <div>
                            <h3><?php echo doctor_patient_h($name); ?></h3>
                            <p><?php echo doctor_patient_h($email); ?></p>
                        </div>
                    </div>
                    <div class="doctor-patient-detail-grid">
                        <div>
                            <span>Patient ID</span>
                            <strong>P-<?php echo doctor_patient_h($id); ?></strong>
                        </div>
                        <div>
                            <span>Name</span>
                            <strong><?php echo doctor_patient_h($name); ?></strong>
                        </div>
                        <div>
                            <span>Email</span>
                            <strong><?php echo doctor_patient_h($email); ?></strong>
                        </div>
                        <div>
                            <span>NIC</span>
                            <strong><?php echo doctor_patient_h($nic); ?></strong>
                        </div>
                        <div>
                            <span>Telephone</span>
                            <strong><?php echo doctor_patient_h($tele); ?></strong>
                        </div>
                        <div>
                            <span>Date of Birth</span>
                            <strong><?php echo doctor_patient_h($dob); ?></strong>
                        </div>
                        <div class="doctor-patient-detail-wide">
                            <span>Address</span>
                            <strong><?php echo doctor_patient_h($address); ?></strong>
                        </div>
                    </div>
                    <div class="doctor-patient-modal-actions">
                        <a href="medical-history.php?patient_id=<?php echo doctor_patient_h($id); ?>" class="btn btn-primary-soft">History</a>
                        <a href="patient.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }else{
            ?>
            <div id="popup1" class="overlay doctor-patient-modal-overlay">
                <div class="popup doctor-patient-modal doctor-patient-confirm-modal">
                    <a class="close doctor-patient-modal-close" href="patient.php" aria-label="Close">&times;</a>
                    <h2>Patient Not Found</h2>
                    <p>The selected patient record is not available.</p>
                    <div class="doctor-patient-confirm-actions">
                        <a href="patient.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }
        }
    };

?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
