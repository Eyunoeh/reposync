<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



use Enqueue\AmqpLib\AmqpConnectionFactory;


//Create an instance; passing `true` enables exceptions
function mailer($subjectType,$bodyMessage, $emailAddress){
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = '';                     //SMTP username
        $mail->Password = '';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            // ENCRYPTION_SMTPS 465 Enable implicit TLS encryption
        $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


        $mail->setFrom('cc.riocarl.delacruz@cvsu.edu.ph',
            'Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus');
        $mail->addAddress($emailAddress, 'Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus');     //Add a recipient



        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subjectType;
        $mail->Body = $bodyMessage;

        $mail->send();
        return true;
    } catch (Exception $e) {

        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        //exit();
    }
}


$factory = new AmqpConnectionFactory([
    'host' => 'localhost',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhost' => '/',
]);

$context = $factory->createContext();
$queue = $context->createQueue('email_queue');
$context->declareQueue($queue);

$consumer = $context->createConsumer($queue);

echo " [*] Waiting for jobs...\n";

while (true) {
    if ($message = $consumer->receive()) {
        $job = json_decode($message->getBody(), true);
        $job_id = $job['job_id'];
        $recipient = $job['recipientEmail'];
        $subjectType = $job['subject_Type'];
        $mail_body = $job['mailBody'];

        echo " [x] Processing mail {$job_id}\n";


        try {
            mailer($subjectType,$mail_body,$recipient);
            echo " [*] Job finish\n";


            $consumer->acknowledge($message);
            echo " [*] Acknowledged message\n";
        } catch (Exception $e) {
            echo " [!] Error: " . $e->getMessage() . "\n";
            $consumer->reject($message); // Requeue the message after failure
            echo " [!] Rejected message\n";
        }
    } else {
        echo " [*] No message received\n";
        sleep(1); // Avoid tight loop
    }
}