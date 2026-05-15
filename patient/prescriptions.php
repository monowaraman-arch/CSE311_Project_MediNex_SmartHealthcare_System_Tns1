<?php
session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" || $_SESSION["usertype"] != "p") {
        header("location: ../login.php");
        exit;
    }
    $useremail = $_SESSION["user"];
} else {
    header("location: ../login.php");
    exit;
}

include("../connection.php");

if (!function_exists("e")) {
    function e($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
    }
}

function displayDate($value, $fallback = "Not scheduled") {
    $value = trim((string) $value);
    if ($value === "" || $value === "0000-00-00") {
        return $fallback;
    }
    return $value;
}

function shortText($value, $length = 30, $fallback = "Not specified") {
    $value = trim((string) $value);
    if ($value === "") {
        return $fallback;
    }
    if (strlen($value) <= $length) {
        return $value;
    }
    return substr($value, 0, $length) . "...";
}

$sqlmain = "select * from patient where pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$userrow = $stmt->get_result();
$userfetch = $userrow->fetch_assoc();

if (!$userfetch) {
    header("location: ../logout.php");
    exit;
}

$userid = (int) $userfetch["pid"];
$username = $userfetch["pname"];

$totalStmt = $database->prepare("select count(*) as total from prescriptions where patient_id=?");
$totalStmt->bind_param("i", $userid);
$totalStmt->execute();
$totalPrescriptions = (int) ($totalStmt->get_result()->fetch_assoc()["total"] ?? 0);
?>
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
    <link rel="stylesheet" href="../css/patient-dashboard-theme.css">
        
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
            .close, .prescription-close, .prescription-modal-footer, button, .btn {
                display: none !important;
            }
            .content {
                max-height: none !important;
                overflow: visible !important;
            }
            .prescription-record {
                max-height: none !important;
                padding: 0 !important;
                background: white !important;
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
        .prescription-page {
            background: #f7fafc !important;
        }
        .prescription-page::before {
            background: #f7fafc !important;
        }
        .prescription-toolbar {
            background: #ffffff;
            border: 1px solid #e6edf3;
            border-radius: 8px;
            padding: 18px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }
        .prescription-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #0f766e;
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 999px;
            padding: 6px 12px;
            font-weight: 700;
        }
        .prescription-table-wrap {
            background: #ffffff;
            border: 1px solid #e6edf3;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }
        .prescription-table {
            margin-bottom: 0;
        }
        .prescription-table thead th {
            background: #f8fafc !important;
            color: #475569 !important;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0;
            white-space: nowrap;
        }
        .prescription-table tbody td {
            padding: 18px 16px;
            vertical-align: middle;
        }
        .rx-id {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 56px;
            color: #0f766e;
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 6px 10px;
            font-weight: 800;
        }
        .prescription-overlay {
            background: rgba(15, 23, 42, 0.72);
            padding: 24px;
        }
        .prescription-modal {
            width: min(980px, 100%);
            max-width: 100%;
            max-height: calc(100vh - 48px);
            margin: 0 auto;
            padding: 0;
            border: 1px solid #dbe7ef;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
        }
        .prescription-modal .content {
            max-height: none;
            overflow: visible;
        }
        .prescription-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            padding: 24px 28px;
            border-bottom: 1px solid #e6edf3;
            background: #ffffff;
        }
        .prescription-modal-header h2 {
            margin: 2px 0 0;
            color: #0f172a;
            font-size: clamp(26px, 4vw, 40px);
            line-height: 1.1;
            letter-spacing: 0;
        }
        .modal-kicker {
            margin: 0;
            color: #0f766e;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0;
        }
        .prescription-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            color: #334155;
            background: #f8fafc;
            border: 1px solid #dbe7ef;
            border-radius: 8px;
            text-decoration: none;
            flex: 0 0 auto;
        }
        .prescription-close:hover {
            color: #ffffff;
            background: #0f766e;
            border-color: #0f766e;
        }
        .prescription-record {
            max-height: calc(100vh - 174px);
            overflow: auto;
            padding: 28px;
            background: #f8fafc;
        }
        .record-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }
        .record-stat,
        .record-panel {
            background: #ffffff;
            border: 1px solid #e6edf3;
            border-radius: 8px;
            padding: 18px;
        }
        .record-stat span,
        .record-label {
            display: block;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0;
            margin-bottom: 8px;
        }
        .record-stat strong {
            display: block;
            color: #0f172a;
            font-size: 20px;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }
        .record-section {
            margin-top: 18px;
        }
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #0f172a;
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 12px;
        }
        .section-title i {
            color: #0f766e;
        }
        .diagnosis-text,
        .followup-text {
            color: #263445;
            font-size: 16px;
            line-height: 1.65;
            margin: 0;
            white-space: pre-wrap;
        }
        .medicine-table {
            margin-bottom: 0;
        }
        .medicine-table thead th {
            color: #475569 !important;
            background: #f8fafc !important;
            border-bottom: 1px solid #dbe7ef !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0;
        }
        .medicine-table td,
        .medicine-table th {
            padding: 14px !important;
        }
        .medicine-name {
            color: #0f172a;
            font-weight: 800;
        }
        .medicine-generic {
            display: block;
            color: #64748b;
            font-size: 13px;
            margin-top: 2px;
        }
        .prescription-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 18px 28px;
            background: #ffffff;
            border-top: 1px solid #e6edf3;
        }
        @media (max-width: 900px) {
            .record-grid {
                grid-template-columns: 1fr;
            }
            .prescription-record {
                padding: 18px;
            }
        }
        @media (max-width: 700px) {
            .prescription-overlay {
                padding: 10px;
            }
            .prescription-modal-header {
                padding: 18px;
            }
            .prescription-modal-footer {
                flex-direction: column;
                padding: 16px 18px;
            }
            .prescription-modal-footer .btn {
                width: 100%;
            }
            .prescription-table thead {
                display: none;
            }
            .prescription-table tbody tr {
                display: block;
                border-bottom: 1px solid #e6edf3;
                padding: 12px;
            }
            .prescription-table tbody td {
                display: flex;
                justify-content: space-between;
                gap: 16px;
                padding: 10px 0;
                border: 0;
                text-align: right !important;
            }
            .prescription-table tbody td::before {
                content: attr(data-label);
                color: #64748b;
                font-weight: 800;
                text-align: left;
            }
        }
