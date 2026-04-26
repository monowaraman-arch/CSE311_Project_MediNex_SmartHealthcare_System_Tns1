<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Medical History</title>
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
    include("../includes/auth-helper.php");
    // ========== DATABASE RELATIONS USED: Patient, Medical_History ==========
    // Patient: Get logged-in patient info (pid, pname)
    // Medical_History: Store and retrieve patient medical history (patient_id, condition_name, diagnosis_date, status, notes)
    $sqlmain= "select * from patient where pemail=?"; // Get patient record based on session email to display user info and for use in features
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

    // Handle form submission
    if($_POST && isset($_POST['add_history'])){
        $condition_name = $_POST['condition_name'];
        $diagnosis_date = $_POST['diagnosis_date'];
        $status = $_POST['status'];
        $notes = $_POST['notes'] ?? '';
        
        // Insert new medical history record for the patient
        $sql = "INSERT INTO medical_history (patient_id, condition_name, diagnosis_date, status, notes) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $database->prepare($sql);
        $stmt->bind_param("issss", $userid, $condition_name, $diagnosis_date, $status, $notes);
        $stmt->execute();
        
        header("location: medical-history.php?action=history-added");
    }

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
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="medical-history.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Medical History</p></div></a>
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
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="medical-history.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">My Medical History</p>
                                           
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 

                        date_default_timezone_set('Asia/Dhaka');

                        $today = date('Y-m-d');
                        echo $today;

                        $list110 = $database->query("select * from medical_history where patient_id=$userid"); // Get medical history record count for the logged-in patient to display in heading

                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>

                </tr>
               
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">Medical History Records (<?php echo $list110->num_rows; ?>)</p>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                        <div style="display: flex;margin-left: 45px;">
                        <a href="?action=add-history&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="margin-left:25px;background-image: url('../img/icons/add.svg');">Add Medical History</font></button>
                        </a>
                        </div>
                    </td>
                </tr>
                
                <?php
                    // Retrieve medical history records for the logged-in patient, ordered by diagnosis date
                    $sqlmain= "select * from medical_history 
                               where patient_id=$userid 
                               order by diagnosis_date desc";

                ?>
                  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0">
                        <thead>
                        <tr>
                                <th class="table-headin">
                                    Condition Name
                                </th>
                                <th class="table-headin">
                                    Diagnosis Date
                                </th>
                               
                                <th class="table-headin">
                                    Status
                                </th>
                                
                                <th class="table-headin" >
                                    Notes
                                </th>
                                
                                <th class="table-headin">
                                    Events
                                </tr>
                        </thead>
                        <tbody>
                        
                            <?php

                                
                                $result= $database->query($sqlmain);

                                if($result->num_rows==0){
                                    echo '<tr>
                                    <td colspan="5">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">No medical history found!</p>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                    
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
                                    $row=$result->fetch_assoc();
                                    $history_id=$row["history_id"];
                                    $condition_name=$row["condition_name"];
                                    $diagnosis_date=$row["diagnosis_date"];
                                    $status=$row["status"];
                                    $notes=$row["notes"];
                                    echo '<tr >
                                        <td style="font-weight:600;"> &nbsp;'.
                                        
                                        substr($condition_name,0,30)
                                        .'</td >
                                        <td style="text-align:center;">
                                        '.$diagnosis_date.'
                                        </td>
                                        <td>
                                        '.$status.'
                                        </td>
                                        
                                        <td style="text-align:center;">
                                            '.substr($notes,0,30).'...
                                        </td>

                                        <td>
                                        <div style="display:flex;justify-content: center;">
                                        
                                        <a href="?action=view&id='.$history_id.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-view"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View</font></button></a>
                                       &nbsp;&nbsp;&nbsp;</div>
                                        </td>
                                    </tr>';
                                    
                                }
                            }
                                 
                            ?>
 
                            </tbody>

                        </table>
                        </div>
                        </center>
                   </td> 
                </tr>
                       
                        
                        
            </table>
        </div>
    </div>
    <?php
    
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        if($action=='add-history'){
            date_default_timezone_set('Asia/Dhaka');
            $today = date('Y-m-d');
            
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Add Medical History</h2>
                        <a class="close" href="medical-history.php">&times;</a>
                        <div class="content">
                            <form action="" method="POST">
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
                                        <input type="date" name="diagnosis_date" class="input-text" max="'.$today.'" required><br><br>
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
                                        <a href="medical-history.php"><input type="button" value="Cancel" class="login-btn btn-primary-soft btn"></a>
                                    </td>
                                </tr>
                            </table>
                            </form>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';
        }elseif($action=='view'){
            // Get medical history record details based on history_id passed in URL for viewing
            $sqlmain= "select * from medical_history 
                       where history_id=$id and patient_id=$userid";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $condition_name=$row["condition_name"];
            $diagnosis_date=$row["diagnosis_date"];
            $status=$row["status"];
            $notes=$row["notes"];
            
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Medical History Details</h2>
                        <a class="close" href="medical-history.php">&times;</a>
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
                                    '.$condition_name.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label class="form-label">Diagnosis Date: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$diagnosis_date.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label class="form-label">Status: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$status.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label class="form-label">Notes: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.($notes ? $notes : 'No notes').'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="medical-history.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                </td>
                            </tr>
                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
        }elseif($action=='history-added'){
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Medical History Added Successfully.</h2>
                        <a class="close" href="medical-history.php">&times;</a>
                        <div class="content">
                        Your medical history has been recorded.<br><br>
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="medical-history.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                        </div>
                    </center>
            </div>
            </div>
            ';
        }
    }

    ?>
    </div>

</body>
</html>

