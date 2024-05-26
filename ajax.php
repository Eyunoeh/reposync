<?php
/*
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
*/


session_start();
date_default_timezone_set('Asia/Manila');
include_once 'DatabaseConn/databaseConn.php';
include_once 'FlipbookFunctions.php';
include 'functions.php';

$action = $_GET['action'];
extract($_POST);




function countFileComments($file_id){
    include "DatabaseConn/databaseConn.php";

    $sql = "SELECT COUNT(*) AS comment_count FROM tbl_revision WHERE file_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $file_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    $comment_count = $row['comment_count'];

    return $comment_count;
}
if ($action == 'login') {

    $log_email = isset($_POST['log_email']) ? sanitizeInput($_POST['log_email']) : '';
    $log_password = $_POST['log_password'] ?? '';
    if ($log_email !== '' && $log_password !== '') {

        $fetch_acc = "SELECT user_id, password FROM tbl_accounts WHERE email = ? and status = 'active'";
        $stmt = $conn->prepare($fetch_acc);
        $stmt->bind_param("s", $log_email);
        $stmt->execute();

        if ($stmt->error) {
            echo "Error executing statement: " . $stmt->error;
            exit;
        }

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];
            $hashed_password = $row['password'];
            if (password_verify($log_password, $hashed_password)) {
                $fetch_user_info = "SELECT user_type FROM tbl_user_info WHERE user_id = ?";
                $stmt_user_info = $conn->prepare($fetch_user_info);
                $stmt_user_info->bind_param('i', $user_id);
                $stmt_user_info->execute();

                if ($stmt_user_info->error) {
                    echo "Error executing statement: " . $stmt_user_info->error;
                    exit; // Stop execution
                }

                $result_user_info = $stmt_user_info->get_result();

                if ($result_user_info->num_rows == 1) {
                    $row_user_info = $result_user_info->fetch_assoc();
                    $_SESSION['log_user_id'] = $user_id;
                    $_SESSION['log_user_type'] = $row_user_info['user_type'];
                    echo 1; // Login successful
                } else {
                    echo 2; // Error: User type not found
                }
            } else {
                echo 2; // Error: Incorrect password
            }
        } else {
            echo 2; // Error: User not found
        }
    } else {
        echo 2; // Error: Email or password empty
    }

}




if ($action == 'addWeeklyReport') {
    $user_id = isset($_POST['stud_user_id']) ? sanitizeInput($_POST['stud_user_id']) : '';
    if ($user_id !== '') {
        if (isset($_FILES['weeklyReport'])) {
            if (isPDF($_FILES['weeklyReport']['name'])) {
                if ($_FILES['weeklyReport']['error'] === UPLOAD_ERR_OK) {
                    $get_weeklyReportCount = "SELECT COUNT(*) AS weeklyReportCount FROM weeklyReport WHERE stud_user_id = ?";
                    $report_count_stmt = $conn->prepare($get_weeklyReportCount);
                    $report_count_stmt->bind_param('i', $user_id);
                    $report_count_stmt->execute();
                    $res = $report_count_stmt->get_result();
                    $weeklyReport_count = $res->fetch_assoc();



                    $get_User_info = "SELECT * FROM tbl_user_info WHERE user_id = ?";
                    $stmt = $conn->prepare($get_User_info);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    $file_name = '';

                    if ($weeklyReport_count['weeklyReportCount'] > 0) {
                        $weeklyReport = $weeklyReport_count['weeklyReportCount'] + 1;
                        $file_name = $row['school_id'] . "_WeeklyReport_week_" . $weeklyReport . ".pdf";

                    } else {
                        $file_name = $row['school_id'] . "_WeeklyReport_week_1.pdf";
                    }
                    $status = "Pending";

                    $insert_weekly_report = "INSERT INTO weeklyReport (stud_user_id, weeklyFileReport, upload_date, upload_status) 
                         VALUES (?, ?, CURRENT_TIMESTAMP, ?)";
                    $stmt = $conn->prepare($insert_weekly_report);
                    $stmt->bind_param("iss", $user_id, $file_name, $status);
                    $stmt->execute();
                    $file_id = $stmt->insert_id;
                    insertActivityLog('upload',$file_id);
                    $temp_file = $_FILES['weeklyReport']['tmp_name'];
                    $final_destination = 'src/StudentWeeklyReports/' . $file_name;
                    if (move_uploaded_file($temp_file, $final_destination)) {
                        echo 1;
                    } else {
                        echo 'move_error';
                    }
                    exit();
                } else {
                    echo 'upload_error';
                }
            } else {
                echo 'format_error';
            }
        } else {
            echo 'file_missing';
        }
    } else {
        echo 'missing_user_id';
    }
}
if ($action == 'getWeeklyReports'){
    $user_id = $_SESSION['log_user_id'];
    $week = 1;
    $sql = "SELECT file_id, upload_status, weeklyFileReport
        FROM weeklyReport
        WHERE stud_user_id = ?
        ORDER BY upload_date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $filename = $row['weeklyFileReport'];

        preg_match('/week_([0-9]+)\.pdf/', $filename, $matches);
        $week_number = isset($matches[1]) ? (int)$matches[1] : '';

        $formatted_week = ($week_number !== '') ? "Week " . $week_number : '';
        $status = $row['upload_status'];

        switch ($status) {
            case 'pending':
                $formattedStatus = 'Pending';
                $status_color = 'text-warning';
                break;
            case 'revision':
                $formattedStatus = 'With Revision';
                $status_color = 'text-info';

                break;
            case 'approved':
                $formattedStatus = 'Approved';
                $status_color = 'text-success';
                break;
            default:
                $formattedStatus = 'Unknown';
                break;
        }
        echo ' <tr class="border-b border-dashed last:border-b-0">

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">' . $formatted_week . '</span>
                                </td>

                                <td class="p-3 pr-0 ">
                                    <span class="'.$status_color.' font-semibold text-light-inverse text-md/normal">' . $formattedStatus . '</span>
                                </td>
                                <td class="p-3 pr-0 " >
                                    <div class="indicator hover:cursor-pointer" data-report-comment-id="'.$row['file_id'].'" onclick="openModalForm(\'comments\');getComments(this.getAttribute(\'data-report-comment-id\'))">
                                        <span class="indicator-item badge badge-neutral"  data-journal-comment-id="3" id="journal_comment_2">'.countFileComments($row['file_id']).'</span>
                                        <a class="font-semibold text-light-inverse text-md/normal"><i class="fa-regular fa-comment"></i></a>
                                    </div>
                                </td>
                                <td class="p-3 pr-0  ">
                                    <div  class="tooltip tooltip-bottom"  data-tip="View">
                                        <a href="StudentWeeklyReports/' . $row['weeklyFileReport'] . '" target="_blank" class=" text-light-inverse text-md/normal mb-1 hover:cursor-pointer font-semibold
                                    transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"  ><i class="fa-regular fa-eye"></i></a>
                                    </div>
                                    <div class="tooltip tooltip-bottom" data-tip="Resubmit">
                                        <a class="text-light-inverse text-md/normal mb-1 hover:cursor-pointer font-semibold
                                transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"  data-report_id="' . $row['file_id'] . '" onclick="openModalForm(\'resubmitReport\');resubmitWeeklyReport(this.getAttribute(\'data-report_id\'))"><i class="fa-solid fa-pen-to-square"></i></a>
                                    </div>
                                </td>
                            </tr>';
    }
}

