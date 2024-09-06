<?php
require_once 'vendor/autoload.php';

$pdfPath = 'src/assets/NarrativeRerportFormat_SAMPLE.pdf';

$outputJpgPath = 'src/assets/a/';




function convertPDFtoJPEG($pdfPath, $file_name){
    $pdf = new \Spatie\PdfToImage\Pdf($pdfPath);
    $pdf->format(\Spatie\PdfToImage\Enums\OutputFormat::Jpeg);

    $numberOfPages = $pdf->pageCount();

    $basePath = "src/NarrativeReports_Images/";
    $subdirectoryName = str_replace(".pdf","",$file_name);
    if (!is_dir($basePath . $subdirectoryName)) {
        mkdir($basePath . $subdirectoryName, 0755);

    }


    try {
        for ($i = 1; $i <= $numberOfPages; $i++) {
            $outputPath = $basePath.$subdirectoryName.'/'.$file_name."_page_$i.jpeg";
            $pdf->selectPages($i)->save($outputPath);
        }
    }catch (Exception $e) {
        handleError($e->getMessage());
    }
    return true;


}