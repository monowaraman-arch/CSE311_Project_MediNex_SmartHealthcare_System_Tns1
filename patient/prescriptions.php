<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>My Prescriptions</title>
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
    // ========== DATABASE RELATIONS USED: Patient, Prescriptions, Doctor, Prescription_Medicines, Medicines ==========
    // Patient: Get logged-in patient info (pid, pname)
    // Prescriptions: Display patient prescriptions (prescription_id, appointment_id, doctor_id, patient_id, prescription_date, diagnosis)
    // Doctor: Join to get doctor details (docname, docemail, doctel)
    // Prescription_Medicines: Get medicines for each prescription (prescription_id, medicine_id, dosage, frequency, duration, instructions)
    // Medicines: Join to get medicine details (medicine_name, generic_name)
    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

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
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="prescriptions.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Prescriptions</p></div></a>
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
                        <a href="prescriptions.php" class="btn btn-outline-primary">Back</a>
                    </div>
                    <div class="col">
                        <h4 class="mb-0">My Prescriptions</h4>
                        <small class="text-muted">Total: <?php $list110 = $database->query("select * from prescriptions where patient_id=$userid"); echo $list110->num_rows; ?></small>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php date_default_timezone_set('Asia/Dhaka'); $today = date('Y-m-d'); echo $today; ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
                
                <?php

                    $sqlmain= "select prescriptions.*, doctor.docname from prescriptions inner join doctor on prescriptions.doctor_id=doctor.docid where prescriptions.patient_id=$userid order by prescriptions.prescription_date desc";

                ?>
                  
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Prescription ID</th>
                                        <th>Doctor Name</th>
                                        <th>Prescription Date</th>
                                        <th>Diagnosis</th>
                                        <th>Follow-up Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $result= $database->query($sqlmain);
                                    if($result->num_rows==0){
                                        echo '<tr><td colspan="6" class="text-center py-5">
                                            <img src="../img/notfound.svg" width="25%" class="mb-3"><br>
                                            <p class="h5 mb-0">No prescriptions found!</p>
                                        </td></tr>';
                                    }
                                    else{
                                        for ( $x=0; $x<$result->num_rows;$x++){
                                            $row=$result->fetch_assoc();
                                            $prescription_id=$row["prescription_id"];
                                            $docname=$row["docname"];
                                            $prescription_date=$row["prescription_date"];
                                            $diagnosis=$row["diagnosis"];
                                            $follow_up_date=$row["follow_up_date"];
                                            echo '<tr>
                                                <td class="fw-bold">#'.$prescription_id.'</td>
                                                <td class="fw-semibold">'.substr($docname,0,25).'</td>
                                                <td class="text-center">'.$prescription_date.'</td>
                                                <td>'.substr($diagnosis,0,30).'...</td>
                                                <td class="text-center">'.($follow_up_date ? $follow_up_date : 'N/A').'</td>
                                                <td class="text-center">
                                                    <a href="?action=view&id='.$prescription_id.'" class="btn btn-sm btn-outline-primary">View</a>
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
    <?php
    
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        if($action=='view'){
            $sqlmain= "select prescriptions.*, doctor.docname, doctor.docemail, doctor.doctel from prescriptions inner join doctor on prescriptions.doctor_id=doctor.docid where prescriptions.prescription_id=$id and prescriptions.patient_id=$userid";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $docname=$row["docname"];
            $docemail=$row["docemail"];
            $doctel=$row["doctel"];
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
                        <h2>Prescription Details</h2>
                        <a class="close" href="prescriptions.php">&times;</a>
                        <div class="content" style="padding: 20px;">
                            <!-- START HERE: Bootstrap Card Section - View Prescription Details with Scrollbar -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Prescription Details</h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Prescription ID:</label>
                                        <p class="mb-0">#'.$id.'</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Doctor Name:</label>
                                        <p class="mb-0">'.$docname.'</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Doctor Email:</label>
                                        <p class="mb-0">'.$docemail.'</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Doctor Telephone:</label>
                                        <p class="mb-0">'.$doctel.'</p>
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
                                                    
                            echo '              </tbody>
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
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
    }
}

    ?>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
