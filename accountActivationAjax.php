<?php

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}



session_start();
date_default_timezone_set('Asia/Manila');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

include 'vendor/autoload.php';

include_once 'DatabaseConn/databaseConn.php';
include 'functions.php';//first
include 'ajaxreq_processFunc.php';//second

include_once 'PhpMailer_producer.php';




$action = $_GET['action'];
extract($_POST);

if($action == 'verifyStudentNum'){
    header('Content-Type: application/json');
    $studentNum = $_POST['studNumber'];
    $email = $_POST['email'];
    $chkstudNum = mysqlQuery("SELECT acc.* , info.* FROM tbl_students stud
JOIN tbl_studinfo info ON stud.enrolled_stud_id = info.enrolled_stud_id
JOIN tbl_accounts acc on acc.user_id = stud.user_id
WHERE info.enrolled_stud_id = ? and info.OJT_status = 1;  " , 'i', [$studentNum]);
    if (count($chkstudNum) === 1){
        if($chkstudNum[0]['email'] != null and $chkstudNum[0]['password'] != null){
            handleError('Account is already activated');
            exit();
        }else{
            $accOTP = random_int(100000, 999999);

            $createOTP = mysqlQuery("UPDATE tbl_accounts acc
    JOIN tbl_students stud ON acc.user_id = stud.user_id
SET acc.OTP = ?,
    acc.OTP_generated_Date = NOW(),
    acc.email = ?
WHERE stud.enrolled_stud_id = ?
 ", 'isi', [$accOTP,$email, $studentNum]);


            $subjectType = 'Insight Account';
            $bodyMessage = "
               <h1><b>Account email verification</b></h1>
               <br><br>
               <b>Verification Code:</b> $accOTP<br>
";
            email_queuing($subjectType, $bodyMessage, $email);

            echo json_encode(['response' => 1,
                'message' => 'OTP sent to your email']);
        }


     }else{
        handleError('Student number not found');
    }

    exit();


}
if ($action == 'verifyOTP'){
    header('Content-Type: application/json');

    $studentNum = $_POST['studNumber'];
    $email = $_POST['email'];
    $accOTP_post = $_POST['verificationCode'];

    $chkstudNum = mysqlQuery("SELECT acc.* , info.* FROM tbl_students stud
JOIN tbl_studinfo info ON stud.enrolled_stud_id = info.enrolled_stud_id
JOIN tbl_accounts acc on acc.user_id = stud.user_id
WHERE info.enrolled_stud_id = ? and info.OJT_status = 1;  " , 'i', [$studentNum]);


    if (count($chkstudNum) !== 1){
        handleError('Student number not found');
        exit();
    }
    $studaccAdccdata = $chkstudNum[0];
    $stud_user_id = $studaccAdccdata['user_id'];
    $studSetEmail = $studaccAdccdata['email'];

    $studotp = $studaccAdccdata['OTP'];
    $otpGenerated = $studaccAdccdata['OTP_generated_Date'];
    $otpGenerated_timestamp = strtotime($otpGenerated);
    $otpExpirationDate = $otpGenerated_timestamp + (5 * 60);


    if($email != $studSetEmail){
        handleError('Email does not match to the sent email before');
        exit();
    }
    if(time() > $otpExpirationDate){//check validation of the otp
        handleError('OTP has expired');
        exit();
    }
    if ($accOTP_post != $studotp){
        handleError('Invalid OTP');
        exit();
    }

    $generatedpassword = generatePassword($studentNum);
    $hashed_password = password_hash($generatedpassword, PASSWORD_DEFAULT);

    $update_account_password = mysqlQuery("UPDATE tbl_accounts  set
                            password = ? , 
                            otp = null ,
                            OTP_generated_Date = null
                        
                        where user_id = ? ", 'si', [$hashed_password, $stud_user_id]);
              // Send email notification
               $subjectType = 'Insight Account';
               $bodyMessage = "
                   <h1><b>Notification</b></h1><br><br>
                   Your email has been successfully registered!<br>
                   Use these credentials to log in:<br>
                   <h3>Account credentials</h3><br>
                   <b>Email:</b> $email  <br>
                   <b>Password:</b> $generatedpassword<br><br>
                   <a href='http://localhost/ReposyncNarrativeManagementSystem/src/login.php'>
                   Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus</a>";
               email_queuing($subjectType, $bodyMessage, $email);


    echo json_encode(['response' => 1,  'message' => 'Account activated successfully please check your email for your credentials.']);
    exit();
}


