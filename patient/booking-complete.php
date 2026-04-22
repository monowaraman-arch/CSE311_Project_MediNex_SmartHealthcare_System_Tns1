<?php
//  PATIENT DASHBOARD & FEATURES - APPOINTMENT BOOKING SYSTEM SECTION START
//  DATABASE RELATIONS USED: Patient, Appointment, Schedule 
// Patient: Get patient ID from session email
// Schedule: Check schedule availability and max patients
// Appointment: Insert new appointment record (pid, apponum, scheduleid, appodate, status)
// This file handles appointment booking completion
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
    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];


    if($_POST){
        if(isset($_POST["booknow"])){
            $apponum=$_POST["apponum"];
            $scheduleid=$_POST["scheduleid"];
            $date=$_POST["date"];
            
            // Check overlap and availability
            if(!checkAppointmentOverlap($database, $scheduleid, $date)){
                $max_patients = getScheduleMaxPatients($database, $scheduleid);
                $booked_count = getScheduleBookedCount($database, $scheduleid);
                
                if($booked_count < $max_patients){
                    $sql2="insert into appointment(pid,apponum,scheduleid,appodate,status) values ($userid,$apponum,$scheduleid,'$date','pending')";
                    $result= $database->query($sql2);
                    //echo $apponom;
                    header("location: appointment.php?action=booking-added&id=".$apponum."&titleget=none");
                } else {
                    header("location: booking.php?id=".$scheduleid."&error=full");
                }
            } else {
                header("location: booking.php?id=".$scheduleid."&error=overlap");
            }

        }
    }
// PATIENT DASHBOARD & FEATURES - APPOINTMENT BOOKING SYSTEM SECTION END 
?>