if ($action == 'updateWeeklyreportStat'){
    $file_id = $_GET['file_id'] ?? '';
    if ($file_id !== ''){
        $sql = "SELECT * FROM weeklyreport where file_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $file_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $filename = $row['weeklyFileReport'];

            preg_match('/week_([0-9]+)\.pdf/', $filename, $matches);
            $week_number = isset($matches[1]) ? (int)$matches[1] : '';

            $row['weeklyFileReport'] = 'Week '.$week_number;

            header('Content-Type: application/json');
            echo json_encode($row);
        }else{
            echo 'No result';
        }
    }
}



if ($action == 'resubmitReport'){
    $file_id = isset($_POST['file_id']) ? sanitizeInput($_POST['file_id']) : '';
    if ($file_id !== '') {
        if (isset($_FILES['resubmitReport'])) {
            if (isPDF($_FILES['resubmitReport']['name'])) {
                if ($_FILES['resubmitReport']['error'] === UPLOAD_ERR_OK) {
                    $get_weeklyReport = "SELECT * FROM weeklyReport WHERE file_id = ?";
                    $stmt = $conn->prepare($get_weeklyReport);
                    $stmt->bind_param("i", $file_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $file_path = 'src/StudentWeeklyReports/' . $row['weeklyFileReport'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                        move_uploaded_file($_FILES['resubmitReport']['tmp_name'], $file_path);
                    }
                    else{
                        exit();
                    }
                    insertActivityLog('resubmit', $file_id);
                    echo 1;
                } else {
                    echo 'upload_error';
                }
            } else {
                echo 'format_error';
            }
        } else {
            echo 'file_missing';
        }
    } else {
        echo 'missing_file_id';
    }
}
if ($action == 'getUploadLogs'){
    $user_id = $_SESSION['log_user_id'];

    $sql = "SELECT a.*, w.stud_user_id, w.weeklyFileReport
            FROM activity_logs AS a
            JOIN weeklyReport AS w ON a.file_id = w.file_id
            WHERE w.stud_user_id = ?
            ORDER BY a.activity_date DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $filename = $row['weeklyFileReport'];

        preg_match('/week_([0-9]+)\.pdf/', $filename, $matches);
        $week_number = isset($matches[1]) ? (int)$matches[1] : '';

        $formatted_week = ($week_number !== '') ? "Week " . $week_number : '';
        $formatted_date_time = date("M d, Y g:i A", strtotime($row['activity_date']));


        echo '  <tr class="border-b border-dashed last:border-b-0">

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">'.$formatted_week.'</span>
                                </td>

                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">'.$formatted_date_time .'</span>
                                </td>
                                <td class="p-3 pr-0 ">
                                    <span class="font-semibold text-light-inverse text-md/normal">'. $row['activity_type'] .'</span>
                                </td>

                            </tr>';
    }
}


