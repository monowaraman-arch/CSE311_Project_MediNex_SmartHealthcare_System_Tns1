<?php
// ========== PATIENT DASHBOARD & FEATURES - APPOINTMENT MANAGEMENT SECTION START ==========
// ========== DATABASE RELATIONS USED: Appointment ==========
// Appointment: Delete appointment record (appoid) when patient cancels booking
session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    
if($_GET){
    include("../connection.php");
    $id=$_GET["id"];
    $database->query("delete from appointment where appoid='$id';"); // Delete appointment record based on appoid passed in URL
    header("location: appointment.php");
}
// ========== PATIENT DASHBOARD & FEATURES - APPOINTMENT MANAGEMENT SECTION END ==========
?>