</style>
</head>
<body>
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
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="medical-history.php" class="non-style-link-menu"><div><p class="menu-text">Medical History</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body prescription-page">
            <div class="container-fluid mt-3">
                <div class="row mb-4 align-items-center prescription-toolbar">
                    <div class="col-auto">
                        <a href="prescriptions.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h4 class="mb-0">My Prescriptions</h4>
                        <span class="prescription-count mt-2"><i class="bi bi-file-medical"></i><?php echo $totalPrescriptions; ?> total</span>
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

                    // Fetch prescriptions for the logged-in patient by joining with doctor table to get doctor details, ordered by prescription date descending
                    $sqlmain= "select prescriptions.*, doctor.docname 
                               from prescriptions 
                               inner join doctor 
                               on prescriptions.doctor_id=doctor.docid 
                               where prescriptions.patient_id=? 
                               order by prescriptions.prescription_date desc";
                    $prescriptionStmt = $database->prepare($sqlmain);
                    $prescriptionStmt->bind_param("i", $userid);
                    $prescriptionStmt->execute();
                    $result = $prescriptionStmt->get_result();

                ?>
                  
                <div class="row">
                    <div class="col">
                        <div class="table-responsive prescription-table-wrap">
                            <table class="table table-hover align-middle prescription-table">
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
                                                <td data-label="Prescription ID"><span class="rx-id">#'.e($prescription_id).'</span></td>
                                                <td data-label="Doctor" class="fw-semibold">'.e(shortText($docname,25)).'</td>
                                                <td data-label="Prescription Date" class="text-center">'.e(displayDate($prescription_date, 'Not dated')).'</td>
                                                <td data-label="Diagnosis">'.e(shortText($diagnosis,42)).'</td>
                                                <td data-label="Follow-up Date" class="text-center">'.e(displayDate($follow_up_date)).'</td>
                                                <td data-label="Actions" class="text-center">
                                                    <a href="?action=view&id='.e($prescription_id).'" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
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
    
    $action = $_GET["action"] ?? "";
    $id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

    if ($action === "view" && $id > 0) {
        $detailStmt = $database->prepare(
            "select prescriptions.*, doctor.docname, doctor.docemail, doctor.doctel
             from prescriptions
             inner join doctor on prescriptions.doctor_id=doctor.docid
             where prescriptions.prescription_id=? and prescriptions.patient_id=?
             limit 1"
        );
        $detailStmt->bind_param("ii", $id, $userid);
        $detailStmt->execute();
        $detailResult = $detailStmt->get_result();
        $row = $detailResult->fetch_assoc();

        if ($row) {
            $medicineStmt = $database->prepare(
                "select prescription_medicines.*, medicines.medicine_name, medicines.generic_name
                 from prescription_medicines
                 inner join medicines on prescription_medicines.medicine_id=medicines.medicine_id
                 where prescription_medicines.prescription_id=?
                 order by prescription_medicines.id"
            );
            $medicineStmt->bind_param("i", $id);
            $medicineStmt->execute();
            $medicines_result = $medicineStmt->get_result();
            ?>
            <div id="popup1" class="overlay prescription-overlay" onclick="if(event.target==this) window.location.href='prescriptions.php'">
                <div class="popup prescription-modal" onclick="event.stopPropagation();">
                    <div class="prescription-modal-header">
                        <div>
                            <p class="modal-kicker">Prescription #<?php echo e($id); ?></p>
                            <h2>Prescription Details</h2>
                        </div>
                        <a class="prescription-close" href="prescriptions.php" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>

                    <div class="content prescription-record">
                        <div class="record-grid">
                            <div class="record-stat">
                                <span>Doctor</span>
                                <strong><?php echo e($row["docname"]); ?></strong>
                            </div>
                            <div class="record-stat">
                                <span>Prescription Date</span>
                                <strong><?php echo e(displayDate($row["prescription_date"], "Not dated")); ?></strong>
                            </div>
                            <div class="record-stat">
                                <span>Follow-up Date</span>
                                <strong><?php echo e(displayDate($row["follow_up_date"])); ?></strong>
                            </div>
                        </div>

                        <div class="record-panel">
                            <h3 class="section-title"><i class="bi bi-person-vcard"></i>Doctor Contact</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <span class="record-label">Email</span>
                                    <p class="mb-0"><?php echo e($row["docemail"]); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <span class="record-label">Telephone</span>
                                    <p class="mb-0"><?php echo e($row["doctel"]); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="record-panel record-section">
                            <h3 class="section-title"><i class="bi bi-clipboard2-pulse"></i>Diagnosis</h3>
                            <p class="diagnosis-text"><?php echo e($row["diagnosis"] ?: "Not specified"); ?></p>
                        </div>

                        <div class="record-panel record-section">
                            <h3 class="section-title"><i class="bi bi-capsule"></i>Medicines</h3>
                            <div class="table-responsive">
                                <table class="table medicine-table">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Dosage</th>
                                            <th>Frequency</th>
                                            <th>Duration</th>
                                            <th>Instructions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($medicines_result->num_rows === 0): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">No medicines were added.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php while ($med_row = $medicines_result->fetch_assoc()): ?>
                                                <tr>
                                                    <td>
                                                        <span class="medicine-name"><?php echo e($med_row["medicine_name"]); ?></span>
                                                        <span class="medicine-generic"><?php echo e($med_row["generic_name"] ?: "Generic not listed"); ?></span>
                                                    </td>
                                                    <td><?php echo e($med_row["dosage"] ?: "-"); ?></td>
                                                    <td><?php echo e($med_row["frequency"] ?: "-"); ?></td>
                                                    <td><?php echo e($med_row["duration"] ?: "-"); ?></td>
                                                    <td><?php echo e($med_row["instructions"] ?: "No extra instructions"); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="record-panel record-section">
                            <h3 class="section-title"><i class="bi bi-calendar2-check"></i>Follow-up Instructions</h3>
                            <p class="followup-text"><?php echo e($row["follow_up_instructions"] ?: "None"); ?></p>
                        </div>
                    </div>

                    <div class="prescription-modal-footer">
                        <button onclick="window.print()" class="btn btn-success"><i class="bi bi-printer"></i> Print</button>
                        <a href="prescriptions.php" class="btn btn-primary">Done</a>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    /*
        $id=$_GET["id"];
        $action=$_GET["action"];
        if($action=='view'){
            // Fetch prescription details by joining with doctor table to get doctor info, 
            // and then fetch associated medicines by joining prescription_medicines with medicines table 
            // to get medicine details for the prescription ID passed in URL, ensuring it belongs to the logged-in patient
            $sqlmain= "select prescriptions.*, doctor.docname, doctor.docemail, doctor.doctel 
                       from prescriptions 
                       inner join doctor 
                       on prescriptions.doctor_id=doctor.docid 
                       where prescriptions.prescription_id=$id and prescriptions.patient_id=$userid";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $docname=$row["docname"];
            $docemail=$row["docemail"];
            $doctel=$row["doctel"];
            $prescription_date=$row["prescription_date"];
            $diagnosis=$row["diagnosis"];
            $follow_up_date=$row["follow_up_date"];
            $follow_up_instructions=$row["follow_up_instructions"];
            
            // Fetch medicines for the prescription by joining prescription_medicines with medicines table to get medicine details, filtering by prescription_id
            $medicines_sql = "select prescription_medicines.*, medicines.medicine_name, medicines.generic_name 
                              from prescription_medicines 
                              inner join medicines 
                              on prescription_medicines.medicine_id=medicines.medicine_id 
                              where prescription_medicines.prescription_id=$id";
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
*/

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
