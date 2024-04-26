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

                $file_first_name = str_replace(' ', '', $first_name);
                $file_last_name = str_replace(' ', '', $last_name);
                $new_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section.".pdf";
                $current_date_time = date('Y-m-d H:i:s');
                $narrative_status = 'OK';
                if($file_error === UPLOAD_ERR_OK) {

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
                    $report_pdf_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section;

                    if (convert_pdf_to_image($report_pdf_file_name)){
                        echo 1;
                        exit();
                    }
                    else{
                        echo 'Flip book Conversion error';
                    }
                }
                else {
                    echo 'file error';
                }
            }else {
                echo 'not pdf';
            }
        }else{
            echo 'empty file';
        }
    }else{
        echo 'form data are empty';
    }
    exit();
}
if ($action == 'get_narrativeReports') {
    $secret_key = 'TheSecretKey#02';

    function encrypt_data($data, $key) {
        $cipher = "aes-256-cbc";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    $sql = "SELECT * FROM narrativereports order by upload_date desc ";
    $result = $conn->query($sql);

    if ($result === false) {
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row["program"] . '</span>
                        </td>
                        <td class="p-3 text-end ">
                            <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>

                       
                        </td>
                      </tr>';
            }
        }
    }
    $conn->close();
}
