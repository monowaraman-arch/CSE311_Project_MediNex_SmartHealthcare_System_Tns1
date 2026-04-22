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
    $userrow = $database->query("select * from doctor where docemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["docid"];
    $username=$userfetch["docname"];

    $error='3';
    $appointment_id = $_GET['appointment_id'] ?? null;
    $patient_id = $_GET['patient_id'] ?? null;

    if($_POST){
        $appointment_id = $_POST['appointment_id'] ?? null;
        $patient_id = $_POST['patient_id'];
        $prescription_date = $_POST['prescription_date'];
        $diagnosis = $_POST['diagnosis'];
        $follow_up_date = $_POST['follow_up_date'] ?? null;
        $follow_up_instructions = $_POST['follow_up_instructions'] ?? '';
        
        // Create prescription
        $sql = "INSERT INTO prescriptions (appointment_id, doctor_id, patient_id, prescription_date, diagnosis, follow_up_date, follow_up_instructions) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $database->prepare($sql);
        $stmt->bind_param("iiissss", $appointment_id, $userid, $patient_id, $prescription_date, $diagnosis, $follow_up_date, $follow_up_instructions);
        $stmt->execute();
        $prescription_id = $database->insert_id;
        
        // Add medicines
        if(isset($_POST['medicines']) && is_array($_POST['medicines'])){
            foreach($_POST['medicines'] as $med){
                if(!empty($med['medicine_id'])){
                    $med_sql = "INSERT INTO prescription_medicines (prescription_id, medicine_id, dosage, frequency, duration, instructions) VALUES (?, ?, ?, ?, ?, ?)";
                    $med_stmt = $database->prepare($med_sql);
                    $med_stmt->bind_param("iissss", $prescription_id, $med['medicine_id'], $med['dosage'], $med['frequency'], $med['duration'], $med['instructions']);
                    $med_stmt->execute();
                }
            }
        }
        
        header("location: prescriptions.php?action=prescription-added&id=".$prescription_id);
    }

    header("location: prescriptions.php?action=add-prescription&appointment_id=".$appointment_id."&patient_id=".$patient_id."&error=".$error);
?>

