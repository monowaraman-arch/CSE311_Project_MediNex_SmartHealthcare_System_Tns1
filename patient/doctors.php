<?php
session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
        exit;
    }
    $useremail = $_SESSION["user"];
} else {
    header("location: ../login.php");
    exit;
}

include("../connection.php");
include("../includes/auth-helper.php");

if (!function_exists("h")) {
    function h($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
    }
}

$patientStmt = $database->prepare("select * from patient where pemail=?");
$patientStmt->bind_param("s", $useremail);
$patientStmt->execute();
$userrow = $patientStmt->get_result();
$userfetch = $userrow->fetch_assoc();

if (!$userfetch) {
    header("location: ../logout.php");
    exit;
}

$username = $userfetch["pname"];
$searchkeyword = trim($_POST["search"] ?? "");

$doctorOptions = $database->query("select docname, docemail from doctor order by docname asc");

if ($searchkeyword !== "") {
    $likeKeyword = "%" . $searchkeyword . "%";
    $doctorStmt = $database->prepare(
        "select doctor.docid, doctor.docname, doctor.docemail, specialties.sname
         from doctor
         left join specialties on doctor.specialties=specialties.id
         where doctor.docemail=? or doctor.docname=? or doctor.docname like ? or doctor.docemail like ?
         order by doctor.docid desc"
    );
    $doctorStmt->bind_param("ssss", $searchkeyword, $searchkeyword, $likeKeyword, $likeKeyword);
} else {
    $doctorStmt = $database->prepare(
        "select doctor.docid, doctor.docname, doctor.docemail, specialties.sname
         from doctor
         left join specialties on doctor.specialties=specialties.id
         order by doctor.docid desc"
    );
}

$doctorStmt->execute();
$doctorsResult = $doctorStmt->get_result();
$doctorCount = $doctorsResult->num_rows;

date_default_timezone_set('Asia/Dhaka');
$date = date('Y-m-d');

$action = $_GET["action"] ?? "";
$modalDoctor = null;
$modalDoctorName = "";
$modalDoctorId = (int) ($_GET["id"] ?? 0);

if ($action === "view" && $modalDoctorId > 0) {
    $modalDoctor = fetchDoctorPopupDetails($database, $modalDoctorId);
    if ($modalDoctor === null) {
        header("location: doctors.php");
        exit;
    }
} elseif ($action === "session") {
    $modalDoctorName = trim($_GET["name"] ?? "");
}
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
    <link rel="stylesheet" href="../css/patient-doctors.css">
    <title>Doctors</title>
