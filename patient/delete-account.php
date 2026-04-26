<?php
// ========== PATIENT DASHBOARD & FEATURES - ACCOUNT DELETION SECTION START ==========
// This file handles permanent account deletion
// Removes patient data from both patient and webuser tables
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
    // ========== DATABASE RELATIONS USED: Patient, WebUser ==========
    // Patient: Delete patient record permanently
    // WebUser: Delete user authentication record
    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

    
    if($_GET){
        //import database
        include("../connection.php");
        $id=$_GET["id"];
        $sqlmain= "select * from patient where pid=?";
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $result001 = $stmt->get_result();
        $email=($result001->fetch_assoc())["pemail"];

        $sqlmain= "delete from webuser where email=?;"; // Delete from webuser table first to remove authentication 
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();


        $sqlmain= "delete from patient where pemail=?"; // Then delete from patient table to remove personal data
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        //print_r($email);
        header("location: ../logout.php");
    }
// ========== PATIENT DASHBOARD & FEATURES - ACCOUNT DELETION SECTION END ==========
?>