if ($action == 'newFinalReport'){
    $first_name = isset($_POST['first_name']) ? sanitizeInput($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitizeInput($_POST['last_name']) : '';
    $program = isset($_POST['program']) ? sanitizeInput($_POST['program']) : '';
    $section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
    $stud_sex = isset($_POST['stud_Sex']) ? sanitizeInput($_POST['stud_Sex']) : '';
    $ojt_adviser = isset($_POST['ojt_adviser']) ? sanitizeInput($_POST['ojt_adviser']) : '';
    $school_id = isset($_POST['school_id']) && is_numeric($_POST['school_id']) && check_uniq_stud_id($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';

        if ($first_name !== '' && $stud_sex !== '' && $last_name !== '' && $program !== '' && $section !== '' && $ojt_adviser !== '' && $school_id !== '') {
            if (isset($_FILES['final_report_file'])) {
                $file_name = $_FILES['final_report_file']['name'];
                $file_temp = $_FILES['final_report_file']['tmp_name'];
                $file_type = $_FILES['final_report_file']['type'];
                $file_error = $_FILES['final_report_file']['error'];
                $file_size = $_FILES['final_report_file']['size'];

                if (isPDF($file_name)) {
                    $file_first_name = str_replace(' ', '', $first_name);
                    $file_last_name = str_replace(' ', '', $last_name);
                    $new_file_name = $file_first_name . "_" . $file_last_name . "_" . $program . "_" . $section . "_" . $school_id . ".pdf";
                    $current_date_time = date('Y-m-d H:i:s');
                    $narrative_status = 'OK';
                    if ($file_error === UPLOAD_ERR_OK) {
                        try {
                            $new_final_report = $conn->prepare("INSERT INTO narrativereports (stud_school_id, OJT_adviser_ID, sex,
                                      first_name, last_name, program, section, narrative_file_name, upload_date, file_status)
                                      values (?,?,?,?,?,?,?,?,?,?)");

                            $new_final_report->bind_param("sissssssss",
                                $school_id, $ojt_adviser,$stud_sex, $first_name, $last_name,
                                $program, $section,  $new_file_name,
                                $current_date_time, $narrative_status);


                            if (!$new_final_report->execute()) {
                                echo 'query error';
                                exit();
                            }
                            $new_final_report->close();
                            $destination = "src/NarrativeReportsPDF/" . $new_file_name;
                            move_uploaded_file($file_temp, $destination);
                            $report_pdf_file_name = $file_first_name . "_" . $file_last_name . "_" . $program . "_" . $section . "_" . $school_id;

                            if (convert_pdf_to_image($report_pdf_file_name)) {
                                echo 1;
                                exit();
                            } else {
                                echo 'Flip book Conversion error';
                            }
                        }catch (Exception $e) {
                            echo "Some error occurred when  uploading pleas contact developer";
                        }
                    } else {
                        echo 'file error';
                    }
                } else {
                    echo 'not pdf';
                }
            } else {
                echo 'empty file';
            }
        } else {
            echo 'form data are empty';
        }
        exit();
}
if ($action == 'get_narrativeReports') {
    if (!isset($_SESSION['log_user_id'])){
        exit();
    }
    $selectProgram = $_GET['program'];

    $sql = "SELECT narrativereports.*, tbl_user_info.user_id,
       tbl_user_info.first_name AS adv_first_name, 
       tbl_user_info.last_name AS adv_last_name
FROM narrativereports
JOIN tbl_user_info ON narrativereports.OJT_adviser_ID = tbl_user_info.user_id
WHERE narrativereports.file_status = 'OK' AND narrativereports.program = ?
ORDER BY narrativereports.upload_date DESC;

";
    $getNarrtivesStmt = $conn->prepare($sql);
    $getNarrtivesStmt->bind_param('s', $selectProgram);
    $getNarrtivesStmt->execute();
    $result = $getNarrtivesStmt->get_result();
    $number = 1;
    if ($result === false) {
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                 if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'adviser') {
                    echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                       <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">' . $row['stud_school_id'] . '</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row['adv_first_name'] . ' ' . $row['adv_last_name'] . '</span>
                        </td>
                      
                
                        <td class="p-3 text-end ">
                            <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>';
                }
                 if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'admin') {
                    echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">' . $row['stud_school_id'] . '</span>
                        </td>
                        <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                        </td>
                        <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal  break-words">' . $row['adv_first_name'] . ' ' . $row['adv_last_name'] . '</span>
                        </td>
                    
                  
                        <td class="p-3 text-end ">
                            <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                            <a onclick="openModalForm(\'EditNarrative\');editNarrative(this.getAttribute(\'data-narrative\'))" id="archive_narrative" data-narrative="' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                      </tr>';
                }
            }
        }
    }
    $conn->close();
}
if ($action == 'narrativeReportsJson'){

    $narrative_id = decrypt_data($_GET['narrative_id'], $secret_key);

    $sql = "SELECT * FROM narrativereports WHERE narrative_id = ? ORDER BY upload_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $narrative_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $row['narrative_id'] = encrypt_data($row['narrative_id'], $secret_key);

            header('Content-Type: application/json');
            echo json_encode($row);
        } else {
            echo "Error: No data found for the given narrative ID.";
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

if ($action === 'UpdateNarrativeReport'){

    $first_name = isset($_POST['first_name']) ? sanitizeInput($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitizeInput($_POST['last_name']) : '';
    $program = isset($_POST['program']) ? sanitizeInput($_POST['program']) : '';
    $section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
    $ojt_adviser = isset($_POST['ojt_adviser']) ? sanitizeInput($_POST['ojt_adviser']) : '';
    $stud_sex = isset($_POST['stud_Sex']) ? sanitizeInput($_POST['stud_Sex']) : '';
    $school_id = isset($_POST['school_id']) && is_numeric($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';
    $narrative_id = isset($_POST['narrative_id']) ? sanitizeInput($_POST['narrative_id']) : '';
    if ($first_name !== '' && $last_name !== '' && $stud_sex !== '' && $program !== '' && $section !== '' && $ojt_adviser !== '' && $school_id !== ''  && $narrative_id !== '') {
        $file_first_name = str_replace(' ', '', $first_name);
        $file_last_name = str_replace(' ', '', $last_name);
        $new_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section."_".$school_id.".pdf";
        $current_date_time = date('Y-m-d H:i:s');
        $narrative_status = 'OK';
        $narrative_id = decrypt_data($narrative_id,$secret_key);
        $old_filename = '';
        $sql = "SELECT * FROM narrativereports WHERE narrative_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $narrative_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $old_filename = $row['narrative_file_name'];
            }
        }
        $update_final_report = $conn->prepare("UPDATE narrativereports
                                      SET stud_school_id = ?,
                                          OJT_adviser_ID = ?,
                                          sex = ?,
                                          first_name = ?,
                                          last_name = ?,
                                          program = ?,
                                          section = ?,
                                          
                                          narrative_file_name = ?,
                                          upload_date = ?,
                                          file_status = ?
                                      WHERE narrative_id = ?");
        $update_final_report->bind_param("sissssssssi",
            $school_id, $ojt_adviser,$stud_sex,$first_name, $last_name,
            $program, $section, $new_file_name,
            $current_date_time, $narrative_status,$narrative_id);


        if (!$update_final_report->execute()){
            echo 'query error';
            exit();
        }else{
            if (isset($_FILES['final_report_file']) && $_FILES['final_report_file']['error'] === UPLOAD_ERR_OK){
                //replace existing by deleting and converting new

                $file_name = $_FILES['final_report_file']['name'];
                $file_temp = $_FILES['final_report_file']['tmp_name'];
                $file_type = $_FILES['final_report_file']['type'];
                $file_error = $_FILES['final_report_file']['error'];
                $file_size = $_FILES['final_report_file']['size'];

                if (isPDF($file_name)){
                    $pdf = 'src/NarrativeReportsPDF/'.$old_filename;
                    $flipbook_page_dir = 'src/NarrativeReports_Images/'. str_replace('.pdf','',$old_filename);
                    if (!delete_pdf($pdf) or !deleteDirectory($flipbook_page_dir)){
                        echo 'dir not deleted';
                        exit();
                    }

                    $file_first_name = str_replace(' ', '', $first_name);
                    $file_last_name = str_replace(' ', '', $last_name);
                    $new_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section."_".$school_id.".pdf";
                    $pdf_file_path = "src/NarrativeReportsPDF/" . $new_file_name;
                    move_uploaded_file($file_temp, $pdf_file_path);
                    $report_pdf_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section."_".$school_id;
                    if (convert_pdf_to_image($report_pdf_file_name)){
                        echo 1;
                        exit();
                    }
                    else{
                        echo 'error Conversion';
                    }
                }

            }
            else {
                //Nirerename lang ung pdf sa src/NarrativeReportsPDF/  tapos directory
                // at ung lamang ng directory sa src/NarrativeReports_Images/
                // ung rename nito naka base lang sa laman ng database
                // ang purpose para ma reuse ang existing files

                $narrative_reportPDF_path = 'src/NarrativeReportsPDF/';
                $narrative_reportIMG_path = 'src/NarrativeReports_Images/';
                if (is_dir($narrative_reportPDF_path)) {
                    if ($handle = opendir($narrative_reportPDF_path)) {
                        // Rename the PDF file
                        while (false !== ($file = readdir($handle))) {
                            if (pathinfo($file, PATHINFO_EXTENSION) == 'pdf' && $file == $old_filename) {
                                // Rename the pdf file
                                $oldFilePath = $narrative_reportPDF_path . $old_filename;
                                $newFilePath = $narrative_reportPDF_path . $new_file_name;
                                if (rename($oldFilePath, $newFilePath)) {
                                    // Rename the flip book image directory
                                    $old_flipbook_page_directory = str_replace('.pdf', '', $old_filename);
                                    $new_flipbook_page_directory = str_replace('.pdf', '', $new_file_name);
                                    if (is_dir($narrative_reportIMG_path . $old_flipbook_page_directory)) {
                                        if (rename($narrative_reportIMG_path . $old_flipbook_page_directory, $narrative_reportIMG_path . $new_flipbook_page_directory)) {
                                            // Rename image files inside the flip book directory
                                            if (is_dir($narrative_reportIMG_path . $new_flipbook_page_directory)) {
                                                if ($handle_img = opendir($narrative_reportIMG_path . $new_flipbook_page_directory)) {
                                                    while (false !== ($file_img = readdir($handle_img))) {
                                                        if ($file_img != "." && $file_img != "..") {
                                                            // Construct the new filename based on the new directory name pattern
                                                            $oldImagePath = $narrative_reportIMG_path . $new_flipbook_page_directory . "/" . $file_img;
                                                            $newImageName = str_replace($old_flipbook_page_directory, $new_flipbook_page_directory, $file_img);
                                                            $newImagePath = $narrative_reportIMG_path . $new_flipbook_page_directory . "/" . $newImageName;

                                                            // Rename the image file
                                                            if (!rename($oldImagePath, $newImagePath)) {
                                                                echo "* Error renaming image file.";
                                                                echo 0;
                                                                exit();
                                                            }
                                                        }
                                                    }
                                                    closedir($handle_img);
                                                } else {
                                                    echo "Error opening image directory.";
                                                    echo 0;
                                                    exit();
                                                }
                                            } else {
                                                echo "New directory does not exist.";
                                                echo 0;
                                                exit();
                                            }
                                        } else {
                                            echo "Error renaming directory.";
                                            echo 0;
                                            exit();
                                        }
                                    } else {
                                        echo "Directory does not exist.";
                                        echo 0;
                                        exit();
                                    }
                                } else {
                                    echo "Error renaming PDF file.";
                                    echo 0;
                                    exit();
                                }
                            }
                        }
                        closedir($handle);
                    } else {
                        echo "Error opening PDF directory.";
                        echo 0;
                        exit();
                    }
                } else {
                    echo "PDF directory does not exist.";
                    echo 0;
                    exit();
                }
                echo 1;
                exit();
            }
        }
    }
}

if ($action == 'ArchiveNarrativeReport'){

    $narrative_id = isset($_POST['narrative_id']) ? sanitizeInput($_POST['narrative_id']) : '';
    if ($narrative_id !== ''){
        $narrative_id = decrypt_data($narrative_id, $secret_key);
        $file_status = 'Archived';
        $archive_final_report = $conn->prepare("UPDATE narrativereports
                                      SET 
                                          file_status = ?
                                      WHERE narrative_id = ?");
        $archive_final_report->bind_param('si',$file_status, $narrative_id);
        if (!$archive_final_report->execute()){
            echo 'Query Error';
            exit();
        }
        echo 1;
        exit();
    }else{
        echo 2;// empty id
        exit();
    }
}

if ($action == 'newUser') {


    $user_first_name = isset($_POST['user_Fname']) ? sanitizeInput($_POST['user_Fname']) : '';
    $user_last_name = isset($_POST['user_Lname']) ? sanitizeInput($_POST['user_Lname']) : '';
    $user_middle_name = isset($_POST['user_Mname']) ? sanitizeInput($_POST['user_Mname']) : 'N/A';
    $user_shc_id = isset($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';
    $user_sex = isset($_POST['user_Sex']) ? sanitizeInput($_POST['user_Sex']) : '';
    $user_contact_number = isset($_POST['contactNumber']) ? sanitizeInput($_POST['contactNumber']) : '';
    $user_address = isset($_POST['user_address']) ? sanitizeInput($_POST['user_address']) : '';
    $user_program = isset($_POST['stud_Program']) ? sanitizeInput($_POST['stud_Program']) : '';
    $user_section = isset($_POST['stud_Section']) ? sanitizeInput($_POST['stud_Section']) : '';
    $stud_adviser = isset($_POST['stud_adviser']) ? sanitizeInput($_POST['stud_adviser']) : '';
    $stud_compName = isset($_POST['stud_compName']) ? sanitizeInput($_POST['stud_compName']) : 'N/A';
    $stud_trainingHours = isset($_POST['stud_TrainingHours']) ? sanitizeInput($_POST['stud_TrainingHours']) : 'N/A';
    $user_email = isset($_POST['user_Email']) ? sanitizeInput($_POST['user_Email']) : '';
    $user_type = isset($_POST['user_type']) ?sanitizeInput($_POST['user_type']) : '';
    $user_password = isset($_POST['user_password']) && sanitizeInput($_POST['user_password']) ? $_POST['user_password'] :'';

    if ($user_first_name !== '' &&
        $user_last_name !== '' &&
        $user_shc_id !== '' &&
        $user_sex !== '' &&
        $user_contact_number !== '' &&
        $user_address !== '' &&
        $user_type !== '' &&
        $user_email !== '') {
        $check_sql = "SELECT user_id FROM tbl_user_info WHERE school_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $newStud_shc_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // Student ID already exists, echo "2" and exit
            echo 2;
            exit();
        }

        if ( $user_program !== '' && $user_section !== '' && $user_password == '') {
            $user_password = generatePassword($user_shc_id);
        }

        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);


        $insert_sql = "INSERT INTO tbl_user_info (first_name, middle_name, last_name, address, contact_number, school_id, sex, user_type) 
                VALUES (?, ?, ? , ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssssssss", $user_first_name, $user_middle_name,$user_last_name, $user_address, $user_contact_number,
            $user_shc_id, $user_sex,$user_type);
        if (!$insert_stmt->execute()) {
            if ($conn->errno == 1062) {
                echo 'Error: Email already exists.';
            } else {
                echo 'Error: ' . $conn->error;
            }
        }



        $user_id = $insert_stmt->insert_id;


        $account_sql = "INSERT INTO tbl_accounts (user_id, email, password, status) 
                VALUES (?, ?, ?, 'active')";
        $account_stmt = $conn->prepare($account_sql);
        $account_stmt->bind_param("iss", $user_id, $user_email, $hashed_password);
        $account_stmt->execute();

        if ( $user_program !== '' && $user_section !== '' && $user_type == 'student' && $stud_adviser !== '')
        {
            $student_sql = "INSERT INTO tbl_students (user_id, program_id, section_id, company_name, training_hours) 
                VALUES (?, ?, ?, ?, ?)";
            $student_stmt = $conn->prepare($student_sql);
            $student_stmt->bind_param("iiiss", $user_id, $user_program, $user_section,$stud_compName, $stud_trainingHours);
            $student_stmt->execute();
            $advisory_sql = "INSERT INTO advisory_list (adv_sch_user_id, stud_sch_user_id) VALUES (?,?)";
            $advisory_stmt = $conn->prepare($advisory_sql);
            $advisory_stmt->bind_param('ii', $stud_adviser, $user_id);
            $advisory_stmt->execute();
        }
        echo 1;

    } else {
        // Output error message if any required field is empty
        echo 'Some required fields are empty.';
    }
}
if ($action == 'getStudentsList'){
    $fetch_enrolled_stud = "SELECT 
                                u.user_id,
                                u.first_name,
                                u.last_name,
                                u.address,
                                u.contact_number,
                                u.sex,
                                u.school_id,
                                u.user_type,
                                s.program_id,
                                p.program_code,
                                p.program_name,
                                a.acc_id,
                                a.email,
                                a.password,
                                a.status,
                                a.date_created,
                                se.section_id,
                                se.section,
                                ad.adv_sch_user_id as adviserUserId,
                                s.*,
                                IFNULL(CONCAT(adv.first_name, ' ', adv.last_name), 'N/A') AS adviser_name
                            FROM 
                                tbl_students s
                            JOIN 
                                tbl_user_info u ON s.user_id = u.user_id
                            JOIN 
                                program p ON s.program_id = p.program_id
                            JOIN 
                                tbl_accounts a ON s.user_id = a.user_id
                            JOIN 
                                section se ON s.section_id = se.section_id
                            LEFT JOIN 
                                advisory_list ad ON s.user_id = ad.stud_sch_user_id
                            LEFT JOIN 
                                tbl_user_info adv ON ad.adv_sch_user_id = adv.user_id
                            WHERE 
                                a.status = 'active' 
                                AND u.user_type = 'student'
                            ORDER BY 
                                a.date_created desc";
    $result = $conn->query($fetch_enrolled_stud);
    if ($result === false){
        echo "Error: " . $conn->error;
    }
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            if ($_SESSION['log_user_type'] == 'adviser' and$_SESSION['log_user_id'] == $row['adviserUserId']){
                echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['school_id'].'</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['first_name'].' '.$row['last_name'].'</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['adviser_name'].'</span>
                        </td>

                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['program_code'].'</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['company_name'].'</span>
                        </td>
                        
                        <td class="p-3 text-end">
                            <a href="#" onclick="openModalForm(\'editStuInfo\');editUserStud_Info(this.getAttribute(\'data-id\'))" data-id="' . urlencode(encrypt_data($row['user_id'], $secret_key)) .'" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>';
            }
            else if ($_SESSION['log_user_type'] == 'admin'){
                echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['school_id'].'</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['first_name'].' '.$row['last_name'].'</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['adviser_name'].'</span>
                        </td>

                
                        
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['program_code'].'</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['company_name'].'</span>
                        </td>
                        <td class="p-3 text-end">
                            <a href="#" onclick="openModalForm(\'editStuInfo\');editUserStud_Info(this.getAttribute(\'data-id\'))" data-id="' . urlencode(encrypt_data($row['user_id'], $secret_key)) .'" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>';
            }
        }
    }
}


