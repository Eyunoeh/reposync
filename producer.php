<?php
require 'vendor/autoload.php';
require 'functions.php';
use Enqueue\AmqpLib\AmqpConnectionFactory;
use Enqueue\AmqpTools\RabbitMqDlxDelayStrategy;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);



$fp = fsockopen("localhost", 5672, $errno, $errstr, 10);
if (!$fp) {
    echo "Error: $errstr ($errno)\n";
} else {
    echo "Connection successful!\n";
    fclose($fp);
}

$factory = new AmqpConnectionFactory([
    'host' => 'localhost',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhost' => '/',
]);







// Establish a connection to RabbitMQ
/*

$context = $factory->createContext();

$queue = $context->createQueue('pdf_conversion_queue');
$context->declareQueue($queue);



$jobData = json_encode([
    'job_id' => uniqid(),
    'pdf_path' => $pdf_file_path,
    'file_name' => $new_file_name,
    'narrative_id' => $narrative_id,
]);

$message = $context->createMessage($jobData);
$context->createProducer()->send($queue, $message);
*/
