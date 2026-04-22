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
        
    <title>Prescriptions</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .popup, .popup * {
                visibility: visible;
            }
            .overlay {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .popup {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
                background: white !important;
                box-shadow: none !important;
            }
            .close, h2, button, .btn {
                display: none !important;
            }
            .content {
                max-height: none !important;
                overflow: visible !important;
            }
            .card {
                box-shadow: none !important;
                border: none !important;
            }
            .table {
                border-collapse: collapse !important;
            }
            .table td, .table th {
                border: 1px solid #ddd !important;
                padding: 8px !important;
            }
            center {
                text-align: left !important;
            }
        }
        body.popup-open {
            overflow: hidden;
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
                    <td class="menu-btn menu-icon-dashbord " >
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
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="prescriptions.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Prescriptions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
                
            </table>
        </div>
        <!-- START HERE: Bootstrap Layout Section - Header -->
        <div class="dash-body">
            <div class="container-fluid mt-3">
                <div class="row mb-3 align-items-center">
                    <div class="col-auto">
                        <a href="prescriptions.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h5 class="mb-0">Prescription Manager</h5>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php 
                            date_default_timezone_set('Asia/Dhaka');
                            $today = date('Y-m-d');
                            echo $today;
                            $list110 = $database->query("select * from prescriptions where doctor_id=$userid");
                        ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
        <!-- END HERE: Bootstrap Layout Section - Header -->
                <!-- START HERE: Bootstrap Filter Section - Date Filter -->
                <div class="row mb-3">
                    <div class="col">
                        <h6>My Prescriptions (<?php echo $list110->num_rows; ?>)</h6>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <form action="" method="post" class="d-flex gap-2 align-items-end">
                            <div class="flex-grow-1">
                                <label class="form-label">Date:</label>
                                <input type="date" name="prescriptiondate" id="date" class="form-control">
                            </div>
                            <div>
                                <button type="submit" name="filter" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END HERE: Bootstrap Filter Section - Date Filter -->
                
                <?php

                    $sqlmain= "select prescriptions.*, patient.pname, appointment.apponum from prescriptions inner join patient on prescriptions.patient_id=patient.pid left join appointment on prescriptions.appointment_id=appointment.appoid where prescriptions.doctor_id=$userid ";

                    if($_POST){
                        if(!empty($_POST["prescriptiondate"])){
                            $prescriptiondate=$_POST["prescriptiondate"];
                            $sqlmain.=" and prescriptions.prescription_date='$prescriptiondate' ";
                        };
                    }

                    $sqlmain.=" order by prescriptions.prescription_date desc";

                ?>
                <!-- START HERE: Bootstrap Table Section - Prescriptions List -->
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Prescription Date</th>
                                        <th>Diagnosis</th>
                                        <th>Follow-up Date</th>
                                        <th>Events</th>
                                    </tr>
                                </thead>
                                <tbody>
                <!-- END HERE: Bootstrap Table Section - Prescriptions List -->
                        
                            <?php

                                
                                $result= $database->query($sqlmain);

                                if($result->num_rows==0){
                                    echo '<tr><td colspan="5" class="text-center py-5">
                                        <img src="../img/notfound.svg" width="25%" class="mb-3"><br>
                                        <p class="h5">We couldn\'t find any prescriptions!</p>
                                        <a href="prescriptions.php" class="btn btn-primary mt-3">Show all Prescriptions</a>
                                    </td></tr>';
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
                                    $row=$result->fetch_assoc();
                                    $prescription_id=$row["prescription_id"];
                                    $pname=$row["pname"];
                                    $prescription_date=$row["prescription_date"];
                                    $diagnosis=$row["diagnosis"];
                                    $follow_up_date=$row["follow_up_date"];
                                    echo '<tr>
                                        <td class="fw-bold">'.substr($pname,0,25).'</td>
                                        <td class="text-center">'.$prescription_date.'</td>
                                        <td>'.substr($diagnosis,0,30).'...</td>
                                        <td class="text-center">'.($follow_up_date ? $follow_up_date : 'N/A').'</td>
                                        <td>
                                            <a href="?action=view&id='.$prescription_id.'" class="btn btn-sm btn-outline-info">View</a>
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
        $id=$_GET["id"] ?? null;
        $action=$_GET["action"] ?? null;
        if($action=='prescription-added'){
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Prescription Created Successfully!</h2>
                        <a class="close" href="prescriptions.php">&times;</a>
                        <div class="content">
                        Prescription has been created successfully.<br><br>
                        </div>
                        <!-- START HERE: Bootstrap Button - Success Message -->
                        <div class="d-flex justify-content-center">
                        <a href="prescriptions.php" class="btn btn-primary">OK</a>
                        </div>
                        <!-- END HERE: Bootstrap Button - Success Message -->
                    </center>
            </div>
            </div>
            ';
        }elseif($action=='view'){
            $sqlmain= "select prescriptions.*, patient.pname, patient.pemail, patient.ptel, appointment.apponum from prescriptions inner join patient on prescriptions.patient_id=patient.pid left join appointment on prescriptions.appointment_id=appointment.appoid where prescriptions.prescription_id=$id and prescriptions.doctor_id=$userid";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $pname=$row["pname"];
            $pemail=$row["pemail"];
            $ptel=$row["ptel"];
            $prescription_date=$row["prescription_date"];
            $diagnosis=$row["diagnosis"];
            $follow_up_date=$row["follow_up_date"];
            $follow_up_instructions=$row["follow_up_instructions"];
            
            // Get medicines
            $medicines_sql = "select prescription_medicines.*, medicines.medicine_name, medicines.generic_name from prescription_medicines inner join medicines on prescription_medicines.medicine_id=medicines.medicine_id where prescription_medicines.prescription_id=$id";
            $medicines_result = $database->query($medicines_sql);
            
            echo '
            <div id="popup1" class="overlay" onclick="if(event.target==this) window.location.href=\'prescriptions.php\'">
                    <div class="popup" style="max-height: 90vh; overflow-y: auto;" onclick="event.stopPropagation();">
                    <center>
                        <h2></h2>
                        <a class="close" href="prescriptions.php">&times;</a>
                        <div class="content" style="padding: 20px;">
                            <!-- START HERE: Bootstrap Card Section - View Prescription Details with Scrollbar -->
                            <div class="card">
                                <div class="card-body">
                                <h5 class="card-title mb-4">Prescription Details</h5>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Patient Name:</label>
                                    <p class="mb-0">'.$pname.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email:</label>
                                    <p class="mb-0">'.$pemail.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Telephone:</label>
                                    <p class="mb-0">'.$ptel.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Prescription Date:</label>
                                    <p class="mb-0">'.$prescription_date.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Diagnosis:</label>
                                    <p class="mb-0">'.$diagnosis.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Prescription ID:</label>
                                    <p class="mb-0">#'.$id.'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Medicines:</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Dosage</th>
                                                    <th>Frequency</th>
                                                    <th>Duration</th>
                                                    <th>Instructions</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                            
                                            while($med_row = $medicines_result->fetch_assoc()){
                                                echo '<tr>
                                                    <td>'.$med_row['medicine_name'].'</td>
                                                    <td>'.$med_row['dosage'].'</td>
                                                    <td>'.$med_row['frequency'].'</td>
                                                    <td>'.$med_row['duration'].'</td>
                                                    <td>'.$med_row['instructions'].'</td>
                                                </tr>';
                                            }
                                            
                            echo '          </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Follow-up Date:</label>
                                    <p class="mb-0">'.($follow_up_date ? $follow_up_date : 'Not specified').'</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Follow-up Instructions:</label>
                                    <p class="mb-0">'.($follow_up_instructions ? $follow_up_instructions : 'None').'</p>
                                </div>
                                <div class="d-flex gap-2 justify-content-end mt-4" style="page-break-inside: avoid;">
                                    <button onclick="window.print()" class="btn btn-success" style="display: inline-block;">🖨️ Print</button>
                                    <a href="prescriptions.php" class="btn btn-primary" style="display: inline-block;">OK</a>
                                </div>
                            </div>
                        </div>
                        <!-- END HERE: Bootstrap Card Section - View Prescription Details with Scrollbar -->
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
    }
}

    ?>
    </div>
    <!-- START HERE: Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- END HERE: Bootstrap JS Bundle -->
    <script>
        // Prevent body scroll when popup is open
        if(document.getElementById('popup1')) {
            document.body.classList.add('popup-open');
            document.getElementById('popup1').addEventListener('click', function(e) {
                if(e.target === this) {
                    document.body.classList.remove('popup-open');
                }
            });
        }
    </script>
</body>
</html>

