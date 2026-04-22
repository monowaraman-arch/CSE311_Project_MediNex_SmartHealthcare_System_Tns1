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
    include("../includes/auth-helper.php");
    // ========== DATABASE RELATIONS USED: Doctor, Visit_Summaries, Appointment ==========
    // Doctor: Get logged-in doctor info (docid)
    // Visit_Summaries: Create visit summary after appointment (appointment_id, chief_complaint, examination_findings, diagnosis, treatment_plan, notes)
    // Appointment: Update appointment status to 'completed'
    $userrow = $database->query("select * from doctor where docemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["docid"];

    if($_POST && isset($_POST['create_summary'])){
        $appointment_id = $_POST['appointment_id'];
        $chief_complaint = $_POST['chief_complaint'];
        $examination_findings = $_POST['examination_findings'] ?? '';
        $diagnosis = $_POST['diagnosis'];
        $treatment_plan = $_POST['treatment_plan'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        $sql = "INSERT INTO visit_summaries (appointment_id, chief_complaint, examination_findings, diagnosis, treatment_plan, notes) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $database->prepare($sql);
        $stmt->bind_param("isssss", $appointment_id, $chief_complaint, $examination_findings, $diagnosis, $treatment_plan, $notes);
        $stmt->execute();
        
        // Update appointment status
        $database->query("UPDATE appointment SET status='completed' WHERE appoid=$appointment_id");
        
        
        header("location: appointment.php?action=summary-created&id=".$appointment_id);
    }

    header("location: appointment.php");

?>