if ($action == 'getStudInfoJson') {
    $user_id = decrypt_data($_GET['data_id'], $secret_key);

    $fetch_enrolled_stud = "SELECT 
                                u.user_id,
                                u.first_name,
                                u.last_name,
                                u.address,
                                u.contact_number,
                                u.sex,
                                u.school_id,
                                s.program_id,
                                p.program_code,
                                p.program_name,
                                a.acc_id,
                                a.email,
                                a.password,
                                a.date_created,
                                a.status,
                                se.section_id,
                                se.section,
                                s.company_name, 
                                s.training_hours,
                                IFNULL(ad.adv_sch_user_id, '') AS adviser_id,
                                IFNULL(CONCAT(adv.first_name, ' ', adv.last_name), 'No adviser') AS adviser_name
                            FROM 
                                tbl_students s
                            JOIN 
                                tbl_user_info u ON s.user_id = u.user_id
                            JOIN 
                                program p ON s.program_id = p.program_id
                            JOIN 
                                tbl_accounts a ON s.user_id = a.user_id
                            JOIN 
                                section se ON s.section_id = se.section_id
                            LEFT JOIN 
                                advisory_list ad ON s.user_id = ad.stud_sch_user_id
                            LEFT JOIN 
                                tbl_user_info adv ON ad.adv_sch_user_id = adv.user_id
                            WHERE u.user_id = ?
                            ORDER BY 
                                a.date_created ASC
                            LIMIT 1";

    $stmt = $conn->prepare($fetch_enrolled_stud);
    $stmt->bind_param("i", $user_id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        $error = "Error: " . $stmt->error;
        header('Content-Type: application/json');
        echo json_encode(array("error" => $error));
    } else {
        $student = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($student);
    }
    $stmt->close();
}





