<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
    <title><?= e($pageTitle) ?></title>
    <style>
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table { animation: transitionIn-Y-bottom 0.5s; }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            <table class="menu-container">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table class="profile-container">
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
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Bookings</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="prescriptions.php" class="non-style-link-menu"><div><p class="menu-text">My Prescriptions</p></div></a>
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
                <div class="row mb-3">
                    <div class="col-auto">
                        <a href="appointment.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h4 class="mb-0">My Bookings history</h4>
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
                    <div class="col">
                        <h6>My Bookings (<?= count($appointments) ?>)</h6>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <form action="" method="post" class="row g-3 align-items-end">
                            <div class="col-auto">
                                <label class="form-label">Date:</label>
                                <input type="date" name="sheduledate" class="form-control" value="<?= e((string) ($filterDate ?? '')) ?>">
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <?php if (count($appointments) === 0): ?>
                        <div class="col-12 text-center py-5">
                            <img src="../img/notfound.svg" width="25%" class="mb-3" alt="No results"><br>
                            <p class="h5">We couldn't find anything related to your keywords!</p>
                            <a href="appointment.php" class="btn btn-primary mt-3">Show all Appointments</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <small class="text-muted d-block mb-2">Booking Date: <?= e(substr($appointment['appodate'], 0, 30)) ?></small>
                                        <small class="text-muted d-block mb-2">Reference: OC-000-<?= e((string) $appointment['appoid']) ?></small>
                                        <h5 class="card-title"><?= e(substr($appointment['title'], 0, 21)) ?></h5>
                                        <p class="mb-2"><strong>Appointment #:</strong> 0<?= e((string) $appointment['apponum']) ?></p>
                                        <p class="mb-2"><strong>Doctor:</strong> <?= e(substr($appointment['docname'], 0, 30)) ?></p>
                                        <p class="mb-3"><small>Scheduled: <?= e($appointment['scheduledate']) ?> @ <?= e(substr($appointment['scheduletime'], 0, 5)) ?> (24h)</small></p>
                                        <div class="d-grid gap-2">
                                            <a href="?action=reschedule&id=<?= e((string) $appointment['appoid']) ?>&scheduleid=<?= e((string) $appointment['scheduleid']) ?>" class="btn btn-outline-primary btn-sm">Reschedule</a>
                                            <a href="?action=drop&id=<?= e((string) $appointment['appoid']) ?>&title=<?= urlencode($appointment['title']) ?>&doc=<?= urlencode($appointment['docname']) ?>" class="btn btn-outline-danger btn-sm">Cancel Booking</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($popup && $popup['type'] === 'booking-added'): ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <br><br>
                    <h2>Booking Successfully.</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        Your Appointment number is <?= e((string) $popup['bookingId']) ?>.<br><br>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a href="appointment.php" class="btn btn-primary">OK</a>
                    </div>
                </center>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($popup && $popup['type'] === 'drop'): ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Are you sure?</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        You want to Cancel this Appointment?<br><br>
                        Session Name: &nbsp;<b><?= e(substr($popup['title'], 0, 40)) ?></b><br>
                        Doctor name&nbsp; : <b><?= e(substr($popup['doctorName'], 0, 40)) ?></b><br><br>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="delete-appointment.php?id=<?= e((string) $popup['appointmentId']) ?>" class="btn btn-primary">Yes</a>
                        <a href="appointment.php" class="btn btn-secondary">No</a>
                    </div>
                </center>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($popup && $popup['type'] === 'view'): ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2></h2>
                    <a class="close" href="doctors.php">&times;</a>
                    <div class="content">
                        eDoc Web App<br>
                    </div>
                    <div style="display:flex;justify-content:center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td>
                                    <p style="padding:0;margin:0;text-align:left;font-size:25px;font-weight:500;">View Details.</p><br><br>
                                </td>
                            </tr>
                            <tr><td class="label-td" colspan="2"><label class="form-label">Name: </label></td></tr>
                            <tr><td class="label-td" colspan="2"><?= e($popup['doctor']['docname']) ?><br><br></td></tr>
                            <tr><td class="label-td" colspan="2"><label class="form-label">Email: </label></td></tr>
                            <tr><td class="label-td" colspan="2"><?= e($popup['doctor']['docemail']) ?><br><br></td></tr>
                            <tr><td class="label-td" colspan="2"><label class="form-label">NIC: </label></td></tr>
                            <tr><td class="label-td" colspan="2"><?= e($popup['doctor']['docnic']) ?><br><br></td></tr>
                            <tr><td class="label-td" colspan="2"><label class="form-label">Telephone: </label></td></tr>
                            <tr><td class="label-td" colspan="2"><?= e($popup['doctor']['doctel']) ?><br><br></td></tr>
                            <tr><td class="label-td" colspan="2"><label class="form-label">Specialties: </label></td></tr>
                            <tr><td class="label-td" colspan="2"><?= e((string) ($popup['doctor']['sname'] ?? '')) ?><br><br></td></tr>
                            <tr>
                                <td colspan="2">
                                    <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </center>
                <br><br>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($popup && $popup['type'] === 'reschedule'): ?>
        <?php $reschedule = $popup['data']; ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Reschedule Appointment</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        <form action="reschedule-appointment.php" method="POST">
                            <input type="hidden" name="appoid" value="<?= e((string) $reschedule['appointmentId']) ?>">
                            <?php if ($reschedule['errorMessage'] !== ''): ?>
                                <label style="color:rgb(255, 62, 62);"><?= e($reschedule['errorMessage']) ?></label><br>
                            <?php endif; ?>
                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label class="form-label">Current Appointment: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <b><?= e($reschedule['title']) ?></b> with <b><?= e($reschedule['doctorName']) ?></b><br>
                                        Date: <?= e($reschedule['currentDate']) ?> at <?= e(substr($reschedule['currentTime'], 0, 5)) ?><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="new_scheduleid" class="form-label">Select New Schedule: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <select name="new_scheduleid" class="input-text" required>
                                            <option value="">Choose a new schedule</option>
                                            <?php foreach ($reschedule['scheduleOptions'] as $option): ?>
                                                <option value="<?= e((string) $option['scheduleid']) ?>"><?= e($option['label']) ?></option>
                                            <?php endforeach; ?>
                                        </select><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="new_date" class="form-label">New Appointment Date: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="date" name="new_date" class="input-text" min="<?= e($reschedule['minDate']) ?>" required><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="submit" name="reschedule" value="Reschedule" class="login-btn btn-primary btn">
                                        <a href="appointment.php"><input type="button" value="Cancel" class="login-btn btn-primary-soft btn"></a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </center>
                <br><br>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($popup && $popup['type'] === 'rescheduled'): ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <br><br>
                    <h2>Appointment Rescheduled Successfully.</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        Your appointment has been rescheduled.<br><br>
                    </div>
                    <div style="display:flex;justify-content:center;">
                        <a href="appointment.php" class="non-style-link">
                            <button class="btn-primary btn" style="display:flex;justify-content:center;align-items:center;margin:10px;padding:10px;" type="button">
                                <span class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</span>
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
