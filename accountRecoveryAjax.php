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

if($action == 'verifyUserAccount'){
    header('Content-Type: application/json');
    $email = $_POST['email'];
    $chkUserAcc = mysqlQuery("SELECT *  FROM tbl_accounts 
WHERE email = ? and status = 1  " , 's', [ $email]);
    if (count($chkUserAcc) === 1){
        $accOTP = random_int(100000, 999999);
        $createOTP = mysqlQuery("UPDATE tbl_accounts 
SET OTP = ?,  OTP_generated_Date = NOW() WHERE email = ? ", 'is', [$accOTP,$email]);
        $subjectType = 'Insight Account';
        $bodyMessage = "
               <h1><b>Account email verification</b></h1>
               <br><br>
               <b>Verification Code:</b> $accOTP<br>
";
        email_queuing($subjectType, $bodyMessage, $email);

        echo json_encode(['response' => 1,
            'message' => 'OTP sent to your email']);
    }else{
        handleError('User Account not found');
    }
    exit();
}


if ($action == 'verifyAccountOTP'){
    header('Content-Type: application/json');
    $email = $_POST['email'];
    $accOTP_post = $_POST['verificationCode'];
    $chkstudNum = mysqlQuery("SELECT * FROM tbl_accounts WHERE email = ? and status = 1"
        , 's', [$email]);
    if (count($chkstudNum) !== 1){
        handleError('Student account not found');
        exit();
    }
    $studaccAdccdata = $chkstudNum[0];

    $studotp = $studaccAdccdata['OTP'];
    $otpGenerated = $studaccAdccdata['OTP_generated_Date'];
    $otpGenerated_timestamp = strtotime($otpGenerated);
    $otpExpirationDate = $otpGenerated_timestamp + (5 * 60);


    if(time() > $otpExpirationDate){//check validation of the otp
        handleError('OTP has expired');
        exit();
    }
    if ($accOTP_post != $studotp){
        handleError('Invalid OTP');
        exit();
    }

    echo json_encode(['response' => 1,
        'message' => 'OTP verified successfully. Please enter your new password.',
        'email' => $email,
        'otp' => $accOTP_post]);
    exit();
}


if ($action == 'PasswordChange'){
    header('Content-Type: application/json');
    $email = $_POST['email'];
    $accOTP_post = $_POST['verificationCode'];
    $chkUserAcc = mysqlQuery("SELECT * FROM tbl_accounts 
         WHERE email = ? and OTP = ?
                             and status = 1"
        , 'si', [$email, $accOTP_post]);
    if (count($chkUserAcc) !== 1){
        handleError('An error occurred. Please try again.');
        exit();
    }

    $studaccAdccdata = $chkUserAcc[0];
    update_password($studaccAdccdata['user_id']);


    echo json_encode(['response' => 1,
        'message' => 'Password changed successfully. Please login with your new password.']);
    exit();
}