</head>
<body>
    <!--  PATIENT DASHBOARD & FEATURES - DOCTOR SEARCH SECTION START  -->
    <div class="container app-shell">
        <div class="menu">
            <table class="menu-container">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo h(substr($username, 0, 13)); ?>..</p>
                                    <p class="profile-subtitle"><?php echo h(substr($useremail, 0, 22)); ?></p>
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
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor menu-active menu-icon-doctor-active">
                        <a href="doctors.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">All Doctors</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="prescriptions.php" class="non-style-link-menu"><div><p class="menu-text">My Prescriptions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="medical-history.php" class="non-style-link-menu"><div><p class="menu-text">Medical History</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
            </table>
        </div>

        <div class="dash-body doctor-directory-page">
            <div class="doctor-directory-shell">
                <div class="doctor-toolbar">
                    <a href="doctors.php" class="doctor-back-btn btn-primary-soft btn btn-icon-back">Back</a>

                    <form action="" method="post" class="doctor-search-form">
                        <input
                            type="search"
                            name="search"
                            class="input-text doctor-search-input"
                            placeholder="Search doctor name or email"
                            list="doctors"
                            value="<?php echo h($searchkeyword); ?>"
                        >
                        <datalist id="doctors">
                            <?php while ($option = $doctorOptions->fetch_assoc()) { ?>
                                <option value="<?php echo h($option["docname"]); ?>"></option>
                                <option value="<?php echo h($option["docemail"]); ?>"></option>
                            <?php } ?>
                        </datalist>
                        <button type="submit" class="doctor-search-btn btn-primary btn">Search</button>
                    </form>

                    <div class="doctor-date-card">
                        <span>Today's Date</span>
                        <strong><?php echo h($date); ?></strong>
                        <button class="doctor-calendar-btn btn-label" type="button" aria-label="Calendar">
                            <img src="../img/calendar.svg" alt="" width="26">
                        </button>
                    </div>
                </div>

                <div class="doctor-page-heading">
                    <div>
                        <p>Doctor Directory</p>
                        <h1>All Doctors</h1>
                    </div>
                    <span><?php echo h($doctorCount); ?> <?php echo $doctorCount === 1 ? "doctor" : "doctors"; ?></span>
                </div>

                <div class="doctor-table-card">
                    <table class="table doctor-table">
                        <thead>
                            <tr>
                                <th>Doctor Name</th>
                                <th>Email</th>
                                <th>Specialties</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($doctorCount === 0) { ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="doctor-empty-state">
                                            <img src="../img/notfound.svg" alt="" width="160">
                                            <h2>No doctors found</h2>
                                            <p>Try a different doctor name or email address.</p>
                                            <a class="btn btn-primary-soft" href="doctors.php">Show all Doctors</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <?php while ($row = $doctorsResult->fetch_assoc()) {
                                    $docid = (int) $row["docid"];
                                    $name = $row["docname"];
                                    $email = $row["docemail"];
                                    $specialty = $row["sname"] ?: "Not specified";
                                ?>
                                    <tr>
                                        <td>
                                            <div class="doctor-name-cell">
                                                <span><?php echo h(substr($name, 0, 1)); ?></span>
                                                <strong><?php echo h($name); ?></strong>
                                            </div>
                                        </td>
                                        <td><?php echo h($email); ?></td>
                                        <td>
                                            <span class="doctor-specialty-pill"><?php echo h($specialty); ?></span>
                                        </td>
                                        <td>
                                            <div class="doctor-row-actions">
                                                <a href="?action=view&id=<?php echo h($docid); ?>" class="doctor-row-action btn-primary-soft btn button-icon btn-view">View</a>
                                                <a href="?action=session&id=<?php echo h($docid); ?>&name=<?php echo urlencode($name); ?>" class="doctor-row-action btn-primary-soft btn button-icon menu-icon-session-active">Sessions</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if ($action === "view" && $modalDoctor !== null) { ?>
        <div id="popup1" class="overlay doctor-modal-overlay">
            <div class="popup doctor-modal doctor-details-modal">
                <a class="close doctor-modal-close" href="doctors.php" aria-label="Close">&times;</a>
                <div class="doctor-modal-header">
                    <p>MediNex Web App</p>
                    <h2>View Details</h2>
                </div>
                <div class="doctor-profile-card">
                    <div class="doctor-profile-mark"><?php echo h(substr($modalDoctor["docname"], 0, 1)); ?></div>
                    <div>
                        <h3><?php echo h($modalDoctor["docname"]); ?></h3>
                        <p><?php echo h($modalDoctor["sname"] ?: "Specialty not specified"); ?></p>
                    </div>
                </div>
                <div class="doctor-detail-grid">
                    <div>
                        <span>Name</span>
                        <strong><?php echo h($modalDoctor["docname"]); ?></strong>
                    </div>
                    <div>
                        <span>Email</span>
                        <strong><?php echo h($modalDoctor["docemail"]); ?></strong>
                    </div>
                    <div>
                        <span>NIC</span>
                        <strong><?php echo h($modalDoctor["docnic"]); ?></strong>
                    </div>
                    <div>
                        <span>Telephone</span>
                        <strong><?php echo h($modalDoctor["doctel"]); ?></strong>
                    </div>
                    <div class="doctor-detail-wide">
                        <span>Specialties</span>
                        <strong><?php echo h($modalDoctor["sname"] ?: "Not specified"); ?></strong>
                    </div>
                </div>
                <div class="doctor-modal-actions">
                    <a href="doctors.php" class="btn btn-primary">OK</a>
                </div>
            </div>
        </div>
    <?php } elseif ($action === "session" && $modalDoctorName !== "") { ?>
        <div id="popup1" class="overlay doctor-modal-overlay">
            <div class="popup doctor-modal doctor-confirm-modal">
                <a class="close doctor-modal-close" href="doctors.php" aria-label="Close">&times;</a>
                <div class="doctor-confirm-icon">
                    <i class="bi bi-calendar2-week"></i>
                </div>
                <h2>Redirect to Doctors sessions?</h2>
                <p>You want to view all sessions by <strong><?php echo h($modalDoctorName); ?></strong>.</p>
                <form action="schedule.php" method="post" class="doctor-confirm-actions">
                    <input type="hidden" name="search" value="<?php echo h($modalDoctorName); ?>">
                    <a href="doctors.php" class="btn btn-primary-soft">Cancel</a>
                    <button type="submit" class="btn btn-primary">Yes</button>
                </form>
            </div>
        </div>
    <?php } ?>

    <!--  PATIENT DASHBOARD & FEATURES - DOCTOR SEARCH SECTION END  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
