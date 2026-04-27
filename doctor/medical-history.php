<?php
session_start();

if (!isset($_SESSION["user"]) || ($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'd') {
    header("location: ../login.php");
    exit;
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$useremail = $_SESSION["user"];

include("../connection.php");
date_default_timezone_set('Asia/Dhaka');

$doctorStmt = $database->prepare("SELECT docid, docname FROM doctor WHERE docemail=? LIMIT 1");
$doctorStmt->bind_param("s", $useremail);
$doctorStmt->execute();
$doctorResult = $doctorStmt->get_result();
$doctor = $doctorResult->fetch_assoc();

if (!$doctor) {
    header("location: ../logout.php");
    exit;
}

$userid = (int) $doctor["docid"];
$username = $doctor["docname"];
$patientId = (int) ($_POST["patient_id"] ?? ($_GET["patient_id"] ?? 0));

if ($patientId <= 0) {
    header("location: patient.php");
    exit;
}

$patientStmt = $database->prepare(
    "SELECT pid, pname, pemail, ptel, paddress
     FROM patient
     WHERE pid = ?
     LIMIT 1"
);
$patientStmt->bind_param("i", $patientId);
$patientStmt->execute();
$patientResult = $patientStmt->get_result();
$patient = $patientResult->fetch_assoc();

if (!$patient) {
    header("location: patient.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conditionName = trim($_POST["condition_name"] ?? "");
    $diagnosisDate = trim($_POST["diagnosis_date"] ?? "");
    $status = trim($_POST["status"] ?? "");
    $notes = trim($_POST["notes"] ?? "");

    if (isset($_POST["add_history"])) {
        $insertStmt = $database->prepare(
            "INSERT INTO medical_history (patient_id, condition_name, diagnosis_date, status, notes)
             VALUES (?, ?, ?, ?, ?)"
        );
        $insertStmt->bind_param("issss", $patientId, $conditionName, $diagnosisDate, $status, $notes);
        $insertStmt->execute();

        header("location: medical-history.php?patient_id=" . $patientId . "&action=history-added");
        exit;
    }

    if (isset($_POST["edit_history"])) {
        $historyId = (int) ($_POST["history_id"] ?? 0);
        $updateStmt = $database->prepare(
            "UPDATE medical_history
             SET condition_name = ?, diagnosis_date = ?, status = ?, notes = ?
             WHERE history_id = ? AND patient_id = ?"
        );
        $updateStmt->bind_param("ssssii", $conditionName, $diagnosisDate, $status, $notes, $historyId, $patientId);
        $updateStmt->execute();

        header("location: medical-history.php?patient_id=" . $patientId . "&action=history-updated");
        exit;
    }
}

$listStmt = $database->prepare(
    "SELECT * FROM medical_history WHERE patient_id = ? ORDER BY diagnosis_date DESC, history_id DESC"
);
$listStmt->bind_param("i", $patientId);
$listStmt->execute();
$listResult = $listStmt->get_result();
$historyCount = $listResult->num_rows;

$action = $_GET["action"] ?? "";
$selectedHistory = null;

if (($action === "view" || $action === "edit") && isset($_GET["id"])) {
    $historyId = (int) $_GET["id"];
    $historyStmt = $database->prepare(
        "SELECT * FROM medical_history WHERE history_id = ? AND patient_id = ? LIMIT 1"
    );
    $historyStmt->bind_param("ii", $historyId, $patientId);
    $historyStmt->execute();
    $historyResult = $historyStmt->get_result();
    $selectedHistory = $historyResult->fetch_assoc();

    if (!$selectedHistory) {
        $action = "";
    }
}

$today = date('Y-m-d');
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
    <title>Patient Medical History</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
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
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0;margin:0;">
                                    <p class="profile-title"><?= e(substr($username, 0, 13)) ?>..</p>
                                    <p class="profile-subtitle"><?= e(substr($useremail, 0, 22)) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-dashbord">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Appointments</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">My Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-patient menu-active menu-icon-patient-active">
                        <a href="patient.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Patients</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
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
                        <a href="patient.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h4 class="mb-0">Medical History</h4>
                        <small class="text-muted">Manage records for <?= e($patient["pname"]) ?></small>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?= e($today) ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Patient Name</small>
                                        <strong><?= e($patient["pname"]) ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Email</small>
                                        <strong><?= e($patient["pemail"]) ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Phone</small>
                                        <strong><?= e($patient["ptel"]) ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Address</small>
                                        <strong><?= e($patient["paddress"]) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <small class="text-muted d-block">Total Records</small>
                                    <h3 class="mb-0"><?= e($historyCount) ?></h3>
                                </div>
                                <div class="mt-3">
                                    <a href="?patient_id=<?= e($patientId) ?>&action=add-history" class="btn btn-primary w-100">Add Medical History</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Condition Name</th>
                                        <th>Diagnosis Date</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Events</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($historyCount === 0): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <img src="../img/notfound.svg" width="25%" class="mb-3" alt="No medical history"><br>
                                                <p class="h5">No medical history found for this patient.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php while ($history = $listResult->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= e($history["condition_name"]) ?></td>
                                                <td><?= e($history["diagnosis_date"]) ?></td>
                                                <td><?= e($history["status"]) ?></td>
                                                <td><?= e(strlen((string) $history["notes"]) > 40 ? substr((string) $history["notes"], 0, 40) . '...' : ($history["notes"] ?: 'No notes')) ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="?patient_id=<?= e($patientId) ?>&action=view&id=<?= e($history["history_id"]) ?>" class="btn btn-sm btn-outline-info">View</a>
                                                        <a href="?patient_id=<?= e($patientId) ?>&action=edit&id=<?= e($history["history_id"]) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($action === 'add-history'): ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Add Medical History</h2>
                    <a class="close" href="medical-history.php?patient_id=<?= e($patientId) ?>">&times;</a>
                    <div class="content">
                        <form action="" method="POST">
                            <input type="hidden" name="patient_id" value="<?= e($patientId) ?>">
                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                <tr>
                                    <td>
                                        <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Add Medical History.</p><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="condition_name" class="form-label">Condition Name: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="text" name="condition_name" class="input-text" placeholder="e.g. Diabetes, Hypertension" required><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="diagnosis_date" class="form-label">Diagnosis Date: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="date" name="diagnosis_date" class="input-text" max="<?= e($today) ?>" required><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="status" class="form-label">Status: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <select name="status" class="input-text" required>
                                            <option value="Active">Active</option>
                                            <option value="Resolved">Resolved</option>
                                            <option value="Chronic">Chronic</option>
                                            <option value="Under Treatment">Under Treatment</option>
                                        </select><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="notes" class="form-label">Notes (Optional): </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <textarea name="notes" class="input-text" rows="3" placeholder="Additional notes about this condition"></textarea><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="submit" name="add_history" value="Add History" class="login-btn btn-primary btn">
                                        <a href="medical-history.php?patient_id=<?= e($patientId) ?>"><input type="button" value="Cancel" class="login-btn btn-primary-soft btn"></a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </center>
                <br><br>
            </div>
        </div>
    <?php elseif ($action === 'edit' && $selectedHistory): ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Edit Medical History</h2>
                    <a class="close" href="medical-history.php?patient_id=<?= e($patientId) ?>">&times;</a>
                    <div class="content">
                        <form action="" method="POST">
                            <input type="hidden" name="patient_id" value="<?= e($patientId) ?>">
                            <input type="hidden" name="history_id" value="<?= e($selectedHistory["history_id"]) ?>">
                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                <tr>
                                    <td>
                                        <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Edit Medical History.</p><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="condition_name" class="form-label">Condition Name: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="text" name="condition_name" class="input-text" value="<?= e($selectedHistory["condition_name"]) ?>" required><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="diagnosis_date" class="form-label">Diagnosis Date: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="date" name="diagnosis_date" class="input-text" max="<?= e($today) ?>" value="<?= e($selectedHistory["diagnosis_date"]) ?>" required><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="status" class="form-label">Status: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <select name="status" class="input-text" required>
                                            <?php
                                            $statuses = ["Active", "Resolved", "Chronic", "Under Treatment"];
                                            foreach ($statuses as $statusOption) {
                                                $selected = $selectedHistory["status"] === $statusOption ? 'selected' : '';
                                                echo '<option value="' . e($statusOption) . '" ' . $selected . '>' . e($statusOption) . '</option>';
                                            }
                                            ?>
                                        </select><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="notes" class="form-label">Notes (Optional): </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <textarea name="notes" class="input-text" rows="3" placeholder="Additional notes about this condition"><?= e($selectedHistory["notes"]) ?></textarea><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="submit" name="edit_history" value="Save Changes" class="login-btn btn-primary btn">
                                        <a href="medical-history.php?patient_id=<?= e($patientId) ?>"><input type="button" value="Cancel" class="login-btn btn-primary-soft btn"></a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </center>
                <br><br>
            </div>
        </div>
    <?php elseif ($action === 'view' && $selectedHistory): ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Medical History Details</h2>
                    <a class="close" href="medical-history.php?patient_id=<?= e($patientId) ?>">&times;</a>
                    <div class="content">
                        <div style="display: flex;justify-content: center;">
                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label class="form-label">Condition Name: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <?= e($selectedHistory["condition_name"]) ?><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label class="form-label">Diagnosis Date: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <?= e($selectedHistory["diagnosis_date"]) ?><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label class="form-label">Status: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <?= e($selectedHistory["status"]) ?><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label class="form-label">Notes: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <?= e($selectedHistory["notes"] ?: "No notes") ?><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a href="medical-history.php?patient_id=<?= e($patientId) ?>"><input type="button" value="OK" class="login-btn btn-primary-soft btn"></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </center>
                <br><br>
            </div>
        </div>
    <?php elseif ($action === 'history-added' || $action === 'history-updated'): ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <br><br>
                    <h2><?= $action === 'history-added' ? 'Medical History Added Successfully.' : 'Medical History Updated Successfully.' ?></h2>
                    <a class="close" href="medical-history.php?patient_id=<?= e($patientId) ?>">&times;</a>
                    <div class="content">
                        <?= $action === 'history-added' ? 'The medical history has been recorded.' : 'The medical history has been updated.' ?><br><br>
                    </div>
                    <div style="display: flex;justify-content: center;">
                        <a href="medical-history.php?patient_id=<?= e($patientId) ?>" class="non-style-link">
                            <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                <font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font>
                            </button>
                        </a>
                        <br><br><br><br>
                    </div>
                </center>
            </div>
        </div>
    <?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