if ($action == 'updateUserInfo'){
    $editUser_first_name = isset($_POST['user_Fname']) ? sanitizeInput($_POST['user_Fname']) : '';
    $editUser_last_name = isset($_POST['user_Lname']) ? sanitizeInput($_POST['user_Lname']) : '';
    $editUser_shc_id = isset($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';
    $editUser_sex = isset($_POST['user_Sex']) ? sanitizeInput($_POST['user_Sex']) : '';
    $editUser_contact_number = isset($_POST['contactNumber']) ? sanitizeInput($_POST['contactNumber']) : '';
    $editUser_address = isset($_POST['user_address']) ? sanitizeInput($_POST['user_address']) : '';
    $editStud_program = isset($_POST['stud_Program']) ? sanitizeInput($_POST['stud_Program']) : '';
    $editStud_section = isset($_POST['stud_Section']) ? sanitizeInput($_POST['stud_Section']) : '';
    $editStud_adviser = isset($_POST['stud_adviser']) ? sanitizeInput($_POST['stud_adviser']) : '';
    $editUser_email = isset($_POST['user_Email']) ? sanitizeInput($_POST['user_Email']) : '';
    $editUser_user_id = isset($_POST['user_id']) ? sanitizeInput($_POST['user_id']) : '';
    $edituser_type = isset($_POST['user_type']) && sanitizeInput($_POST['user_type']) ? $_POST['user_type']: '';
    $editStud_compName = isset($_POST['stud_compName']) ? sanitizeInput($_POST['stud_compName']) : 'N/A';
    $edit_trainingHours = isset($_POST['stud_TrainingHours']) ? sanitizeInput($_POST['stud_TrainingHours']) : 'N/A';


    if ($editUser_first_name !== '' &&
        $editUser_last_name !== '' &&
        $editUser_shc_id !== '' &&
        $editUser_sex !== '' &&
        $editUser_contact_number !== '' &&
        $editUser_address !== '' &&
        $editUser_user_id !== '' &&
        $editUser_email !== ''&&
        $edituser_type !== '') {


        // Proceed with updating student information
        $sql = "UPDATE tbl_user_info 
                SET first_name = ?, 
                    last_name = ?, 
                    address = ?, 
                    contact_number = ?, 
                    sex = ?, 
                    school_id = ?,
                    user_type = ?
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $editUser_first_name, $editUser_last_name, $editUser_address, $editUser_contact_number, $editUser_sex, $editUser_shc_id,$edituser_type, $editUser_user_id);
        $stmt->execute();


        if ($stmt->errno == 1062) {
            echo "School id already exist";
            exit;
        } else if ($stmt->errno) {

            echo 'Error: ' . $stmt->error;
            exit;
        }
        if ($editStud_program !== '' && //execute only if the admin editing student type user
            $editStud_section !== '' && $edituser_type == 'student'){
            $update_stud_info = "UPDATE tbl_students 
                            SET program_id = ?, 
                                section_id = ? ,
                                company_name= ?, 
                                training_hours = ?
                            WHERE user_id = ?";
            $stmt_update_info = $conn->prepare($update_stud_info);
            $stmt_update_info->bind_param("iissi", $editStud_program, $editStud_section, $editStud_compName, $edit_trainingHours , $editUser_user_id);
            $stmt_update_info->execute();
            if ($editStud_adviser !== ''){
                $check_query = "SELECT * FROM advisory_list WHERE stud_sch_user_id = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param('i', $editUser_user_id);
                $check_stmt->execute();
                $result = $check_stmt->get_result();
                if ($result->num_rows === 0) {
                    $insert_query = "INSERT INTO advisory_list (stud_sch_user_id, adv_sch_user_id) VALUES (?, ?)";
                    $insert_stmt = $conn->prepare($insert_query);
                    $insert_stmt->bind_param('ii', $editUser_user_id, $editStud_adviser);
                    $insert_stmt->execute();
                } else {
                    $update_query = "UPDATE advisory_list SET adv_sch_user_id = ? WHERE stud_sch_user_id = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param('ii', $editStud_adviser, $editUser_user_id);
                    $update_stmt->execute();
                }
            }

        }
        if (isset($_POST['user_Pass']) and sanitizeInput($_POST['user_Pass'])){
            $hashed_password = password_hash($_POST['user_Pass'], PASSWORD_DEFAULT);
            $update_account = "UPDATE tbl_accounts 
                           SET email = ?, 
                               password = ? 
                           WHERE user_id = ?";
            $stmt_update_account = $conn->prepare($update_account);
            $stmt_update_account->bind_param("ssi", $editUser_email, $hashed_password, $editUser_user_id);

            if (!$stmt_update_account->execute()){
                if ($stmt_update_account->errno == 1062) {
                    echo "Email id already exist";
                    exit;
                } else if ($stmt_update_account->errno) {

                    echo 'Error: ' . $stmt->error;
                    exit;
                }
            }

        }
        echo 1;
        exit();


    } else {
        echo 'Error: Some required fields are empty.';
    }
}

