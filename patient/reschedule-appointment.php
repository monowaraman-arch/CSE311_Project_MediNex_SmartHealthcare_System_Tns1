<?php
// ========== PATIENT DASHBOARD & FEATURES - APPOINTMENT MANAGEMENT SECTION START ==========
// This file handles appointment rescheduling
// Updates appointment with new schedule, checks availability and overlap
// Uses helper functions for validation and audit logging
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
    // ========== DATABASE RELATIONS USED: Patient, Appointment, Schedule ==========
    // Patient: Get patient ID for validation
    // Appointment: Update appointment with new schedule (scheduleid, appodate, status)
    // Schedule: Check new schedule availability and max patients
    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];

    if($_POST && isset($_POST['reschedule'])){
        $appoid = $_POST['appoid'];
        $new_scheduleid = $_POST['new_scheduleid'];
        $new_date = $_POST['new_date'];
        
        // Check overlap
        if(!checkAppointmentOverlap($database, $new_scheduleid, $new_date, $appoid)){
            // Get schedule max patients
            $max_patients = getScheduleMaxPatients($database, $new_scheduleid);
            $booked_count = getScheduleBookedCount($database, $new_scheduleid);
            
            if($booked_count < $max_patients){
                // Update appointment
                $sql = "UPDATE appointment SET scheduleid=?, appodate=?, status='rescheduled' WHERE appoid=? AND pid=?";
                $stmt = $database->prepare($sql);
                $stmt->bind_param("isii", $new_scheduleid, $new_date, $appoid, $userid);
                $stmt->execute();
                
                header("location: appointment.php?action=rescheduled&id=".$appoid);
            } else {
                header("location: appointment.php?action=reschedule&id=".$appoid."&error=full");
            }
        } else {
            header("location: appointment.php?action=reschedule&id=".$appoid."&error=overlap");
        }
    }

header("location: appointment.php");
// ========== PATIENT DASHBOARD & FEATURES - APPOINTMENT MANAGEMENT SECTION END ==========
?>
