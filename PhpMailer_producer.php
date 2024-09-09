<?php

use Enqueue\AmqpLib\AmqpConnectionFactory;
use Enqueue\AmqpTools\RabbitMqDlxDelayStrategy;



function email_queuing($subjectType, $bodyMessage, $emailAddress){
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



    $jobData = json_encode([
        'job_id' => uniqid(),
        'recipientEmail' => $emailAddress,
        'subject_Type' => $subjectType,
        'mailBody' => $bodyMessage,
    ]);

    $message = $context->createMessage($jobData);
    $context->createProducer()->send($queue, $message);
    return true;


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





?>

