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
    <link rel="stylesheet" href="../css/admin-dashboard-theme.css">
    <link rel="stylesheet" href="../css/admin-doctors.css">
    <title>Doctors</title>
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
            <table class="menu-container" >
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table  class="profile-container">
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
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor menu-active menu-icon-doctor-active">
                        <a href="doctors.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Doctors</p></div></a>
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
        <div class="dash-body">
            <div class="container-fluid mt-3">
                <div class="row mb-3">
                    <div class="col-auto">
                        <a href="doctors.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <form action="" method="post" class="d-flex gap-2">
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
                        <strong><?php date_default_timezone_set('Asia/Dhaka'); echo date('Y-m-d'); ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <h5 class="mb-0">Add New Doctor</h5>
                    </div>
                    <div class="col-auto">
                        <a href="?action=add&id=none&error=0" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h6>All Doctors (<?php echo $list11->num_rows; ?>)</h6>
                    </div>
                </div>
                <?php
                    if($_POST){
                        $keyword=$_POST["search"];
                        
                        $sqlmain= "select * from doctor where docemail='$keyword' or docname='$keyword' or docname like '$keyword%' or docname like '%$keyword' or docname like '%$keyword%'";
                    }else{
                        $sqlmain= "select * from doctor order by docid desc";

                    }



                ?>
                  
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Doctor Name</th>
                                        <th>Email</th>
                                        <th>Specialties</th>
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
                                            <a href="doctors.php" class="btn btn-primary mt-3">Show all Doctors</a>
                                        </td></tr>';
                                    } else {
                                        for ($x=0; $x<$result->num_rows;$x++){
                                            $row=$result->fetch_assoc();
                                            $docid=$row["docid"];
                                            $name=$row["docname"];
                                            $email=$row["docemail"];
                                            $spe=$row["specialties"];
                                            $spcil_res= $database->query("select sname from specialties where id='$spe'");
                                            $spcil_array= $spcil_res->fetch_assoc();
                                            $spcil_name=$spcil_array["sname"];
                                            echo '<tr>
                                                <td>'.substr($name,0,30).'</td>
                                                <td>'.substr($email,0,20).'</td>
                                                <td>'.substr($spcil_name,0,20).'</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="?action=edit&id='.$docid.'&error=0" class="btn btn-sm btn-outline-primary">Edit</a>
                                                        <a href="?action=view&id='.$docid.'" class="btn btn-sm btn-outline-info" style="color: #0dcaf0 !important; border-color: #0dcaf0; background-image: none !important; background-repeat: no-repeat !important;">View</a>
                                                        <a href="?action=drop&id='.$docid.'&name='.$name.'" class="btn btn-sm btn-outline-danger" style="color: #dc3545 !important; border-color: #dc3545; background-image: none !important; background-repeat: no-repeat !important;">Remove</a>
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
    function admin_doctor_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    if($_GET){
        
        $id=isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $action=$_GET["action"] ?? "";
        if($action=='drop'){
            $nameget=$_GET["name"] ?? "";
            $short_name=admin_doctor_h(substr($nameget,0,40));
            ?>
            <div id="popup1" class="overlay admin-doctor-modal-overlay">
                <div class="popup admin-doctor-modal admin-doctor-confirm-modal">
                    <a class="close admin-doctor-modal-close" href="doctors.php" aria-label="Close">&times;</a>
                    <div class="admin-doctor-confirm-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h2>Remove Doctor?</h2>
                    <p>You want to delete this doctor record.</p>
                    <div class="admin-doctor-confirm-name"><?php echo $short_name; ?></div>
                    <div class="admin-doctor-confirm-actions">
                        <a href="doctors.php" class="btn btn-secondary">No</a>
                        <a href="delete-doctor.php?id=<?php echo $id; ?>" class="btn btn-primary">Yes</a>
                    </div>
                </div>
            </div>
            <?php
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
            <div id="popup1" class="overlay admin-doctor-modal-overlay">
                <div class="popup admin-doctor-modal admin-doctor-details-modal">
                    <a class="close admin-doctor-modal-close" href="doctors.php" aria-label="Close">&times;</a>
                    <div class="admin-doctor-modal-header">
                        <p>MediNex Web App</p>
                        <h2>View Details</h2>
                    </div>
                    <div class="admin-doctor-profile-card">
                        <span><?php echo admin_doctor_h(substr($name, 0, 1)); ?></span>
                        <div>
                            <h3><?php echo admin_doctor_h($name); ?></h3>
                            <p><?php echo admin_doctor_h($email); ?></p>
                        </div>
                    </div>
                    <div class="admin-doctor-detail-grid">
                        <div>
                            <span>Name</span>
                            <strong><?php echo admin_doctor_h($name); ?></strong>
                        </div>
                        <div>
                            <span>Email</span>
                            <strong><?php echo admin_doctor_h($email); ?></strong>
                        </div>
                        <div>
                            <span>NIC</span>
                            <strong><?php echo admin_doctor_h($nic); ?></strong>
                        </div>
                        <div>
                            <span>Telephone</span>
                            <strong><?php echo admin_doctor_h($tele); ?></strong>
                        </div>
                        <div class="admin-doctor-detail-wide">
                            <span>Specialties</span>
                            <strong><?php echo admin_doctor_h($spcil_name); ?></strong>
                        </div>
                    </div>
                    <div class="admin-doctor-modal-actions">
                        <a href="doctors.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }else{
            ?>
            <div id="popup1" class="overlay admin-doctor-modal-overlay">
                <div class="popup admin-doctor-modal admin-doctor-confirm-modal">
                    <a class="close admin-doctor-modal-close" href="doctors.php" aria-label="Close">&times;</a>
                    <h2>Doctor Not Found</h2>
                    <p>The selected doctor record is not available.</p>
                    <div class="admin-doctor-confirm-actions">
                        <a href="doctors.php" class="btn btn-primary">OK</a>
                    </div>
                </div>
            </div>
            <?php
            }
        }elseif($action=='add'){
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
                    
                        <a class="close" href="doctors.php">&times;</a> 
                        <div style="display: flex;justify-content: center;">
                        <div class="abc">
                        <div class="card">
                            <div class="card-body">
                                <form action="add-new.php" method="POST">
                                    '.$errorlist[$error_1].'
                                    <h5 class="card-title mb-4">Add New Doctor</h5>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="name" class="input-text" placeholder="Doctor Name" required><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="email" name="email" class="input-text" placeholder="Email Address" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="nic" class="input-text" placeholder="NIC Number" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="tel" name="Tele" class="input-text" placeholder="Telephone Number" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Choose specialties: </label>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <select name="spec" id="" class="box" >';
                                        
        
                                        $list11 = $database->query("select  * from  specialties order by sname asc;");
        
                                        for ($y=0;$y<$list11->num_rows;$y++){
                                            $row00=$list11->fetch_assoc();
                                            $sn=$row00["sname"];
                                            $id00=$row00["id"];
                                            echo "<option value=".$id00.">$sn</option><br/>";
                                        };
        
        
        
                                        
                        echo     '       </select><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="password" class="form-label">Password: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="password" name="password" class="input-text" placeholder="Defind a Password" required><br>
                                </td>
                            </tr><tr>
                                <td class="label-td" colspan="2">
                                    <label for="cpassword" class="form-label">Conform Password: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="password" name="cpassword" class="input-text" placeholder="Conform Password" required><br>
                                </td>
                            </tr>
                            
                
                            <tr>
                                <td colspan="2">
                                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                
                                    <input type="submit" value="Add" class="login-btn btn-primary btn">
                                </td>
                
                            </tr>
                           
                            </form>
                            </tr>
                        </table>
                        </div>
                        </div>
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
                                <h2>New Record Added Successfully!</h2>
                                <a class="close" href="doctors.php">&times;</a>
                                <div class="content">
                                    
                                    
                                </div>
                                <div class="d-flex justify-content-center">
                                <a href="doctors.php" class="btn btn-primary">OK</a>
                                </div>
                                <br><br>
                            </center>
                    </div>
                    </div>
        ';
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
                    '1'=>'Already have an account for this email address.',
                    '2'=>'Password confirmation error. Please confirm the password again.',
                    '3'=>'',
                    '4'=>'',
                    '0'=>'',

                );
                $error_message=$errorlist[$error_1] ?? '';

            if($error_1!='4'){
                    ?>
                    <div id="popup1" class="overlay admin-doctor-modal-overlay">
                        <div class="popup admin-doctor-modal admin-doctor-edit-modal">
                            <a class="close admin-doctor-modal-close" href="doctors.php" aria-label="Close">&times;</a>
                            <div class="admin-doctor-modal-header">
                                <p>Admin Doctors</p>
                                <h2>Edit Doctor Details</h2>
                                <span>Doctor ID: <?php echo $id; ?> (Auto Generated)</span>
                            </div>
                            <form action="edit-doc.php" method="POST" class="admin-doctor-form">
                                <?php if($error_message!=""){ ?>
                                    <div class="admin-doctor-alert"><?php echo admin_doctor_h($error_message); ?></div>
                                <?php } ?>
                                <input type="hidden" value="<?php echo $id; ?>" name="id00">
                                <input type="hidden" name="oldemail" value="<?php echo admin_doctor_h($email); ?>">
                                <div class="admin-doctor-form-grid">
                                    <div class="admin-doctor-field">
                                        <label for="doctor-email">Email</label>
                                        <input id="doctor-email" type="email" name="email" class="form-control" placeholder="Email Address" value="<?php echo admin_doctor_h($email); ?>" required>
                                    </div>
                                    <div class="admin-doctor-field">
                                        <label for="doctor-name">Name</label>
                                        <input id="doctor-name" type="text" name="name" class="form-control" placeholder="Doctor Name" value="<?php echo admin_doctor_h($name); ?>" required>
                                    </div>
                                    <div class="admin-doctor-field">
                                        <label for="doctor-nic">NIC</label>
                                        <input id="doctor-nic" type="text" name="nic" class="form-control" placeholder="NIC Number" value="<?php echo admin_doctor_h($nic); ?>" required>
                                    </div>
                                    <div class="admin-doctor-field">
                                        <label for="doctor-tele">Telephone</label>
                                        <input id="doctor-tele" type="tel" name="Tele" class="form-control" placeholder="Telephone Number" value="<?php echo admin_doctor_h($tele); ?>" required>
                                    </div>
                                    <div class="admin-doctor-field admin-doctor-field-wide">
                                        <label for="doctor-spec">Choose specialties <span>Current: <?php echo admin_doctor_h($spcil_name); ?></span></label>
                                        <select id="doctor-spec" name="spec" class="form-select">
                                            <?php
                                            $list11 = $database->query("select * from specialties order by sname asc;");
                                            for ($y=0;$y<$list11->num_rows;$y++){
                                                $row00=$list11->fetch_assoc();
                                                $selected=((int)$row00["id"]===$spe) ? " selected" : "";
                                                echo '<option value="'.admin_doctor_h($row00["id"]).'"'.$selected.'>'.admin_doctor_h($row00["sname"]).'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="admin-doctor-field">
                                        <label for="doctor-password">Password</label>
                                        <input id="doctor-password" type="password" name="password" class="form-control" placeholder="Define a Password" required>
                                    </div>
                                    <div class="admin-doctor-field">
                                        <label for="doctor-cpassword">Confirm Password</label>
                                        <input id="doctor-cpassword" type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                                <div class="admin-doctor-form-actions">
                                    <input type="reset" value="Reset" class="btn btn-secondary">
                                    <input type="submit" value="Save" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
        }else{
            ?>
                <div id="popup1" class="overlay admin-doctor-modal-overlay">
                    <div class="popup admin-doctor-modal admin-doctor-confirm-modal">
                        <a class="close admin-doctor-modal-close" href="doctors.php" aria-label="Close">&times;</a>
                        <div class="admin-doctor-confirm-icon admin-doctor-success-icon">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <h2>Edit Successfully!</h2>
                        <p>The doctor details were updated.</p>
                        <div class="admin-doctor-confirm-actions">
                            <a href="doctors.php" class="btn btn-primary">OK</a>
                        </div>
                    </div>
                </div>
            <?php



        }; 
            }else{
            ?>
                <div id="popup1" class="overlay admin-doctor-modal-overlay">
                    <div class="popup admin-doctor-modal admin-doctor-confirm-modal">
                        <a class="close admin-doctor-modal-close" href="doctors.php" aria-label="Close">&times;</a>
                        <h2>Doctor Not Found</h2>
                        <p>The selected doctor record is not available.</p>
                        <div class="admin-doctor-confirm-actions">
                            <a href="doctors.php" class="btn btn-primary">OK</a>
                        </div>
                    </div>
                </div>
            <?php
            }
        }; 
    };

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
