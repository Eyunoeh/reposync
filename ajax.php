<?php
include_once 'DatabaseConn/databaseConn.php';
include_once 'functions.php';
session_start();
date_default_timezone_set('Asia/Manila');


$action = $_GET['action'];

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

    extract($_POST);
    $first_name = isset($_POST['first_name']) ? sanitizeInput($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitizeInput($_POST['last_name']) : '';
    $program = isset($_POST['program']) ? sanitizeInput($_POST['program']) : '';
    $section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
    $ojt_adviser = isset($_POST['ojt_adviser']) ? sanitizeInput($_POST['ojt_adviser']) : '';
    $school_id = isset($_POST['school_id']) && is_numeric($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';
    if ($first_name !== '' && $last_name !== '' && $program !== '' && $section !== '' && $ojt_adviser !== '' && $school_id !== '') {
        if(isset($_FILES['final_report_file'])) {
            $file_name = $_FILES['final_report_file']['name'];
            $file_temp = $_FILES['final_report_file']['tmp_name'];
            $file_type = $_FILES['final_report_file']['type'];
            $file_error = $_FILES['final_report_file']['error'];
            $file_size = $_FILES['final_report_file']['size'];

            if (isPDF($file_name)){
                $new_file_name = $first_name."_".$last_name."_".$program."_".$section.".pdf";
                $current_date_time = date('Y-m-d H:i:s');
                $narrative_status = 'OK';
                if($file_error === UPLOAD_ERR_OK) {
                    /*
                    $new_final_report = $conn->prepare("INSERT INTO narrativereports (stud_school_id, 
                              first_name, last_name, program, section, OJT_adviser,narrative_file_name, upload_date, file_status)
                              values (?,?,?,?,?,?,?,?,?)");

                    $new_final_report->bind_param("issssssss",
                        $school_id, $first_name, $last_name,
                        $program, $section, $ojt_adviser, $new_file_name,
                        $current_date_time, $narrative_status);


                    if (!$new_final_report->execute()){
                        echo 'query error';
                        exit();
                    }
                    $new_final_report->close();
                    $destination = "src/NarrativeReportsPDF/" . $new_file_name;
                    move_uploaded_file($file_temp, $destination);

                    //convert_pdf_to_image($new_file_name,$first_name."_".$last_name,$section,$program replace the true
                    */
                    sleep(5);
                    if (true){
                        echo 1;
                        exit();
                    }
                    else{
                        echo 'Flip book Conversion error';
                    }
                }
                else {
                    echo 2;
                }
            }else {
                echo 3;
            }
        }else{
            echo 4;
        }
    }else{
        echo 5;
    }
    exit();
}