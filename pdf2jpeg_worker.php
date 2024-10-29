<?php
require 'vendor/autoload.php';
include 'functions.php';
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

use Enqueue\AmqpLib\AmqpConnectionFactory;

$factory = new AmqpConnectionFactory([
    'host' => 'localhost',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhost' => '/',
]);

$context = $factory->createContext();
$queue = $context->createQueue('pdf_conversion_queue');
$context->declareQueue($queue);

$consumer = $context->createConsumer($queue);

echo " [*] Waiting for jobs...\n";

while (true) {
    if ($message = $consumer->receive()) {
        $job = json_decode($message->getBody(), true);
        $job_id = $job['job_id'];
        $pdfPath = $job['pdf_path'];
        $file_name = $job['file_name'];
        $narrative_id = $job['narrative_id'];

        echo " [x] Processing PDF {$job_id}\n";
        mysqlQuery('UPDATE narrativereports SET convertStatus = 2 WHERE narrative_id = ?', 'i', [$narrative_id]);


        try {
            convertPDFtoJPEG($pdfPath, $file_name);

            mysqlQuery('UPDATE narrativereports SET narrativeConvertJobID = NULL, convertStatus = 3 WHERE narrative_id = ?', 'i', [$narrative_id]);
            echo " [*] Job finish\n";

            // Acknowledge the successful processing
            $consumer->acknowledge($message);
            echo " [*] Acknowledged message\n";
        } catch (Exception $e) {
            echo " [!] Error: " . $e->getMessage() . "\n";
            $consumer->reject($message); // Requeue the message after failure
            echo " [!] Rejected message\n";
            mysqlQuery('UPDATE narrativereports SET convertStatus = 4 WHERE narrative_id = ?', 'i', [$narrative_id]);

        }
    } else {
        echo " [*] No message received\n";
        sleep(1); // Avoid tight loop
    }
}


/**
 * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
 * @throws Exception
 */
function convertPDFtoJPEG($pdfPath, $file_name){
    $pdf = new \Spatie\PdfToImage\Pdf($pdfPath);//location of pdf in the server
    $pdf->format(\Spatie\PdfToImage\Enums\OutputFormat::Jpeg);
    $pdf->resolution(300);

    $numberOfPages = $pdf->pageCount();

    $basePath = "src/NarrativeReports_Images/";
    $subdirectoryName = str_replace(".pdf","",$file_name);
    if (!is_dir($basePath . $subdirectoryName)) {
        mkdir($basePath . $subdirectoryName, 0755);//location of flipbook pages in the server

    }


    try {
        for ($i = 1; $i <= $numberOfPages; $i++) {
            $outputPath = $basePath.$subdirectoryName.'/'.$file_name."_page_$i.jpeg";
            $pdf->selectPages($i)->save($outputPath);
        }
    }catch (Exception $e) {

        throw new Exception($e->getMessage());

    }
    return true;

}