if ($action == 'deactivate_account'){
    $user_id = isset($_GET['data_id']) && sanitizeInput($_GET['data_id']) ? $_GET['data_id'] : '';
    if (isset($user_id)){
        $sql = "UPDATE tbl_accounts SET status = 'inactive'  where user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i',$user_id);
        if ($stmt->execute()){
            echo 1;
        }
    }
}

if ($action == 'getAdvisers') {

    $sql = "SELECT ui.*, acc.*
        FROM tbl_user_info ui
        INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
        WHERE ui.user_type IN ('adviser', 'admin')";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $advisers = array();

        // Fetch data row by row
        while ($row = $result->fetch_assoc()) {
            echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['school_id'].'</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['first_name'].' '.$row['last_name'].'</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">'.getTotalAdvList($row['user_id']).'</span>
                        </td>
                        <td class="p-3 text-end">
                            <a onclick="openModalForm(\'editAdv_admin\');editAdvInfo(this.getAttribute(\'data-id\'))" data-id="'.$row['user_id'].'" href="#" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>';
        }
    }
    exit();
}
if ($action == 'getAdvInfoJson') {
    $user_id = $_GET['data_id'];

    $sql = "SELECT ui.*, acc.*
            FROM tbl_user_info ui
            INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
            WHERE ui.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result === false) {
        $error = "Error: " . $stmt->error;
        header('Content-Type: application/json');
        echo json_encode(array("error" => $error));
    }else {
        $advisers = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($advisers);
    }
    $stmt->close();
}

if ($action == "getCommentst") {
    $user_id = $_SESSION['log_user_id'];
    $file_id = $_GET['file_id'];
    $sql = "SELECT * FROM tbl_revision WHERE file_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $result = $stmt->get_result();
   if ($result->num_rows > 0){
       while ($row = $result->fetch_assoc()) {
           if ($row['user_id'] == $user_id) {
               echo '<div class="grid place-items-center">
                        <div class="flex justify-end items-end w-full mb-2">
                            <div>
                                <p class="py-4 px-2 bg-slate-100 border rounded-lg min-w-8 text-sm text-slate-700 text-end ' . (isset($row['comment']) && $row['comment'] !== '' ? '' : 'hidden') . '" id="ref_id">' . $row['comment'] . '</p>
                            </div>
                            <div class="avatar">
                                <div class="w-10 rounded-full">
                                    <img src="assets/prof.jpg" />
                                </div>
                            </div>
                        </div>';

               $comment_id = $row['comment_id'];
               $attachments_sql = "SELECT * FROM revision_attachment WHERE comment_id = ?";
               $attachments_stmt = $conn->prepare($attachments_sql);
               $attachments_stmt->bind_param("i", $comment_id);
               $attachments_stmt->execute();
               $attachments_result = $attachments_stmt->get_result();

               if ($attachments_result->num_rows > 0) {
                   echo '<div class="flex flex-wrap gap-1 w-full justify-end mb-2">';
                   while ($attachment_row = $attachments_result->fetch_assoc()) {
                       // Display attachment images here
                       echo '<img src="comments_img/' . $attachment_row['attach_img_file_name'] . ' " onclick="openModalForm(\'img_modal\');viewImage(\''.$attachment_row['attach_img_file_name'].'\')" class="hover:cursor-pointer h-[5rem] min-h-[3rem] max-h-[5rem] object-contain" alt="attachment">';
                   }
                   echo '</div>';
               }

               echo '</div>';
           } else {
               // Render the comment to the left
               echo '<div class="grid place-items-center">
                    <div class="flex justify-start items-start w-full mb-2">
                       <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="assets/prof.jpg" />
                            </div>
                        </div>
                        <div>
                                <p class="py-4 px-2 bg-slate-100 border rounded-lg min-w-8 text-sm text-slate-700 text-start ' . (isset($row['comment']) && $row['comment'] !== '' ? '' : 'hidden') . '" id="ref_id">' . $row['comment'] . '</p>
                       </div>
                        
                    </div>';

               $comment_id = $row['comment_id'];
               $attachments_sql = "SELECT * FROM revision_attachment WHERE comment_id = ?";
               $attachments_stmt = $conn->prepare($attachments_sql);
               $attachments_stmt->bind_param("i", $comment_id);
               $attachments_stmt->execute();
               $attachments_result = $attachments_stmt->get_result();

               if ($attachments_result->num_rows > 0) {
                   echo '<div class="flex flex-wrap gap-1  w-full justify-start mb-2">';
                   while ($attachment_row = $attachments_result->fetch_assoc()) {
                       echo '<img  src="comments_img/' . $attachment_row['attach_img_file_name'] . '" onclick="openModalForm(\'img_modal\');viewImage(\''.$attachment_row['attach_img_file_name'].'\')" class="hover:cursor-pointer min-h-[3rem] max-h-[5rem] h-[5rem] object-contain" alt="attachment">';
                   }
                   echo '</div>';
               }
               echo '</div>';
           }
           $comment_date_time = $row['comment_date'];
           $timestamp = strtotime($comment_date_time);
           $formatted_time = date("g:ia", $timestamp); // Format time as '2:30pm'
           $formatted_date = date("n/j/Y", $timestamp); // Format date as '4/16/2024'
           echo '<hr>';
           echo '<div class="w-full grid place-items-center">
        <p class="text-[10px] text-slate-400 text-center">' . $formatted_time . '</p>
        <p class="text-[10px] text-slate-400 text-center">' . $formatted_date . '</p>
      </div>';

       }
   }else{
       echo '<p></p>';
   }

}
if ($action == 'giveComment') {

    $revision_comment = $_POST['revision_comment'];
    $file_id = $_POST['file_id'];
    $user_id = $_SESSION['log_user_id'];
    $comment_date = date('Y-m-d H:i:s');
    $insert_revision_sql = "INSERT INTO tbl_revision (file_id,user_id ,comment, comment_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_revision_sql);
    $stmt->bind_param("iiss", $file_id,$user_id , $revision_comment, $comment_date);
    $stmt->execute();
    $stmt->close();
    $comment_id = $conn->insert_id;

    if (!empty($_FILES['final_report_file']['name'][0])) {


        foreach ($_FILES['final_report_file']['tmp_name'] as $key => $tmp_name) {
            $temp_file = $_FILES['final_report_file']['tmp_name'][$key];
            $file_type = $_FILES['final_report_file']['type'][$key];
            $file_name = uniqid() . '.' . pathinfo($_FILES['final_report_file']['name'][$key], PATHINFO_EXTENSION);
            $destination_directory = 'src/comments_img/';
            $destination_file = $destination_directory . $file_name;
            if (move_uploaded_file($temp_file, $destination_file)) {
                $insert_attachment_sql = "INSERT INTO revision_attachment (comment_id, attach_img_file_name) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_attachment_sql);
                $stmt->bind_param("is", $comment_id, $file_name);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Error moving file to destination directory.";
            }
        }

        echo 1; // Success response
    } else {
        echo 2; // No file uploaded
    }
}


