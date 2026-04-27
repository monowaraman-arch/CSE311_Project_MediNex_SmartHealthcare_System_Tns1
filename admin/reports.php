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
    <title>Reports & Analytics</title>
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
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");

    // ========== DATABASE RELATIONS USED: Patient, Doctor, Appointment, Schedule, Prescriptions, Medicines, Prescription_Medicines ==========
    // Patient: Count total patients, get visit frequency
    // Doctor: Count total doctors, get doctor workload
    // Appointment: Count appointments, join for workload and visit frequency
    // Schedule: Join with Doctor and Appointment for workload analysis
    // Prescriptions: Count total prescriptions, get common diagnoses
    // Medicines: Join with Prescription_Medicines for most prescribed medicines
    // Prescription_Medicines: Count medicine prescriptions
    // Get statistics
    $total_patients = $database->query("SELECT COUNT(*) as count FROM patient")->fetch_assoc()['count'];
    $total_doctors = $database->query("SELECT COUNT(*) as count FROM doctor")->fetch_assoc()['count'];
    $total_appointments = $database->query("SELECT COUNT(*) as count FROM appointment")->fetch_assoc()['count'];
    $total_prescriptions = $database->query("SELECT COUNT(*) as count FROM prescriptions")->fetch_assoc()['count'];
    
    // Doctor workload
    $doctor_workload = $database->query("SELECT doctor.docname, COUNT(appointment.appoid) as appointment_count FROM doctor LEFT JOIN schedule ON doctor.docid=schedule.docid LEFT JOIN appointment ON schedule.scheduleid=appointment.scheduleid GROUP BY doctor.docid ORDER BY appointment_count DESC LIMIT 10");
    
    // Most prescribed medicines
    $top_medicines = $database->query("SELECT medicines.medicine_name, COUNT(prescription_medicines.id) as prescription_count FROM medicines INNER JOIN prescription_medicines ON medicines.medicine_id=prescription_medicines.medicine_id GROUP BY medicines.medicine_id ORDER BY prescription_count DESC LIMIT 10");
    
    // Patient visit frequency
    $visit_frequency = $database->query("SELECT patient.pname, COUNT(appointment.appoid) as visit_count FROM patient LEFT JOIN appointment ON patient.pid=appointment.pid GROUP BY patient.pid ORDER BY visit_count DESC LIMIT 10");
    
    // Common diagnoses
    $common_diagnoses = $database->query("SELECT diagnosis, COUNT(*) as count FROM prescriptions WHERE diagnosis IS NOT NULL AND diagnosis != '' GROUP BY diagnosis ORDER BY count DESC LIMIT 10");

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
                                    <p class="profile-title">Admin</p>
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
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">Doctors</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
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
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="reports.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Reports</p></div></a>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body">
            <div class="container-fluid mt-3">
                <div class="row mb-3">
                    <div class="col-auto">
                        <a href="reports.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h4 class="mb-0">Reports & Analytics</h4>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php date_default_timezone_set('Asia/Dhaka'); echo date('Y-m-d'); ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h2 class="mb-0" style="color: #4CAF50;"><?php echo $total_patients; ?></h2>
                                <p class="mb-0 text-muted">Total Patients</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h2 class="mb-0" style="color: #2196F3;"><?php echo $total_doctors; ?></h2>
                                <p class="mb-0 text-muted">Total Doctors</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h2 class="mb-0" style="color: #FF9800;"><?php echo $total_appointments; ?></h2>
                                <p class="mb-0 text-muted">Total Appointments</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h2 class="mb-0" style="color: #9C27B0;"><?php echo $total_prescriptions; ?></h2>
                                <p class="mb-0 text-muted">Total Prescriptions</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Doctor Workload (Top 10)</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor Name</th>
                                                <th class="text-center">Appointments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while($row = $doctor_workload->fetch_assoc()){
                                                echo '<tr><td>'.$row['docname'].'</td><td class="text-center">'.$row['appointment_count'].'</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Most Prescribed Medicines (Top 10)</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Medicine</th>
                                                <th class="text-center">Prescriptions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while($row = $top_medicines->fetch_assoc()){
                                                echo '<tr><td>'.$row['medicine_name'].'</td><td class="text-center">'.$row['prescription_count'].'</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Patient Visit Frequency (Top 10)</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Patient Name</th>
                                                <th class="text-center">Visits</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while($row = $visit_frequency->fetch_assoc()){
                                                echo '<tr><td>'.$row['pname'].'</td><td class="text-center">'.$row['visit_count'].'</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Common Diagnoses (Top 10)</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Diagnosis</th>
                                                <th class="text-center">Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while($row = $common_diagnoses->fetch_assoc()){
                                                echo '<tr><td>'.substr($row['diagnosis'],0,40).'</td><td class="text-center">'.$row['count'].'</td></tr>';
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
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
