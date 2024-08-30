<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
function email_notif_sender($subjectType,$bodyMessage, $emailAddress){
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'cc.riocarl.delacruz@cvsu.edu.ph';                     //SMTP username
        $mail->Password = 'lmsuohilwsildxex';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            // ENCRYPTION_SMTPS 465 Enable implicit TLS encryption
        $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('cc.riocarl.delacruz@cvsu.edu.ph', 'Reposync: An Online Narrative Report Managemment System
    for Cavite State University - Carmona Campus');
        $mail->addAddress($emailAddress, 'Reposync: An Online Narrative Report Managemment System
    for Cavite State University - Carmona Campus');     //Add a recipient


        /*    //Attachments
            $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name*/

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subjectType;
        $mail->Body = $bodyMessage;

        $mail->send();
        return true;
    } catch (Exception $e) {

        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        exit();
    }
}

function getRecipient($user_id){
    include 'DatabaseConn/databaseConn.php';
    $getEmail = "SELECT email from tbl_accounts where user_id = ?";
    $stmt = $conn->prepare($getEmail);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['email'];
}
/*$subjectType = "Reposync Notification";
$messageBody = "Weekly Report Status has been updated";
$recipient =  getRecipient(6);
if (email_notif_sender($subjectType, $messageBody, $recipient)){
    echo 'email success';
}*/

// send_email.php

/*if (isset($argv[1])) {
    $data = json_decode($argv[1], true);
    $subjectType = $data['subjectType'];
    $bodyMessage = $data['bodyMessage'];
    $recipient = $data['recipient'];

    // Call your email sending function
    email_notif_sender($subjectType, $bodyMessage, $recipient);
}*/

?>