if ($action === 'Notes') {
    $user_id = $_SESSION['log_user_id'];
    $note_title = isset($_POST['noteTitle']) ? sanitizeInput($_POST['noteTitle']) : '';
    $actionType = isset($_POST['actionType']) ? sanitizeInput($_POST['actionType']) : '';
    $message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';
    $announcement_id = isset($_POST['announcementID']) ? sanitizeInput($_POST['announcementID']) : '';;
    if ($note_title !== ''  && $message !== '') {
        if ($actionType == 'edit'){
            $sql = "UPDATE announcement SET 
                        title = ?, description = ?, 
                        announcementPosted = NOW() 
                    where announcement_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $note_title, $message, $announcement_id);
            $stmt->execute();
            echo 1;
        }else {
            $sql = "INSERT INTO announcement  (user_id, title, description,type)
                    VALUES (?,?,?,'Notes')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iss', $user_id, $note_title, $message);
            $stmt->execute();
            echo 1;
        }
    }
}

if ($action == 'getDashboardNotes'){
    $user_id = $_SESSION['log_user_id'];
    $getNotes = "SELECT * from announcement where user_id= ?  and status IN ('Active', 'Pending', 'Declined') and type = 'Notes'";
    $stmt = $conn->prepare($getNotes);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()){
            $announcementPosted = date('h:i A', strtotime($row['announcementPosted']));
            $formattedDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $row['announcementPosted'])->format('m/d/Y h:i A');
            $message = $row['description'];

            echo '
              
            
        <div onclick="removeTrashButton(); getNotes('.$row['announcement_id'].');openModalForm(\'Notes\');" class="transform w-full md:w-[18rem] transition duration-500 shadow rounded hover:scale-110 hover:bg-slate-300 justify-center items-center cursor-pointer p-3 h-[10rem]">
            <div class="h-[8rem] overflow-hidden hover:overflow-auto">
                <h1 class="font-semibold">'.$row['title'].'</h1>
                <p class="text-start text-sm break-words"> '.$row['description'].' </p>
                <p class="text-[12px] text-slate-400 text-end">'.$formattedDateTime.'</p>
            </div>
        </div>
            
           ';
        }
    }
}

if ($action == 'announcementJson' ){
    $announcemnt_id = $_GET['data_id'];
    $getAnnouncement = "SELECT * from announcement where announcement_id= ?";
    $stmt = $conn->prepare($getAnnouncement);
    $stmt->bind_param('i', $announcemnt_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $announcement = $res->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($announcement);
    }
}


if ($action == 'deleteAnnouncement'){
    $announcement_id = isset($_GET['data_id']) ? sanitizeInput($_GET['data_id']) : '';
    if ($announcement_id !== '' ){
        $hideAnnoucement = "UPDATE announcement SET status = 'Hidden' where announcement_id = ?";
        $stmt = $conn->prepare($hideAnnoucement);
        $stmt->bind_param('i',$announcement_id);
        if ($stmt->execute()){
            echo 1;
            exit();
        }else{
            echo $stmt->error;
            exit();
        }
    }else{
        echo 'Invalid announcement ID';
    }
}

if ($action === 'NewActivity') {
    $user_id = $_SESSION['log_user_id'];
    $note_title = isset($_POST['Activitytitle']) ? sanitizeInput($_POST['Activitytitle']) : Null;
    $actionType = isset($_POST['actionType']) ? sanitizeInput($_POST['actionType']) : '';
    $actDescription = isset($_POST['description']) ? sanitizeInput($_POST['description']) : Null;
    $announcement_id = isset($_POST['announcementID']) ? sanitizeInput($_POST['announcementID']) : Null;;
    $startingDate = isset($_POST['startDate']) ? sanitizeInput($_POST['startDate']): null;
    $endinggDate = isset($_POST['endDate']) ? sanitizeInput($_POST['endDate']): Null;
    if ($note_title !== '' ) {
        if ($actionType == 'edit'){
            $sql = "UPDATE announcement SET 
                        title = ?, description = ?, starting_date = ?, end_date = ?,
                        announcementPosted = NOW() 
                    where announcement_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssi', $note_title, $actDescription,$startingDate,$endinggDate ,  $announcement_id);
            $stmt->execute();
            echo 1;
        }else {
            $sql = "INSERT INTO announcement  (user_id, title, description , starting_date, end_date,type)
                    VALUES (?,?,?,?,?,'schedule and activities')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issss', $user_id, $note_title, $actDescription, $startingDate, $endinggDate);
            $stmt->execute();
            echo 1;
        }
    }
}


