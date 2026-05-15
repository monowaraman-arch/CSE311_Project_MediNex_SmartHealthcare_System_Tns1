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
    <link rel="stylesheet" href="../css/patient-settings.css">
    <title>Settings</title>
    <style>
        .dashbord-tables{animation: transitionIn-Y-over 0.5s;}
        .filter-container{animation: transitionIn-X 0.5s;}
        .sub-table{animation: transitionIn-Y-bottom 0.5s;}
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
    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $result = $stmt->get_result();
    $userfetch=$result->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];
    ?>
    <!--  PATIENT DASHBOARD & FEATURES - PATIENT SETTINGS SECTION START -->
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
                    <td class="menu-btn menu-icon-home " >
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
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="prescriptions.php" class="non-style-link-menu"><div><p class="menu-text">My Prescriptions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="medical-history.php" class="non-style-link-menu"><div><p class="menu-text">Medical History</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings  menu-active menu-icon-settings-active">
                        <a href="settings.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
                
            </table>
        </div>
        <!--  PATIENT SETTINGS HEADER SECTION START  -->
        <div class="dash-body" style="margin-top: 15px">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-auto">
                        <a href="settings.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>
                    <div class="col">
                        <h4 class="mb-0">Settings</h4>
                    </div>
                    <div class="col-auto text-end">
                        <small class="text-muted d-block">Today's Date</small>
                        <strong><?php date_default_timezone_set('Asia/Dhaka'); echo date('Y-m-d'); ?></strong>
                    </div>
                    <div class="col-auto">
                        <img src="../img/calendar.svg" width="30" alt="calendar">
                    </div>
                </div>
                <!-- PATIENT SETTINGS HEADER SECTION END  -->
                <!--  SETTINGS OPTIONS SECTION START -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="?action=edit&id=<?php echo $userid ?>&error=0" class="text-decoration-none">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Account Settings</h5>
                                    <p class="card-text text-muted">Edit your Account Details & Change Password</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="?action=view&id=<?php echo $userid ?>" class="text-decoration-none">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">View Account Details</h5>
                                    <p class="card-text text-muted">View Personal information About Your Account</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="?action=drop&id=<?php echo $userid.'&name='.$username ?>" class="text-decoration-none">
                            <div class="card h-100 border-danger">
                                <div class="card-body">
                                    <h5 class="card-title text-danger">Delete Account</h5>
                                    <p class="card-text text-muted">Will Permanently Remove your Account</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!--  SETTINGS OPTIONS SECTION END  -->
            </div>
        </div>
    </div>
    <?php
    if (!function_exists('settings_h')) {
        function settings_h($value) {
            return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        }
    }

    if (!function_exists('settings_age')) {
        function settings_age($dob) {
            if (empty($dob) || $dob === '0000-00-00') {
                return 'Not set';
            }

            try {
                $dobDate = new DateTime($dob);
                $today = new DateTime();
                return $today->diff($dobDate)->y . ' years';
            } catch (Exception $exception) {
                return 'Not set';
            }
        }
    }

    if($_GET){
        $id = (int) ($_GET["id"] ?? 0);
        $action = $_GET["action"] ?? "";

        if($action=='drop'){
            $nameget = $_GET["name"] ?? $username;
            $displayName = settings_h(substr($nameget,0,40));
            ?>
            <div id="popup1" class="overlay settings-modal-overlay">
                <div class="popup settings-modal settings-confirm-modal">
                    <a class="close settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                    <div class="settings-confirm-icon settings-danger-icon">
                        <i class="bi bi-person-x"></i>
                    </div>
                    <h2>Delete Account?</h2>
                    <p class="settings-confirm-copy">This will permanently remove your MediNex account.</p>
                    <div class="settings-summary-card">
                        <div>
                            <span>Account Holder</span>
                            <strong><?php echo $displayName; ?></strong>
                        </div>
                    </div>
                    <div class="settings-modal-actions">
                        <a href="settings.php" class="btn btn-primary-soft">Keep Account</a>
                        <a href="delete-account.php?id=<?php echo $id; ?>" class="btn btn-danger">Delete Account</a>
                    </div>
                </div>
            </div>
            <?php
        }elseif($action=='view'){
            $sqlmain= "select * from patient where pid=?"; // Get patient record based on patient ID passed in URL for viewing details
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row=$result->fetch_assoc();

            if($row){
                $age = settings_age($row["pdob"]);
                ?>
                <div id="popup1" class="overlay settings-modal-overlay">
                    <div class="popup settings-modal settings-details-modal">
                        <a class="close settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                        <div class="settings-modal-header">
                            <p>MediNex</p>
                            <h2>View Details</h2>
                        </div>
                        <div class="settings-profile-card">
                            <div class="settings-profile-mark"><?php echo settings_h(substr($row["pname"], 0, 1)); ?></div>
                            <div>
                                <h3><?php echo settings_h($row["pname"]); ?></h3>
                                <p><?php echo settings_h($row["pemail"]); ?></p>
                            </div>
                        </div>
                        <div class="settings-detail-grid">
                            <div>
                                <span>Name</span>
                                <strong><?php echo settings_h($row["pname"]); ?></strong>
                            </div>
                            <div>
                                <span>Email</span>
                                <strong><?php echo settings_h($row["pemail"]); ?></strong>
                            </div>
                            <div>
                                <span>NIC</span>
                                <strong><?php echo settings_h($row["pnic"]); ?></strong>
                            </div>
                            <div>
                                <span>Telephone</span>
                                <strong><?php echo settings_h($row["ptel"]); ?></strong>
                            </div>
                            <div>
                                <span>Date of Birth</span>
                                <strong><?php echo settings_h($row["pdob"]); ?></strong>
                            </div>
                            <div>
                                <span>Age</span>
                                <strong><?php echo settings_h($age); ?></strong>
                            </div>
                            <div class="settings-detail-wide">
                                <span>Address</span>
                                <strong><?php echo settings_h($row["paddress"]); ?></strong>
                            </div>
                            <div class="settings-detail-wide">
                                <span>Allergies</span>
                                <strong><?php echo settings_h($row["allergies"] ?: "None listed"); ?></strong>
                            </div>
                        </div>
                        <div class="settings-modal-actions">
                            <a href="settings.php" class="btn btn-primary">OK</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }elseif($action=='edit'){
            $sqlmain= "select * from patient where pid=?"; // Get patient record based on patient ID passed in URL for editing details
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row=$result->fetch_assoc();

            if($row){
                $age = settings_age($row["pdob"]);
                $error_1=$_GET["error"] ?? '0';
                $errorlist= array(
                    '1'=>'Already have an account for this email address.',
                    '2'=>'Password confirmation error. Please confirm your password again.',
                    '3'=>'',
                    '4'=>'',
                    '0'=>'',
                );
                $errorMessage = $errorlist[$error_1] ?? '';

                if($error_1!='4'){
                    ?>
                    <div id="popup1" class="overlay settings-modal-overlay">
                        <div class="popup settings-modal settings-edit-modal">
                            <a class="close settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                            <div class="settings-modal-header">
                                <p>Account Settings</p>
                                <h2>Edit User Account Details</h2>
                                <span>User ID: <?php echo $id; ?> (Auto Generated)</span>
                            </div>
                            <form action="edit-user.php" method="POST" class="settings-edit-form">
                                <input type="hidden" value="<?php echo $id; ?>" name="id00">
                                <input type="hidden" name="oldemail" value="<?php echo settings_h($row["pemail"]); ?>">

                                <?php if($errorMessage !== ''){ ?>
                                    <div class="settings-alert">
                                        <i class="bi bi-exclamation-circle"></i>
                                        <?php echo settings_h($errorMessage); ?>
                                    </div>
                                <?php } ?>

                                <div class="settings-form-grid">
                                    <div class="settings-field">
                                        <label for="settings-email" class="form-label">Email</label>
                                        <input id="settings-email" type="email" name="email" class="input-text" placeholder="Email Address" value="<?php echo settings_h($row["pemail"]); ?>" required>
                                    </div>
                                    <div class="settings-field">
                                        <label for="settings-name" class="form-label">Name</label>
                                        <input id="settings-name" type="text" name="name" class="input-text" placeholder="Patient Name" value="<?php echo settings_h($row["pname"]); ?>" required>
                                    </div>
                                    <div class="settings-field">
                                        <label for="settings-nic" class="form-label">NIC</label>
                                        <input id="settings-nic" type="text" name="nic" class="input-text" placeholder="NIC Number" value="<?php echo settings_h($row["pnic"]); ?>" required>
                                    </div>
                                    <div class="settings-field">
                                        <label for="settings-phone" class="form-label">Telephone</label>
                                        <input id="settings-phone" type="tel" name="Tele" class="input-text" placeholder="Telephone Number" value="<?php echo settings_h($row["ptel"]); ?>" required>
                                    </div>
                                    <div class="settings-readonly-card">
                                        <span>Date of Birth</span>
                                        <strong><?php echo settings_h($row["pdob"]); ?></strong>
                                        <small><?php echo settings_h($age); ?></small>
                                    </div>
                                    <div class="settings-field settings-field-wide">
                                        <label for="settings-address" class="form-label">Address</label>
                                        <input id="settings-address" type="text" name="address" class="input-text" placeholder="Address" value="<?php echo settings_h($row["paddress"]); ?>" required>
                                    </div>
                                    <div class="settings-field settings-field-wide">
                                        <label for="settings-allergies" class="form-label">Allergies (Optional)</label>
                                        <textarea id="settings-allergies" name="allergies" class="input-text" rows="3" placeholder="List any allergies (e.g. Penicillin, Peanuts)"><?php echo settings_h($row['allergies'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="settings-field">
                                        <label for="settings-password" class="form-label">Password</label>
                                        <input id="settings-password" type="password" name="password" class="input-text" placeholder="Define a password" required>
                                    </div>
                                    <div class="settings-field">
                                        <label for="settings-cpassword" class="form-label">Confirm Password</label>
                                        <input id="settings-cpassword" type="password" name="cpassword" class="input-text" placeholder="Confirm password" required>
                                    </div>
                                </div>
                                <div class="settings-modal-actions">
                                    <button type="reset" class="btn btn-primary-soft">Reset</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                }else{
                    ?>
                    <div id="popup1" class="overlay settings-modal-overlay">
                        <div class="popup settings-modal settings-confirm-modal">
                            <a class="close settings-modal-close" href="settings.php" aria-label="Close">&times;</a>
                            <div class="settings-confirm-icon">
                                <i class="bi bi-check2"></i>
                            </div>
                            <h2>Edit Successfully!</h2>
                            <p class="settings-confirm-copy">If you changed your email, please log out and sign in again with your new email.</p>
                            <div class="settings-modal-actions">
                                <a href="settings.php" class="btn btn-primary">OK</a>
                                <a href="../logout.php" class="btn btn-primary-soft">Log out</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
    }
    ?>

</body>
</html>
