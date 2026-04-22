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
        
    <title>Doctors</title>
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
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            //header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    
    

    //import database
    include("../connection.php");

    
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

            </table>
        </div>
        <!-- START HERE: Bootstrap Layout Section - Header with Search -->
        <div class="dash-body">
            <div class="container-fluid mt-3">
                <div class="row mb-3 align-items-center">
                    <div class="col-auto">
                        <a href="doctors.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <form action="" method="post" class="d-flex gap-2">
                            <input type="search" name="search" class="form-control" placeholder="Search Doctor name or Email" list="doctors">
                            <?php
                                echo '<datalist id="doctors">';
                                $list11 = $database->query("select  docname,docemail from  doctor;");
                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["docname"];
                                    $c=$row00["docemail"];
                                    echo "<option value='$d'>";
                                    echo "<option value='$c'>";
                                };
                                echo '</datalist>';
                            ?>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
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
        <!-- END HERE: Bootstrap Layout Section - Header with Search -->
                <!-- START HERE: Bootstrap Action Section - Add New Button -->
                <div class="row mb-3">
                    <div class="col">
                        <h5 class="mb-0">Add New Doctor</h5>
                    </div>
                    <div class="col-auto">
                        <a href="?action=add&id=none&error=0" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <h6>All Doctors (<?php echo $list11->num_rows; ?>)</h6>
                    </div>
                </div>
                <!-- END HERE: Bootstrap Action Section - Add New Button -->
                <?php
                    if($_POST){
                        $keyword=$_POST["search"];
                        
                        $sqlmain= "select * from doctor where docemail='$keyword' or docname='$keyword' or docname like '$keyword%' or docname like '%$keyword' or docname like '%$keyword%'";
                    }else{
                        $sqlmain= "select * from doctor order by docid desc";

                    }



                ?>
                <!-- START HERE: Bootstrap Table Section - Doctors List -->
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
                <!-- END HERE: Bootstrap Table Section - Doctors List -->
                        
                            <?php

                                
                                $result= $database->query($sqlmain);

                                if($result->num_rows==0){
                                    echo '<tr><td colspan="4" class="text-center py-5">
                                        <img src="../img/notfound.svg" width="25%" class="mb-3"><br>
                                        <p class="h5">We couldn\'t find anything related to your keywords!</p>
                                        <a href="doctors.php" class="btn btn-primary mt-3">Show all Doctors</a>
                                    </td></tr>';
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
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
                                                <a href="?action=view&id='.$docid.'" class="btn btn-sm btn-outline-info">View</a>
                                                <a href="?action=drop&id='.$docid.'&name='.$name.'" class="btn btn-sm btn-outline-danger">Remove</a>
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
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br>('.substr($nameget,0,40).').
                            
                        </div>
                        <!-- START HERE: Bootstrap Button Group - Delete Confirmation -->
                        <div class="d-flex justify-content-center gap-2">
                        <a href="delete-doctor.php?id='.$id.'" class="btn btn-primary">Yes</a>
                        <a href="doctors.php" class="btn btn-secondary">No</a>
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
                        <a class="close" href="doctors.php">&times;</a>
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
                                <a href="doctors.php" class="btn btn-primary">OK</a>
                            </div>
                        </div>
                        <!-- END HERE: Bootstrap Card Section - View Details Popup with Scrollbar -->
                    </center>
                    <br><br>
            </div>
            </div>
            ';
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
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        <tr>
                                <td class="label-td" colspan="2">'.
                                    $errorlist[$error_1]
                                .'</td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Add New Doctor.</p><br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <form action="add-new.php" method="POST" class="add-new-form">
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
                                        
        
                                        $list11 = $database->query("select  * from  specialties;");
        
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
                                <!-- START HERE: Bootstrap Button - Success Message -->
                                <div class="d-flex justify-content-center">
                                <a href="doctors.php" class="btn btn-primary">OK</a>
                                </div>
                                <!-- END HERE: Bootstrap Button - Success Message -->
                                <br><br>
                            </center>
                    </div>
                    </div>
        ';
            }
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
                            
                                <a class="close" href="doctors.php">&times;</a> 
                                <!-- START HERE: Bootstrap Card Form Section - Edit Doctor -->
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
                                <!-- END HERE: Bootstrap Card Form Section - Edit Doctor -->
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
                            <a class="close" href="doctors.php">&times;</a>
                            <div class="content">
                                
                                
                            </div>
                            <!-- START HERE: Bootstrap Button - Edit Success -->
                            <div class="d-flex justify-content-center">
                            <a href="doctors.php" class="btn btn-primary">OK</a>
                            </div>
                            <!-- END HERE: Bootstrap Button - Edit Success -->
                            <br><br>
                        </center>
                </div>
                </div>
    ';



        }; };
    };

?>
</div>
<!-- START HERE: Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- END HERE: Bootstrap JS Bundle -->
</body>
</html>