if ($action == 'getDashboardActSched'){
    $user_id = $_SESSION['log_user_id'];
    $actSched = "SELECT *    
    FROM announcement 
        WHERE 1 = 1
          AND status = 'Active'
            AND type = 'schedule and activities'
        ORDER BY starting_date;
        ";
    $stmt = $conn->prepare($actSched);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()){
            $announcementPosted = date('h:i A', strtotime($row['announcementPosted']));
            $formattedDatePosted = DateTime::createFromFormat('Y-m-d H:i:s', $row['announcementPosted'])->format('m/d/Y h:i A');
            $formattedStartingDate = date("F j, Y", strtotime($row['starting_date']));
            $formattedEndingDate = date("F j, Y", strtotime($row['end_date']));
            echo '<div onclick="removeTrashButton();openModalForm(\'Act&shedModal\');getActSched('.$row['announcement_id'].')" class="flex transform w-[50rem]  transition duration-500 shadow rounded
            hover:scale-110 hover:bg-slate-300  justify-start items-center cursor-pointer">
            <div class=" min-w-[12rem]  p-2 sm:p-5 b text-center flex flex-col justify-center text-sm">';

           if ($formattedStartingDate === $formattedEndingDate){
              echo '<h4 class="text-start">'.$formattedStartingDate.'</h4>';
           }else {
               echo '<h4 class="text-start">' . $formattedStartingDate . '</h4>';
               echo '<h4 class="text-start">' . $formattedEndingDate . '</h4>';
           }
            echo '
            </div>
            <div class="flex flex-col justify-center max-h-[10rem] overflow-auto p-3">
                <h1 class="font-semibold">'.$row['title'].'</h1>
                <div class=" max-h-[10rem] overflow-auto">
                    <p class="text-justify text-sm pr-5 break-words">'.$row['description'].'
                    </p>
                </div>
            </div>
        </div>';
        }
    }
}
if ($action == 'ProgYrSec') {
    $program_code = isset($_POST['ProgramCode']) ? sanitizeInput($_POST['ProgramCode']) : '';
    $program_name = isset($_POST['ProgramName']) ? sanitizeInput($_POST['ProgramName']) : '';
    $year = isset($_POST['year']) ? sanitizeInput($_POST['year']) : '';
    $section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
    $actionType = isset($_POST['action_type']) ? sanitizeInput($_POST['action_type']) : '';
    $id = isset($_POST['ID']) ? sanitizeInput($_POST['ID']) : '';

    if ($program_code !== '' && $program_name !== '') {
        if (isset($actionType) && $actionType == 'edit') {
            $sql = "UPDATE program SET program_code = ?, program_name = ? WHERE id = ?";
            $updateProgStmt = $conn->prepare($sql);
            $updateProgStmt->bind_param('ssi', $program_code, $program_name, $id);
            if ($updateProgStmt->execute()) {
                echo 1;
                exit();
            } else {
                echo $updateProgStmt->error;
                exit();
            }
        } else {
            $sql = "INSERT INTO program (program_code, program_name) VALUES (?, ?)";
            $insertProgStmt = $conn->prepare($sql);
            $insertProgStmt->bind_param('ss', $program_code, $program_name);
            if ($insertProgStmt->execute()) {
                echo 1;
                exit();
            } else {
                echo $insertProgStmt->error;
                exit();
            }
        }
    } elseif ($year !== '' && $section !== '') {
        $dbcolNameSection = $year . $section;
        if (isset($actionType) && $actionType == 'edit') {
            $sql = "UPDATE section SET section = ? WHERE id = ?";
            $updateSecStmt = $conn->prepare($sql);
            $updateSecStmt->bind_param('si', $dbcolNameSection, $id);
            if ($updateSecStmt->execute()) {
                echo 1;
                exit();
            } else {
                echo $updateSecStmt->error;
                exit();
            }
        } else {
            $sql = "INSERT INTO section (section) VALUES (?)";
            $insertSecStmt = $conn->prepare($sql);
            $insertSecStmt->bind_param('s', $dbcolNameSection);
            if ($insertSecStmt->execute()) {
                echo 1;
                exit();
            } else {
                echo $insertSecStmt->error;
                exit();
            }
        }
    } else {
        echo "Please provide valid input.";
    }
}

if ($action == 'getDasboardYrSec'){

    $sql = "SELECT * FROM  section";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()){
        echo '<tr class="hover">
                    <td>'.$row['section'].'</td>
                    <td class="text-center cursor-pointer"><i class="fa-solid fa-pen-to-square"></i></td>
                </tr>';
    }
}
if ($action == 'getDasboardPrograms'){
    $sql = "SELECT * FROM  program";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()){
        echo '<tr class="hover">
                    <td>'.$row['program_code'].'</td>
                    <td>'.$row['program_name'].'</td>
                    <td class="text-center cursor-pointer"><i class="fa-solid fa-pen-to-square"></i></td>

                </tr>';

    }
}

if ($action === 'getHomeActSched'){
    $actSched = "SELECT *    
    FROM announcement 
        WHERE 1 = 1
          AND status = 'Active'
            AND type = 'schedule and activities'
        ORDER BY starting_date;
        ";
    $stmt = $conn->prepare($actSched);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()){
            $announcementPosted = date('h:i A', strtotime($row['announcementPosted']));
            $formattedDatePosted = DateTime::createFromFormat('Y-m-d H:i:s', $row['announcementPosted'])->format('m/d/Y h:i A');
            $formattedStartingDate = date("F j, Y", strtotime($row['starting_date']));
            $formattedEndingDate = date("F j, Y", strtotime($row['end_date']));

            echo '<div class="flex min-w-[40rem] ' . (isset($_SESSION['log_user_type']) && $_SESSION['log_user_type'] == 'student' ? 'w-[40rem]' : 'w-[50rem]') . ' shadow rounded transition duration-500 transform hover:scale-110 hover:bg-slate-300 cursor-pointer justify-start items-center">
                    <div class="w-[12rem] p-2 sm:p-5 b text-center flex flex-col justify-start text-sm ">
                    
                    ';
            if ($formattedStartingDate === $formattedEndingDate){
                echo '<h4 class="text-start">'.$formattedStartingDate.'</h4>';
            }else {
                echo '<h4 class="text-start">' . $formattedStartingDate . '</h4>';
                echo '<h4 class="text-start">' . $formattedEndingDate . '</h4>';
            }


                echo '</div>
                    <div class="flex flex-col justify-center max-h-[10rem] overflow-auto ">
                        <h1 class="font-semibold">'.$row['title'].'</h1>
                        <div class="max-h-[10rem] transition duration-100 overflow-hidden hover:overflow-auto">
                            <p class="text-justify text-sm pr-5">'.$row['description'].'</p>
                        </div>
                    </div>
                </div>';
        }
    }
}

if ($action == 'getHomeNotes'){
    $user_id = $_SESSION['log_user_id'];

    $getAdv = "SELECT * FROM advisory_list WHERE stud_sch_user_id = ?";
    $getAdvStmt = $conn->prepare($getAdv);
    $getAdvStmt->bind_param('i', $user_id);
    $getAdvStmt->execute();
    $res = $getAdvStmt->get_result();
    $get_adv_id = $res->fetch_assoc();
    $adv_id = $get_adv_id['adv_sch_user_id'];

    $getAdv_announcement = "SELECT * FROM announcement WHERE user_id = ? AND type = 'Notes' AND status = 'Active' order by announcementPosted";
    $getannouncementStmt = $conn->prepare($getAdv_announcement);
    $getannouncementStmt->bind_param('i', $adv_id);
    $getannouncementStmt->execute();
    $results = $getannouncementStmt->get_result();

    while ($row = $results->fetch_assoc()){
        $notePosted = DateTime::createFromFormat('Y-m-d H:i:s', $row['announcementPosted'])->format('m/d/Y h:i A');

        echo '

<div class="flex transition duration-500 transform scale-90 hover:scale-100 hover:bg-slate-300 cursor-pointer w-full">
    <div class="flex flex-col justify-center p-2 w-full">
        <h1 class="font-semibold">'.$row['title'].'</h1>
        <div class="max-h-[10rem] transition overflow-hidden hover:overflow-auto w-full">
            <p class="text-justify text-sm break-words w-full">'.$row['description'].'
            </p>
            <p class="text-[12px] text-slate-400 text-end">'.$notePosted.'
        </div>
    </div>
</div>

       
        ';
    }
}
