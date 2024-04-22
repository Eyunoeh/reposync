<?php
session_start();
include 'vendor/autoload.php';
use \ConvertApi\ConvertApi;
$action = $_GET['action'];
function convert_pdf_to_image($file_name,$stud_name, $section,$program):bool{
    $basePath = "src/NarrativeReports_Images/";
    $subdirectoryName = $stud_name.'_'.$program."_".$section;

    if (!is_dir($basePath . $subdirectoryName)) {
        mkdir($basePath . $subdirectoryName, 0755);
        ConvertApi::setApiSecret('KFPZfRgPon0OpPHW');

        ConvertApi::setApiSecret('KFPZfRgPon0OpPHW');
        $result = ConvertApi::convert('png', [
            'File' => 'src/NarrativeReportsPDF/'.$file_name,
            'FileName' => $stud_name.'_'.$program."_".$section."_page",
            'ImageResolution' => '800',
        ], 'pdf'
        );

        $result->saveFiles($basePath.$subdirectoryName);
        return true;
    } else {
        return false;
    }
}



if ($action== 'signUp'){
    extract($_POST);
    echo 1;
}
if ($action== 'login'){
    $_SESSION['log_user_type'] = 'admin';
    extract($_POST);
    echo 1;
}

if ($action == 'addWeeklyReport'){
    extract($_POST);
    echo $newWeeklyReport;
}
if ($action == 'resubmitReport'){
    extract($_POST);
    echo $resubmitReport;

}
if ($action == 'newFinalReport'){

}