<?php

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}

session_start();
date_default_timezone_set('Asia/Manila');
include_once 'DatabaseConn/databaseConn.php';
include_once 'FlipbookFunctions.php';
include 'functions.php';

$action = $_GET['action'];
extract($_POST);
/*
if ($action== 'signUp'){
    echo 1;
}
*/
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
    $stmt->bind_param("i", $user_id); // Assuming $stud_user_id contains the user ID
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

    // Prepare and execute the statement
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
    $stud_sex = isset($_POST['stud_Sex']) ? sanitizeInput($_POST['stud_Sex']) :'';
    $ojt_adviser = isset($_POST['ojt_adviser']) ? sanitizeInput($_POST['ojt_adviser']) : '';
    $school_id = isset($_POST['school_id']) && is_numeric($_POST['school_id']) && check_uniq_stud_id($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';
    if ($first_name !== '' && $stud_sex !== '' && $last_name !== '' && $program !== '' && $section !== '' && $ojt_adviser !== '' && $school_id !== '') {
        if(isset($_FILES['final_report_file'])) {
            $file_name = $_FILES['final_report_file']['name'];
            $file_temp = $_FILES['final_report_file']['tmp_name'];
            $file_type = $_FILES['final_report_file']['type'];
            $file_error = $_FILES['final_report_file']['error'];
            $file_size = $_FILES['final_report_file']['size'];

            if (isPDF($file_name)){

                $file_first_name = str_replace(' ', '', $first_name);
                $file_last_name = str_replace(' ', '', $last_name);
                $new_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section."_".$school_id.".pdf";
                $current_date_time = date('Y-m-d H:i:s');
                $narrative_status = 'OK';
                if($file_error === UPLOAD_ERR_OK) {

                    $new_final_report = $conn->prepare("INSERT INTO narrativereports (stud_school_id, sex,
                              first_name, last_name, program, section, OJT_adviser,narrative_file_name, upload_date, file_status)
                              values (?,?,?,?,?,?,?,?,?,?)");

                    $new_final_report->bind_param("ssssssssss",
                        $school_id,$stud_sex, $first_name, $last_name,
                        $program, $section, $ojt_adviser, $new_file_name,
                        $current_date_time, $narrative_status);


                    if (!$new_final_report->execute()){
                        echo 'query error';
                        exit();
                    }
                    $new_final_report->close();
                    $destination = "src/NarrativeReportsPDF/" . $new_file_name;
                    move_uploaded_file($file_temp, $destination);
                    $report_pdf_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section."_".$school_id;

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
    $sql = "SELECT * FROM narrativereports where file_status = 'OK' order by upload_date desc";
    $result = $conn->query($sql);
    $number = 1;
    if ($result === false) {
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (isset($_GET['homeTable']) && $_GET['homeTable'] == 'request') {
                    echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $number++ . '</span>
                        </td>
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
                else if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'adviser' and isset($_GET['dashboardTable']) && $_GET['dashboardTable'] == 'request') {
                    echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">' . $row['OJT_adviser'] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-sm">' . $row["program"] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-sm">' . $row["section"] . '</span>
                        </td>
                        <td class="p-3 text-end ">
                            <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>';
                }
                else if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'admin' and isset($_GET['dashboardTable']) && $_GET['dashboardTable'] == 'request') {
                    echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row['OJT_adviser'] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row["program"] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row["section"] . '</span>
                        </td>
                        <td class="p-3 text-end ">
                            <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                            <a onclick="openModalForm(\'EditNarrative\');editNarrative(this.getAttribute(\'data-narrative\'))" id="archive_narrative" data-narrative="' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                      </tr>';
                }else{

                    exit();
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
                                          sex = ?,
                                          first_name = ?,
                                          last_name = ?,
                                          program = ?,
                                          section = ?,
                                          OJT_adviser = ?,
                                          narrative_file_name = ?,
                                          upload_date = ?,
                                          file_status = ?
                                      WHERE narrative_id = ?");
        $update_final_report->bind_param("ssssssssssi",
            $school_id, $stud_sex,$first_name, $last_name,
            $program, $section, $ojt_adviser, $new_file_name,
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
    $user_shc_id = isset($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';
    $user_sex = isset($_POST['user_Sex']) ? sanitizeInput($_POST['user_Sex']) : '';
    $user_contact_number = isset($_POST['contactNumber']) ? sanitizeInput($_POST['contactNumber']) : '';
    $user_address = isset($_POST['user_address']) ? sanitizeInput($_POST['user_address']) : '';
    $user_program = isset($_POST['stud_Program']) ? sanitizeInput($_POST['stud_Program']) : '';
    $user_section = isset($_POST['stud_Section']) ? sanitizeInput($_POST['stud_Section']) : '';
    $stud_adviser = isset($_POST['stud_adviser']) ? sanitizeInput($_POST['stud_adviser']) : '';
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


        $insert_sql = "INSERT INTO tbl_user_info (first_name, last_name, address, contact_number, school_id, sex, user_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssssss", $user_first_name, $user_last_name, $user_address, $user_contact_number,
            $user_shc_id, $user_sex,$user_type);
        $insert_stmt->execute();


        $user_id = $insert_stmt->insert_id;


        $account_sql = "INSERT INTO tbl_accounts (user_id, email, password, status) 
                VALUES (?, ?, ?, 'active')";
        $account_stmt = $conn->prepare($account_sql);
        $account_stmt->bind_param("iss", $user_id, $user_email, $hashed_password);
        $account_stmt->execute();

        if ( $user_program !== '' && $user_section !== '' && $user_type == 'student' && $stud_adviser !== '')
        {
            $student_sql = "INSERT INTO tbl_students (user_id, program_id, section_id) 
                VALUES (?, ?, ?)";
            $student_stmt = $conn->prepare($student_sql);
            $student_stmt->bind_param("iii", $user_id, $user_program, $user_section);
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
                                a.date_created ASC";
    $result = $conn->query($fetch_enrolled_stud);
    if ($result === false){
        echo "Error: " . $conn->error;
    }
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
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
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['section'].'</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$row['program_code'].'</span>
                        </td>
                        <td class="p-3 text-end">
                            <a href="#" onclick="openModalForm(\'editStuInfo\');editUserStud_Info(this.getAttribute(\'data-id\'))" data-id="' . urlencode(encrypt_data($row['user_id'], $secret_key)) .'" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>
                    </tr>';
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
            // Error code 1062  duplicate entry error
            echo 2;// duplicate stud id
            exit; // Stop execution
        } else if ($stmt->errno) {
            // Handle other MySQL errors
            echo 'Error: ' . $stmt->error;
            exit; // Stop execution
        }
        if ($editStud_program !== '' && //execute only if the admin editing student type user
            $editStud_section !== '' && $edituser_type == 'student'){
            $update_stud_info = "UPDATE tbl_students 
                            SET program_id = ?, 
                                section_id = ? 
                            WHERE user_id = ?";
            $stmt_update_info = $conn->prepare($update_stud_info);
            $stmt_update_info->bind_param("iii", $editStud_program, $editStud_section, $editUser_user_id);
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
            $stmt_update_account->execute();

            //add emailing
        }
        echo 1;

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
           // Check if the user ID matches the session user ID
           if ($row['user_id'] == $user_id) {
               // Render the comment to the right
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

               // Fetch and display any attachments associated with the comment
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
                                <p class="py-4 px-2 bg-slate-100 border rounded-lg min-w-8 text-sm text-slate-700 text-end ' . (isset($row['comment']) && $row['comment'] !== '' ? '' : 'hidden') . '" id="ref_id">' . $row['comment'] . '</p>
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


