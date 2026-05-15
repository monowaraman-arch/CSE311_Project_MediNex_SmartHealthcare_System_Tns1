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
    <link rel="stylesheet" href="../css/doctor-appointments.css">
        
    <title>Appointments</title>
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
                    <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Appointments</p></div></a>
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
                        <a href="appointment.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h5 class="mb-0">Appointment Manager</h5>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php 
                            date_default_timezone_set('Asia/Dhaka');
                            $today = date('Y-m-d');
                            echo $today;
                            $list110 = $database->query("select * from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  doctor.docid=$userid ");
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
                        <h6>My Appointments (<?php echo $list110->num_rows; ?>)</h6>
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
                <!-- END HERE: Bootstrap Filter Section - Date Filter -->
                
                <?php


                    $sqlmain= "select appointment.appoid,schedule.scheduleid,schedule.title,doctor.docname,patient.pname,patient.pid,schedule.scheduledate,schedule.scheduletime,appointment.apponum,appointment.appodate from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  doctor.docid=$userid ";

                    if($_POST){
                        //print_r($_POST);
                        


                        
                        if(!empty($_POST["sheduledate"])){
                            $sheduledate=$_POST["sheduledate"];
                            $sqlmain.=" and schedule.scheduledate='$sheduledate' ";
                        };

                        

                        //echo $sqlmain;

                    }


                ?>
                <!-- START HERE: Bootstrap Table Section - Appointments List -->
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Appointment Number</th>
                                        <th>Session Title</th>
                                        <th>Session Date & Time</th>
                                        <th>Appointment Date</th>
                                        <th>Events</th>
                                    </tr>
                                </thead>
                                <tbody>
                <!-- END HERE: Bootstrap Table Section - Appointments List -->
                        
                            <?php

                                
                                $result= $database->query($sqlmain);

                                if($result->num_rows==0){
                                    echo '<tr><td colspan="6" class="text-center py-5">
                                        <img src="../img/notfound.svg" width="25%" class="mb-3"><br>
                                        <p class="h5">We couldn\'t find anything related to your keywords!</p>
                                        <a href="appointment.php" class="btn btn-primary mt-3">Show all Appointments</a>
                                    </td></tr>';
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
                                    $row=$result->fetch_assoc();
                                    $appoid=$row["appoid"];
                                    $scheduleid=$row["scheduleid"];
                                    $title=$row["title"];
                                    $docname=$row["docname"];
                                    $scheduledate=$row["scheduledate"];
                                    $scheduletime=$row["scheduletime"];
                                    $pname=$row["pname"];
                                    $apponum=$row["apponum"];
                                    $appodate=$row["appodate"];
                                    echo '<tr>
                                        <td class="fw-bold">'.substr($pname,0,25).'</td>
                                        <td class="text-center fs-5 fw-medium text-primary">'.$apponum.'</td>
                                        <td>'.substr($title,0,15).'</td>
                                        <td class="text-center">'.substr($scheduledate,0,10).' @'.substr($scheduletime,0,5).'</td>
                                        <td class="text-center">'.$appodate.'</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="?action=create-prescription&appointment_id='.$appoid.'&patient_id='.$row["pid"].'" class="btn btn-sm btn-outline-primary">Prescription</a>
                                                <a href="?action=create-summary&appointment_id='.$appoid.'" class="btn btn-sm btn-outline-info">Summary</a>
                                                <a href="?action=drop&id='.$appoid.'&name='.$pname.'&session='.$title.'&apponum='.$apponum.'" class="btn btn-sm btn-outline-danger">Cancel</a>
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
    function doctor_appointment_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
    
    if($_GET){
        $id=isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $action=$_GET["action"] ?? "";
        if($action=='add-session'){

            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    
                    
                        <a class="close" href="schedule.php">&times;</a> 
                        <div style="display: flex;justify-content: center;">
                        <div class="abc">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        <tr>
                                <td class="label-td" colspan="2">'.
                                   ""
                                
                                .'</td>
                            </tr>

                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Add New Session.</p><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                <form action="add-session.php" method="POST" class="add-new-form">
                                    <label for="title" class="form-label">Session Title : </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="title" class="input-text" placeholder="Name of this Session" required><br>
                                </td>
                            </tr>
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="docid" class="form-label">Select Doctor: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <select name="docid" id="" class="box" >
                                    <option value="" disabled selected hidden>Choose Doctor Name from the list</option><br/>';
                                        
        
                                        $list11 = $database->query("select  * from  doctor;");
        
                                        for ($y=0;$y<$list11->num_rows;$y++){
                                            $row00=$list11->fetch_assoc();
                                            $sn=$row00["docname"];
                                            $id00=$row00["docid"];
                                            echo "<option value=".$id00.">$sn</option><br/>";
                                        };
        
        
        
                                        
                        echo     '       </select><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nop" class="form-label">Number of Patients/Appointment Numbers : </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="number" name="nop" class="input-text" min="0"  placeholder="The final appointment number for this session depends on this number" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="date" class="form-label">Session Date: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="date" name="date" class="input-text" min="'.date('Y-m-d').'" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="time" class="form-label">Schedule Time: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="time" name="time" class="input-text" placeholder="Time" required><br>
                                </td>
                            </tr>
                           
                            <tr>
                                <td colspan="2">
                                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                
                                    <input type="submit" value="Place this Session" class="login-btn btn-primary btn" name="shedulesubmit">
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
        }elseif($action=='session-added'){
            $titleget=$_GET["title"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Session Placed.</h2>
                        <a class="close" href="schedule.php">&times;</a>
                        <div class="content">
                        '.substr($titleget,0,40).' was scheduled.<br><br>
                            
                        </div>
                        <div class="d-flex justify-content-center">
                        <a href="schedule.php" class="btn btn-primary">OK</a>
                        </div>
                    </center>
            </div>
            </div>
            ';
        }elseif($action=='drop'){
            $nameget=$_GET["name"];
            $session=$_GET["session"];
            $apponum=$_GET["apponum"];
            echo '
            <!-- START HERE: Bootstrap Popup Section - Delete Confirmation -->
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br><br>
                            Patient Name: &nbsp;<b>'.substr($nameget,0,40).'</b><br>
                            Appointment number &nbsp; : <b>'.substr($apponum,0,40).'</b><br><br>
                            
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                        <a href="delete-appointment.php?id='.$id.'" class="btn btn-primary">Yes</a>
                        <a href="appointment.php" class="btn btn-secondary">No</a>
                        </div>
                    </center>
            </div>
            </div>
            <!-- END HERE: Bootstrap Popup Section - Delete Confirmation -->
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
                        <!-- START HERE: Bootstrap View Details with Scrollbar -->
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
                        <!-- END HERE: Bootstrap View Details with Scrollbar -->
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
        }elseif($action=='create-prescription'){
            $appointment_id = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : 0;
            $patient_id = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
            
            $patient_sql = "select * from patient where pid=$patient_id";
            $patient_result = $database->query($patient_sql);
            $patient_data = $patient_result ? $patient_result->fetch_assoc() : null;
            $patient_name = $patient_data['pname'] ?? 'Selected Patient';
            
            $medicines_list = $database->query("select * from medicines order by medicine_name");
            $medicines_array = array();
            while($med = $medicines_list->fetch_assoc()){
                $medicines_array[] = array(
                    'id' => $med['medicine_id'],
                    'name' => $med['medicine_name'],
                    'strength' => $med['strength']
                );
            }
            
            date_default_timezone_set('Asia/Dhaka');
            $today = date('Y-m-d');
            ?>
            <div id="popup1" class="overlay doctor-appointment-modal-overlay">
                <div class="popup doctor-appointment-modal doctor-prescription-modal">
                    <a class="close doctor-appointment-modal-close" href="appointment.php" aria-label="Close">&times;</a>
                    <div class="doctor-appointment-modal-header">
                        <p>My Appointments</p>
                        <h2>Create Prescription</h2>
                    </div>
                    <form action="create-prescription.php" method="POST" id="prescription-form" class="doctor-appointment-form">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                        <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                        <div class="doctor-appointment-patient-card">
                            <span><i class="bi bi-person-heart"></i></span>
                            <div>
                                <small>Patient</small>
                                <strong><?php echo doctor_appointment_h($patient_name); ?></strong>
                            </div>
                        </div>
                        <div class="doctor-appointment-form-grid">
                            <div class="doctor-appointment-field">
                                <label for="prescription-date">Prescription Date</label>
                                <input id="prescription-date" type="date" name="prescription_date" class="form-control" value="<?php echo doctor_appointment_h($today); ?>" required>
                            </div>
                            <div class="doctor-appointment-field doctor-appointment-field-wide">
                                <label for="prescription-diagnosis">Diagnosis</label>
                                <textarea id="prescription-diagnosis" name="diagnosis" class="form-control" rows="4" placeholder="Enter diagnosis" required></textarea>
                            </div>
                        </div>
                        <div class="doctor-appointment-section-title">
                            <h3>Medicines</h3>
                            <button type="button" onclick="addMedicineRow()" class="btn btn-outline-primary btn-sm"><i class="bi bi-plus-circle"></i> Add Medicine</button>
                        </div>
                        <div id="medicines-container" class="doctor-medicines-table-wrap">
                            <table class="table doctor-medicines-table">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Dosage</th>
                                        <th>Frequency</th>
                                        <th>Duration</th>
                                        <th>Instructions</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="medicines[0][medicine_id]" class="form-select form-select-sm" required>
                                                <option value="">Select Medicine</option>
                                                <?php foreach($medicines_array as $med){ ?>
                                                    <option value="<?php echo doctor_appointment_h($med['id']); ?>"><?php echo doctor_appointment_h($med['name'].' ('.$med['strength'].')'); ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><input type="text" name="medicines[0][dosage]" class="form-control form-control-sm" placeholder="1 tablet" required></td>
                                        <td><input type="text" name="medicines[0][frequency]" class="form-control form-control-sm" placeholder="Twice daily" required></td>
                                        <td><input type="text" name="medicines[0][duration]" class="form-control form-control-sm" placeholder="7 days" required></td>
                                        <td><input type="text" name="medicines[0][instructions]" class="form-control form-control-sm" placeholder="After meal"></td>
                                        <td><button type="button" class="btn btn-light btn-sm doctor-row-remove" onclick="removeMedicineRow(this)" aria-label="Remove medicine" disabled><i class="bi bi-x"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="doctor-appointment-form-grid doctor-appointment-followup-grid">
                            <div class="doctor-appointment-field">
                                <label for="follow-up-date">Follow-up Date <span>Optional</span></label>
                                <input id="follow-up-date" type="date" name="follow_up_date" class="form-control">
                            </div>
                            <div class="doctor-appointment-field">
                                <label for="follow-up-instructions">Follow-up Instructions <span>Optional</span></label>
                                <textarea id="follow-up-instructions" name="follow_up_instructions" class="form-control" rows="3" placeholder="Follow-up instructions"></textarea>
                            </div>
                        </div>
                        <div class="doctor-appointment-form-actions">
                            <a href="appointment.php" class="btn btn-secondary">Cancel</a>
                            <input type="submit" value="Create Prescription" class="btn btn-primary">
                        </div>
                    </form>
                    <script>
                    let medicineCount = 1;
                    const medicinesData = <?php echo json_encode($medicines_array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

                    function escapeHtml(value) {
                        return String(value)
                            .replace(/&/g, "&amp;")
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;")
                            .replace(/"/g, "&quot;")
                            .replace(/'/g, "&#039;");
                    }

                    function medicineOptionsHtml() {
                        let optionsHtml = '<option value="">Select Medicine</option>';
                        medicinesData.forEach(function(med) {
                            optionsHtml += '<option value="' + escapeHtml(med.id) + '">' + escapeHtml(med.name) + ' (' + escapeHtml(med.strength) + ')</option>';
                        });
                        return optionsHtml;
                    }

                    function addMedicineRow() {
                        const table = document.querySelector("#medicines-container table tbody");
                        const newRow = table.insertRow(-1);
                        newRow.innerHTML = '<td><select name="medicines[' + medicineCount + '][medicine_id]" class="form-select form-select-sm" required>' + medicineOptionsHtml() + '</select></td><td><input type="text" name="medicines[' + medicineCount + '][dosage]" class="form-control form-control-sm" placeholder="1 tablet" required></td><td><input type="text" name="medicines[' + medicineCount + '][frequency]" class="form-control form-control-sm" placeholder="Twice daily" required></td><td><input type="text" name="medicines[' + medicineCount + '][duration]" class="form-control form-control-sm" placeholder="7 days" required></td><td><input type="text" name="medicines[' + medicineCount + '][instructions]" class="form-control form-control-sm" placeholder="After meal"></td><td><button type="button" class="btn btn-light btn-sm doctor-row-remove" onclick="removeMedicineRow(this)" aria-label="Remove medicine"><i class="bi bi-x"></i></button></td>';
                        medicineCount++;
                    }

                    function removeMedicineRow(button) {
                        const tbody = button.closest("tbody");
                        if (tbody.rows.length > 1) {
                            button.closest("tr").remove();
                        }
                    }
                    </script>
                </div>
            </div>
        <?php }elseif($action=='create-summary'){
            $appointment_id = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : 0;
            
            $app_sql = "select appointment.*, patient.pname, schedule.title from appointment inner join patient on appointment.pid=patient.pid inner join schedule on appointment.scheduleid=schedule.scheduleid where appointment.appoid=$appointment_id and schedule.docid=$userid";
            $app_result = $database->query($app_sql);
            if($app_result && $app_result->num_rows==1){
                $app_data = $app_result->fetch_assoc();
                $patient_name = $app_data['pname'];
                $title = $app_data['title'];
                ?>
                <div id="popup1" class="overlay doctor-appointment-modal-overlay">
                    <div class="popup doctor-appointment-modal doctor-summary-modal">
                        <a class="close doctor-appointment-modal-close" href="appointment.php" aria-label="Close">&times;</a>
                        <div class="doctor-appointment-modal-header">
                            <p>My Appointments</p>
                            <h2>Create Visit Summary</h2>
                        </div>
                        <form action="visit-summary.php" method="POST" class="doctor-appointment-form">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                            <div class="doctor-appointment-patient-card">
                                <span><i class="bi bi-clipboard2-pulse"></i></span>
                                <div>
                                    <small>Patient</small>
                                    <strong><?php echo doctor_appointment_h($patient_name); ?></strong>
                                    <em><?php echo doctor_appointment_h($title); ?></em>
                                </div>
                            </div>
                            <div class="doctor-appointment-form-grid">
                                <div class="doctor-appointment-field doctor-appointment-field-wide">
                                    <label for="chief-complaint">Chief Complaint</label>
                                    <textarea id="chief-complaint" name="chief_complaint" class="form-control" rows="3" placeholder="Main complaint" required></textarea>
                                </div>
                                <div class="doctor-appointment-field doctor-appointment-field-wide">
                                    <label for="examination-findings">Examination Findings <span>Optional</span></label>
                                    <textarea id="examination-findings" name="examination_findings" class="form-control" rows="4" placeholder="Physical examination findings"></textarea>
                                </div>
                                <div class="doctor-appointment-field doctor-appointment-field-wide">
                                    <label for="summary-diagnosis">Diagnosis</label>
                                    <textarea id="summary-diagnosis" name="diagnosis" class="form-control" rows="3" placeholder="Diagnosis" required></textarea>
                                </div>
                                <div class="doctor-appointment-field">
                                    <label for="treatment-plan">Treatment Plan <span>Optional</span></label>
                                    <textarea id="treatment-plan" name="treatment_plan" class="form-control" rows="3" placeholder="Treatment plan"></textarea>
                                </div>
                                <div class="doctor-appointment-field">
                                    <label for="summary-notes">Additional Notes <span>Optional</span></label>
                                    <textarea id="summary-notes" name="notes" class="form-control" rows="3" placeholder="Additional notes"></textarea>
                                </div>
                            </div>
                            <div class="doctor-appointment-form-actions">
                                <a href="appointment.php" class="btn btn-secondary">Cancel</a>
                                <input type="submit" name="create_summary" value="Create Summary" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
        }elseif($action=='summary-created'){
            echo '
            <!-- START HERE: Bootstrap Popup Section - Summary Created Success -->
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Visit Summary Created Successfully.</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                        Visit summary has been recorded.<br><br>
                        </div>
                        <div class="d-flex justify-content-center">
                        <a href="appointment.php" class="btn btn-primary">OK</a>
                        </div>
                    </center>
            </div>
            </div>
            <!-- END HERE: Bootstrap Popup Section - Summary Created Success -->
            ';
    }
}

    ?>
    </div>
    <!-- START HERE: Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- END HERE: Bootstrap JS Bundle -->
</body>
</html>
