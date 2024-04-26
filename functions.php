<?php
include 'vendor/autoload.php';
use \ConvertApi\ConvertApi;

function convert_pdf_to_image($file_name):bool{

    $basePath = "src/NarrativeReports_Images/";
    $subdirectoryName = $file_name;

    if (!is_dir($basePath . $subdirectoryName)) {
        mkdir($basePath . $subdirectoryName, 0755);

        ConvertApi::setApiSecret('1dLsWCbgR9f2Pgrw');
        $result = ConvertApi::convert('png', [
            'File' => 'src/NarrativeReportsPDF/'.$file_name.'.pdf',
            'FileName' => $file_name."_page",
            'ImageResolution' => '800',
        ], 'pdf'
        );

        $result->saveFiles($basePath.$subdirectoryName);
        return true;
    } else {
        return false;
    }
}
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function isPDF($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    return strtolower($extension) === 'pdf';
}
