<?php
// ========== PATIENT DASHBOARD & FEATURES - PATIENT SETTINGS SECTION START ==========
// This file handles patient account information updates
// Processes form data to update patient profile, email, password, and other details
include("../connection.php");
// ========== DATABASE RELATIONS USED: WebUser, Patient ==========
// WebUser: Check email uniqueness, update email if changed
// Patient: Update patient profile (pemail, pname, ppassword, pnic, ptel, paddress, allergies)

    if($_POST){
        //print_r($_POST);
        $result= $database->query("select * from webuser");
        $name=$_POST['name'];
        $nic=$_POST['nic'];
        $oldemail=$_POST["oldemail"];
        $address=$_POST['address'];
        $email=$_POST['email'];
        $tele=$_POST['Tele'];
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];
        $allergies=$_POST['allergies'] ?? '';
        $id=$_POST['id00'];
        
        if ($password==$cpassword){
            $error='3';

            // Check if the new email is already taken by another user
            $sqlmain= "select patient.pid 
                       from patient 
                       inner join webuser 
                       on patient.pemail=webuser.email 
                       where webuser.email=?;";

            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $result = $stmt->get_result();
            //$resultqq= $database->query("select * from doctor where docid='$id';");
            if($result->num_rows==1){
                $id2=$result->fetch_assoc()["pid"];
            }else{
                $id2=$id;
            }
            

            if($id2!=$id){
                $error='1';
                //$resultqq1= $database->query("select * from doctor where docemail='$email';");
                //$did= $resultqq1->fetch_assoc()["docid"];
                //if($resultqq1->num_rows==1){
                    
            }else{

                // Update patient record with new details
                $sql1="update patient 
                       set pemail='$email',pname='$name',ppassword='$password',pnic='$nic',ptel='$tele',paddress='$address',allergies='$allergies' where pid=$id ;";
                $database->query($sql1);
                echo $sql1;
                // If email was changed, update webuser record to maintain authentication
                $sql1="update webuser 
                       set email='$email' where email='$oldemail' ;";
                $database->query($sql1);
                echo $sql1;
                
                $error= '4';
                
            }
            
        }else{
            $error='2';
        }
    
    
        
        
    }else{
        //header('location: signup.php');
        $error='3';
    }
    

header("location: settings.php?action=edit&error=".$error."&id=".$id);
// ========== PATIENT DASHBOARD & FEATURES - PATIENT SETTINGS SECTION END ==========
?>

</body>
</html>