<?php

/*if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
*/


session_start();
date_default_timezone_set('Asia/Manila');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

include 'vendor/autoload.php';

include_once 'DatabaseConn/databaseConn.php';
include 'functions.php';//first
include 'ajaxreq_processFunc.php';//second
include_once 'FlipbookFunctions.php';
include_once 'PhpMailer_producer.php';




$action = $_GET['action'];
extract($_POST);




function countFileComments($file_id){

    $sql = "SELECT COUNT(*) AS comment_count FROM tbl_revision WHERE file_id = ?";


    $result = mysqlQuery($sql, 'i', [$file_id]);

    $comment_count = $result[0]['comment_count'];

    return $comment_count;
}
if ($action == 'login') {
    header('Content-Type: application/json');
    $response = 1;
    $redirectPage = '';

    $log_email = isset($_POST['log_email']) ? sanitizeInput($_POST['log_email']) : '';
    $log_password = $_POST['log_password'] ?? '';
    if ($log_email == '' && $log_password == '') {
        handleError('Email or password empty');
    }
    $fetch_acc = "SELECT user_id, password, status FROM tbl_accounts WHERE email = ? ";

    $result = mysqlQuery($fetch_acc, 's', [$log_email]);

    if (count($result) !== 1) {
        handleError('User not found');
    }
    $row = $result[0];
    $user_id = $row['user_id'];
    $hashed_password = $row['password'];
    $acc_stat = $row['status'];
    if (!password_verify($log_password, $hashed_password)) {
        handleError('Incorrect password');
    }
    $fetch_user_info = "SELECT * FROM tbl_user_info WHERE user_id = ?";
    $result_user_info = mysqlQuery($fetch_user_info, 'i', [$user_id]);
    if (count($result_user_info) !== 1) {
        handleError('User type not found');
    }
    $row_user_info = $result_user_info[0];
    $_SESSION['log_user_id'] = $user_id;
    $_SESSION['log_user_email'] = $log_email;
    $_SESSION['log_acc_stat'] = $acc_stat;
    $_SESSION['log_user_type'] = $row_user_info['user_type'];
    $_SESSION['log_user_firstName'] = $row_user_info['first_name'];
    $_SESSION['log_user_middleName'] = $row_user_info['middle_name'] !== 'N/A' ? $row_user_info['middle_name'] : '';
    $_SESSION['log_user_lastName'] = $row_user_info['last_name'];
    $_SESSION['log_user_profileImg'] = $row_user_info['profile_img_file'];
    if ($acc_stat === 'active'){
        $redirectPage = in_array($row_user_info['user_type'], ['adviser', 'admin']) ? 'dashboard.php' : 'index.php?page=weeklyJournal';

    }else{
        $redirectPage = 'index.php';
    }


    echo json_encode(['response' => $response,
       'redirect' => $redirectPage]);
   exit();




}




if ($action == 'addWeeklyReport') {
    $response = 1;
    $responseMessage = 'Weekly report has been submitted';
    header('Content-Type: application/json');

    $user_id = $_SESSION['log_user_id']; // student

    if (empty($user_id)) {
        handleError('missing_user_id');
        return;
    }

    if (!isset($_FILES['weeklyReport'])) {
        handleError('file_missing');
        return;
    }

    if (!isPDF($_FILES['weeklyReport']['name'])) {
        handleError('format_error');
        return;
    }

    if ($_FILES['weeklyReport']['error'] !== UPLOAD_ERR_OK) {
        handleError('upload_error');
        return;
    }

    $get_User_info = "SELECT * FROM tbl_students WHERE user_id = ?";
    $result = mysqlQuery($get_User_info, 'i', [$user_id]);

    $row = $result[0];
    $file_name = "{$row['enrolled_stud_id']}_WeeklyJournal_week_" . uniqid('', true) . ".pdf";

    try {
        $startWeek = new DateTime(getPostData('startWeek'));
        $endWeek = new DateTime(getPostData('endWeek'));
        $week = $startWeek->format('M j, Y') . " - " . $endWeek->format('M j, Y');
    } catch (Exception $e) {
        handleError('invalid_date');
        return;
    }


    $insert_weekly_report = "INSERT INTO weeklyReport (stud_user_id, weeklyFileReport, week, upload_date, upload_status) 
                             VALUES (?, ?, ?, CURRENT_TIMESTAMP, 1)";

    $file_id = mysqlQuery($insert_weekly_report, 'iss', [$user_id, $file_name, $week])[1];

    insertActivityLog('upload', $file_id);

    $temp_file = $_FILES['weeklyReport']['tmp_name'];
    $final_destination = 'src/StudentWeeklyReports/' . $file_name;

    if (!move_uploaded_file($temp_file, $final_destination)) {
        handleError('move_error');
        return;
    }

    echo json_encode(['response' => $response, 'message' => $responseMessage]);
    exit();
}

if ($action == 'resubmitReport') {
    $response = 1;
    $responseMessage = 'Weekly report has been resubmitted';
    header('Content-Type: application/json');

    $file_id = isset($_POST['file_id']) ? sanitizeInput($_POST['file_id']) : '';

    if (empty($file_id)) {
        handleError('missing_file_id');
        return;
    }

    if (!isset($_FILES['resubmitReport'])) {
        handleError('file_missing');
        return;
    }

    if (!isPDF($_FILES['resubmitReport']['name'])) {
        handleError('format_error');
        return;
    }

    if ($_FILES['resubmitReport']['error'] !== UPLOAD_ERR_OK) {
        handleError('upload_error');
        return;
    }

    $get_weeklyReport = "SELECT * FROM weeklyReport WHERE file_id = ?";
    $row = mysqlQuery($get_weeklyReport, 'i', [$file_id]);

    if (!$row || count($row) == 0) {
        handleError('file_not_found');
        return;
    }

    $file_path = 'src/StudentWeeklyReports/' . $row[0]['weeklyFileReport'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    if (!move_uploaded_file($_FILES['resubmitReport']['tmp_name'], $file_path)) {
        handleError('move error');
        return;
    }

    try {
        $startWeek = new DateTime(getPostData('startWeek'));
        $endWeek = new DateTime(getPostData('endWeek'));
        $week = $startWeek->format('M j, Y') . " - " . $endWeek->format('M j, Y');
    } catch (Exception $e) {
        handleError('invalid_date');
        return;
    }


    $updateWeeklyReport = "UPDATE weeklyReport SET readStatus = 'Unread', week = ? WHERE file_id = ?";
    mysqlQuery($updateWeeklyReport, 'si', [$week, $file_id]);

    insertActivityLog('resubmit', $file_id);
    echo json_encode(['response' => $response, 'message' => $responseMessage]);
    exit();
}



if ($action == 'getWeeklyReports'){
    header('Content-Type: application/json');
    $user_id = $_SESSION['log_user_id'];
    $week = 1;
    $sql = "SELECT *
        FROM weeklyReport
        WHERE stud_user_id = ?
        ORDER BY upload_date";


    $result = mysqlQuery($sql, 'i',[$user_id] );
    if(count($result) > 0){

        for ($i = 0; $i < count($result); $i++){
            $result[$i]['totalJournalComment'] = countFileComments($result[$i]['file_id']);
        }
        echo json_encode(['response' => 1,
        'data' => $result]);
    }else{
        handleError('No weekly journal reports found');
    }
    exit();

}





if ($action == 'updateReadStat'){
    header('Content-Type: application/json');
    $file_id = $_GET['file_id'];

    $updateReadStat = "UPDATE weeklyreport SET readStatus = 'Read' where file_id = ?";
    $updateQuery  = mysqlQuery($updateReadStat,'i', [$file_id]);
    echo json_encode(['response' => 1]);

}


if ($action == 'getUploadLogs'){
    header('Content-Type: application/json');
    $user_id = $_SESSION['log_user_id'];

    $sql = "SELECT a.*, w.stud_user_id, w.weeklyFileReport, w.week
            FROM activity_logs AS a
            JOIN weeklyReport AS w ON a.file_id = w.file_id
            WHERE w.stud_user_id = ?
            ORDER BY a.activity_date DESC";


    $result = mysqlQuery($sql, 'i', [$user_id]);
    if (count($result) > 0){
        echo json_encode(['response' => 1,
            'data' => $result]);
    }else
    {
        echo json_encode(['response' => 1,
            'data' => []]);
    }
    exit();

}


if ($action == 'newFinalReport'){
    header('Content-Type: application/json');

    $response = 1;
    $responseMessage ='New narrative report has been submitted! Please wait for adviser approval';

    if (!isset($_FILES['narrativeReportPDF'])) {
        handleError('Empty file');
    }

    $file_name = $_FILES['narrativeReportPDF']['name'];
    $file_temp = $_FILES['narrativeReportPDF']['tmp_name'];
    $file_type = $_FILES['narrativeReportPDF']['type'];
    $file_error = $_FILES['narrativeReportPDF']['error'];
    $file_size = $_FILES['narrativeReportPDF']['size'];


    if (!isPDF($file_name)){
        handleError('Invalid file format: Not pdf');
    }

    if (!$file_error === UPLOAD_ERR_OK) {
        handleError('file error');
    }




    $stud_info = mysqlQuery('SELECT s.*, ui.* 
from tbl_students s 
    JOIN tbl_user_info ui on ui.user_id = s.user_id
   
where s.user_id = ?', 'i', [$_SESSION['log_user_id']])[0];




    $school_id = $stud_info['enrolled_stud_id'];
    $ojt_adviser_UID = $stud_info['adv_id'];
    $semAyId = mysqlQuery("SELECT id from tbl_aysem where Curray_sem = 1", '', [])[0]['id'];
    $new_file_name =  $school_id.'_'. uniqid('', true) . ".pdf";
    $current_date_time = date('Y-m-d H:i:s');

    try {
        $new_final_report = "INSERT INTO narrativereports
    (enrolled_stud_id , ojt_adv_id, ay_sem_id,  narrative_file_name)
    values (?,?,?,?)";

        $valueTypes = "isss";
        $params = [$school_id, $ojt_adviser_UID,$semAyId, $new_file_name ];

        $narrative_id = mysqlQuery($new_final_report,$valueTypes, $params)[1];
        handleNarrativeUpload('', $new_file_name, $file_temp);

    }catch (mysqli_sql_exception $e) {
        $responseMessage = $e->getMessage();
        handleError($responseMessage);
    }
    $subjectType = "Narrative Report Upload";
    $bodyMessage = "<h1><b>Notification</b></h1><br>";
    $bodyMessage .= "<b>Student </b>".$_SESSION['log_user_firstName'].' '.$_SESSION['log_user_middleName'].' '.$_SESSION['log_user_lastName'] .'<br>';
    $bodyMessage .= "Uploaded a new  narrative report <br>";
    $bodyMessage .= "Click to review : <a href='http://localhost/ReposyncNarrativeManagementSystem/src/login.php'>
                Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus</a>";
    $recipient =  getRecipient($ojt_adviser_UID);
    email_queuing($subjectType, $bodyMessage,$recipient );



    echo json_encode(['response' => $response,
        'message' => $responseMessage]);
    exit();

}


if ($action === 'editFinalReport') {
    header('Content-Type: application/json');

    $response = 1;
    $responseMessage = 'Narrative report has been updated! Please wait for adviser approval';
    $params = [];
    $types = '';

    $stud_info = mysqlQuery('SELECT s.*, ui.*, n.*
        FROM tbl_students s 
        JOIN tbl_user_info ui ON ui.user_id = s.user_id
        JOIN narrativereports n ON n.enrolled_stud_id = s.enrolled_stud_id
        WHERE s.user_id = ?', 'i', [$_SESSION['log_user_id']])[0];

    $ojt_adviser_UID = $stud_info['adv_id'];
    $school_id = $stud_info['enrolled_stud_id'];
    $narrative_id = $stud_info['narrative_id'];



    $update_query = 'UPDATE narrativereports SET file_status = 1, upload_date = NOW()';
    $types = '';
    $params = [];

    if (isset($_FILES['narrativeReportPDF'])) {
        $file_name = $_FILES['narrativeReportPDF']['name'];
        if (!isPDF($file_name)) {
            handleError('Invalid file format: Not pdf');
        } else {
            $file_temp = $_FILES['narrativeReportPDF']['tmp_name'];
            $file_error = $_FILES['narrativeReportPDF']['error'];

            if ($file_error !== UPLOAD_ERR_OK) {
                handleError('File error');
            }

            $old_filename = $stud_info['narrative_file_name'];
            $new_file_name = $school_id . '_' . uniqid('', true) . ".pdf";

            handleNarrativeUpload($old_filename, $new_file_name, $file_temp);

            $update_query .= ', narrative_file_name = ?';
            $params[] = $new_file_name;
            $types .= 's';
        }
    }

    $update_query .= ' WHERE narrative_id = ?';
    $types .= 'i';
    $params[] = $narrative_id;

    $upd_narrativeRepsTbl = mysqlQuery($update_query, $types, $params);

    echo json_encode([
        'response' => 1,
        'message' => $responseMessage
    ]);
    exit();
}




if($action == 'StudsubmittedNarratives'){
    header('Content-Type: application/json');

    $submtdNarratives = mysqlQuery('SELECT narrativereports.*, tbl_aysem.*
from narrativereports
    JOIN tbl_students
         on tbl_students.enrolled_stud_id = narrativereports.enrolled_stud_id  
    JOIN tbl_aysem on tbl_aysem.id = narrativereports.ay_sem_id
         where  tbl_students.user_id = ? ', 'i', [$_SESSION['log_user_id']]);


    if (count($submtdNarratives) > 0){
        for ($i = 0 ; $i < count($submtdNarratives) ; $i++){
            $submtdNarratives[$i]['narrative_id'] = urlencode (encrypt_data($submtdNarratives[$i]['narrative_id'], $secret_key));
        }
    }else {
        echo json_encode(['response' => 1,
            'data' => []]);
        exit();
    }

    $currAcadYear = mysqlQuery(
        'SELECT id FROM tbl_aysem WHERE Curray_sem = 1',
        'i', [])[0]['id'];
    $studSemAYnarrativeequery = mysqlQuery("SELECT * FROM narrativereports n
                JOIN tbl_students s on s.enrolled_stud_id = n.enrolled_stud_id 
         where user_id = ? and ay_sem_id = ?", 'ii', [$_SESSION['log_user_id'], $currAcadYear]);


    $isStudCanSubmitNewNarrative =  count($studSemAYnarrativeequery) !== 0
    || $_SESSION['log_acc_stat'] === 'inactive' ? false : true;


    echo json_encode(['response' => 1,
        'data' => $submtdNarratives,
        'isStudCanSubmitNewNarrative' => $isStudCanSubmitNewNarrative]);
    exit();

}

if ($action == 'narrativeReportsJson'){

    $narrative_id = decrypt_data($_GET['narrative_id'], $secret_key);

    $sql = "SELECT * FROM narrativereports WHERE narrative_id = ? ORDER BY upload_date DESC LIMIT 1";


    header('Content-Type: application/json');

    $result = mysqlQuery($sql, 'i', [$narrative_id]);
    if (count($result) > 0) {
        echo json_encode(['response' => 1,
            'data' => $result[0]]);
    }else{
        handleError('Invalid ID');
    }
    exit();

}













if ($action == 'archiveNarrative'){
    header('Content-Type: application/json');


    $response = 1;
    $responseMessage = 'Narrative report has been archived!';
    $narrative_id = isset($_GET['narrative_id']) ? $_GET['narrative_id'] : '';


    if ($narrative_id !== ''){
        $narrative_id = decrypt_data($narrative_id, $secret_key);
        $file_status = 'Archived';
        $archive_final_report = "UPDATE narrativereports
                                      SET 
                                          file_status = ?
                                      WHERE narrative_id = ?";

        try {
            mysqlQuery($archive_final_report, 'si' , [$file_status, $narrative_id]);
        }catch (mysqli_sql_exception $e){
            $responseMessage = $e->getMessage();
            $response = 2;
        }

        echo json_encode(['response' => $response,
            'message' => $responseMessage ]);
        exit();
    }else{
        handleError('Student Narrative ID is empty');
    }
}

if ($action === 'recoverNarrativeReport') {
    header('Content-Type: application/json');

    $archive_id = isset($_GET['archived_id'])  ? $_GET['archived_id'] : '';
    $narrative_id = decrypt_data($archive_id, $secret_key);
    if (!$narrative_id ) {
     handleError('emptyID');
    }
    $recoverNarrativeQuery ="UPDATE narrativereports
                                      SET 
                                          file_status = 3
                                      WHERE narrative_id = ?";
        $recoverNarrative = mysqlQuery($recoverNarrativeQuery, 'i', [$narrative_id]);
    echo json_encode(['response' => 1,
        'message' => 'Report has been successfully recovered']);
    exit();



}




if ($action == 'newUser') {
    header('Content-Type: application/json');
    $responseMessages = [
        'adviser' => 'New adviser account has been created!',
        'admin' => 'New admin account has been created!',
        'student' => 'New student account has been created!'
    ];
    $responseMessage = '';
    $response = 1;

    // Fetch user data
    $user_first_name = getPostData('user_Fname');
    $user_last_name = getPostData('user_Lname');
    $user_middle_name = getPostData('user_Mname', 'N/A');
    $user_sex = getPostData('user_Sex');
    $user_contact_number = (int) getPostData('contactNumber', 0);
    $user_address = getPostData('user_address');
    $user_email = getPostData('user_Email');
    $user_type = getPostData('user_type');

    // Student specific fields
    $user_shc_id = getPostData('school_id');
    $user_program = getPostData('stud_Program');
    $user_yr_section = getPostData('stud_Section');
    $stud_adviser = getPostData('stud_adviser');
    $studs_ojtCenter = getPostData('stud_OJT_center', 'N/A');
    $stud_Ojtlocation = getPostData('stud_ojtLocation', 'N/A');

    // Validate required fields
    $requiredFields = [
        'First Name' => $user_first_name,
        'Last Name' => $user_last_name,
        'Sex' => $user_sex,
        'Contact Number' => $user_contact_number,
        'Address' => $user_address,
        'Email' => $user_email,
        'User Type' => $user_type
    ];

    foreach ($requiredFields as $field => $value) {
        if (empty($value)) {
            handleError("Field $field is required.");
            exit();
        }
    }


    if (!is_int($user_contact_number) || strlen((string)$user_contact_number) < 10 || strlen((string)$user_contact_number) > 11) {
        handleError('Invalid contact number format.');
    }

    // Generate password based on user type
    $user_password = ($user_type === 'student')
        ? generatePassword($user_shc_id)
        : 'CVSUOJT_' . strtoupper($user_type);

    try {
        $conn->begin_transaction();


        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);


        $insert_sql = "INSERT INTO tbl_user_info (first_name, middle_name, last_name, address, contact_number, sex, user_type) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssssss", $user_first_name, $user_middle_name, $user_last_name, $user_address, $user_contact_number, $user_sex, $user_type);
        $stmt->execute();
        $user_id = $stmt->insert_id;


        $account_sql = "INSERT INTO tbl_accounts (user_id, email, password, status) VALUES (?, ?, ?, 'active')";
        $account_stmt = $conn->prepare($account_sql);
        $account_stmt->bind_param("iss", $user_id, $user_email, $hashed_password);
        $account_stmt->execute();

        // Insert student-specific details if the user is a student
        if ($user_type === 'student') {
            $student_sql = "INSERT INTO tbl_students (enrolled_stud_id, user_id, adv_id, program_id, year_sec_Id, ojt_center, ojt_location) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stud_stmt = $conn->prepare($student_sql);
            $stud_stmt->bind_param("iiiiiss", $user_shc_id, $user_id, $stud_adviser, $user_program,
                $user_yr_section, $studs_ojtCenter, $stud_Ojtlocation);
            $stud_stmt->execute();
        }

        $responseMessage = $responseMessages[$user_type] ?? 'User account has been created.';
        $conn->commit();


        if ($user_type === 'adviser') {
            updAdvisory($user_id);
        }

        // Send email notification
        $subjectType = "Insight Account";
        $recipient = getRecipient($user_id);
        $bodyMessage = "
            <h1><b>Notification</b></h1><br><br>
            Your email has been successfully registered!<br>
            Use these credentials to log in:<br>
            <h3>Account credentials</h3><br>
            <b>Email:</b> $user_email<br>
            <b>Password:</b> $user_password<br><br>
            <a href='http://localhost/ReposyncNarrativeManagementSystem/src/login.php'>
            Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus</a>";
        email_queuing($subjectType, $bodyMessage, $recipient);

    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        handleError($e->getCode() == 1062 ? 'Duplicate entry: ' . $e->getMessage() : $e->getMessage());
        exit();
    }

    echo json_encode(['response' => $response, 'message' => $responseMessage]);
}


if ($action == 'ExcelImport') {
    header('Content-Type: application/json');




    $user_type = getPostData('user_type', 'student');
    $excel_data = json_decode($_POST['excelStudData'], true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['response' => 0, 'message' => 'Invalid JSON data.']);
        exit;
    }

    try {
        $conn->begin_transaction();

        foreach ($excel_data as $row) {
            // Split and sanitize Name field
            [$lastName, $firstName, $middleName] = array_map('trim', explode(',', $row['Name']) + ['', '', '']);
            $middleName = rtrim($middleName, '.'); // Remove trailing dot from middle name
            $sex = strtolower(trim($row['Sex']));

            // Validate `sex` field
            if (!in_array($sex, ['male', 'female'])) {
                throw new Exception("Invalid sex value in row for Student No: {$row['Student No']}.");
            }

            $studNo = trim($row['Student No']);
            $adviser_id = getPostData('stud_adviser', '');
            $prog_id = getPostData('stud_Program', '');
            $yrSec_ID = getPostData('stud_Section', '');
            $course_code_id = getPostData('progCourse', null);

            // Generate and hash default password
            $studuser_def_password = generatePassword($studNo);
            $hashed_password = password_hash($studuser_def_password, PASSWORD_DEFAULT);
            $Acc_Email = trim($row['Acc Email']);

            // Check if the student already exists
            $checkStudent = mysqlQuery(
                "SELECT enrolled_stud_id FROM tbl_students WHERE enrolled_stud_id = ?",
                'i',
                [$studNo]
            );


            if (count($checkStudent) > 0) {
                $reactivateStud = "
                    UPDATE tbl_students s
                    JOIN tbl_accounts acc ON acc.user_id = s.user_id
                    JOIN tbl_user_info ui ON ui.user_id = s.user_id
                    SET 
                        ui.first_name = ?, 
                        ui.middle_name = ?, 
                        ui.last_name = ?, 
                        ui.sex = ?, 
                        s.adv_id = ?, 
                        s.program_id = ?, 
                        s.year_sec_Id = ?, 
                        s.course_code_id = ?, 
                        acc.email = ?, 
                        acc.password = ?,
                        acc.status = 1
                    WHERE s.enrolled_stud_id = ?;
                ";
                $reactivateStudSTMT = $conn->prepare($reactivateStud);
                $reactivateStudSTMT->bind_param(
                    'ssssiiiissi',
                    $firstName, $middleName, $lastName, $sex,
                    $adviser_id, $prog_id, $yrSec_ID, $course_code_id,
                    $Acc_Email, $hashed_password, $studNo
                );
                if (!$reactivateStudSTMT->execute()) {
                    throw new Exception("Failed to update student record: " . $reactivateStudSTMT->error);
                }
            } else {
                // Insert new records for student
                $tbl_user_infoQ = "
                    INSERT INTO tbl_user_info (first_name, middle_name, last_name, sex, user_type) 
                    VALUES (?, ?, ?, ?, ?)";
                $tbl_user_infoSTMT = $conn->prepare($tbl_user_infoQ);
                $tbl_user_infoSTMT->bind_param('sssss', $firstName, $middleName, $lastName, $sex, $user_type);
                if (!$tbl_user_infoSTMT->execute()) {
                    throw new Exception("Failed to insert user info: " . $tbl_user_infoSTMT->error);
                }
                $stud_user_ref_id = $tbl_user_infoSTMT->insert_id;

                $tbl_studntsQ = "
                    INSERT INTO tbl_students (enrolled_stud_id, user_id, adv_id, program_id, year_sec_Id, course_code_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
                $tbl_studntsSTMT = $conn->prepare($tbl_studntsQ);
                $tbl_studntsSTMT->bind_param(
                    'iiiiii',
                    $studNo, $stud_user_ref_id, $adviser_id, $prog_id, $yrSec_ID, $course_code_id
                );
                if (!$tbl_studntsSTMT->execute()) {
                    throw new Exception("Failed to insert student record: " . $tbl_studntsSTMT->error);
                }

                $stud_account_q = "
                    INSERT INTO tbl_accounts (user_id, email, password, status) 
                    VALUES (?, ?, ?, 1)";
                $account_stmt = $conn->prepare($stud_account_q);
                $account_stmt->bind_param('iss', $stud_user_ref_id, $Acc_Email, $hashed_password);
                if (!$account_stmt->execute()) {
                    throw new Exception("Failed to insert account record: " . $account_stmt->error);
                }
            }

            // Send email notification
            $subjectType = 'Insight Account';
            $bodyMessage = "
                <h1><b>Notification</b></h1><br><br>
                Your email has been successfully registered!<br>
                Use these credentials to log in:<br>
                <h3>Account credentials</h3><br>
                <b>Email:</b> $Acc_Email<br>
                <b>Password:</b> $studuser_def_password<br><br>
                <a href='http://localhost/ReposyncNarrativeManagementSystem/src/login.php'>
                Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus</a>";
            email_queuing($subjectType, $bodyMessage, $Acc_Email);
        }

        // Commit the transaction
        $conn->commit();
        echo json_encode([
            'response' => 1,
            'message' => 'All student records imported and accounts created successfully.'
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'response' => 0,
            'message' => 'Failed to import records: ' . $e->getMessage()
        ]);
    }
}


if ($action == 'getStudentsList'){
    isset($_SESSION['log_user_type']) || exit();

    header('Content-Type: application/json');
    $fetch_enrolled_stud = "SELECT 
                                u.*,
                                s.*,
                                p.*,
                                a.*,
                                se.*
                            FROM 
                                tbl_students s
                            JOIN 
                                tbl_user_info u ON s.user_id = u.user_id
                            JOIN 
                                program p ON s.program_id = p.program_id
                            JOIN 
                                tbl_accounts a ON s.user_id = a.user_id
                            JOIN 
                                section se ON s.year_sec_Id = se.year_sec_Id
                  
                            WHERE 
                                a.status = 'active' 
                                AND u.user_type = 'student'
                            ORDER BY 
                                a.date_created desc;";
    $data = mysqlQuery($fetch_enrolled_stud, '', []);
    echo json_encode(['response' => 1, 'data' => $data]);
    exit();

}







if ($action == 'updateUserInfo'){
    header('Content-Type: application/json');
    $response = 1;

    $editUser_user_id = getPostData('user_id');
    $edituser_type = getPostData('user_type');


    $responseMessage = updateBasicInfo($editUser_user_id, $edituser_type);
    updateAccEmail($editUser_user_id);

    if ($edituser_type == 'adviser'){
        updAdvisory($editUser_user_id);
    }

    if($edituser_type == 'student'){
        upd_stud_tbl($editUser_user_id);
    }
    echo json_encode(['response' => $response,
        'message' => $responseMessage]);
    exit();

}

if ($action == 'deactivate_account'){

    header('Content-Type: application/json');
    $user_id = isset($_GET['data_id']) ? sanitizeInput($_GET['data_id']) : '';
    if ($user_id !== ''){
        $sql = "UPDATE tbl_accounts SET status = 2  where user_id = ?";
        mysqlQuery($sql,'i',[$user_id]);

    }
    echo json_encode([
        'response' => 1,
        'message' => 'Message user account has been deactivated']);
}




if ($action == 'recoverUser') {
    $user_id = isset($_GET['archived_id']) && sanitizeInput($_GET['archived_id']) ? $_GET['archived_id'] : '';

    header('Content-Type: application/json');
    if (isset($user_id) && $user_id !== '') {
        $sql = "UPDATE tbl_accounts SET status = 1 WHERE acc_id = ?";
        mysqlQuery($sql, 'i', [$user_id]);
    }

    echo json_encode([
        'response' => 1,
        'message' => 'User account has been retrived'
    ]);
}


if ($action === 'getArchiveUsers') {
    $archive_id = isset($_GET['archive_id']) ? intval($_GET['archive_id']) : null;
    header('Content-Type: application/json');


    if ($archive_id) {
        //student , admin, adviser query
        $getArchiveUsers = "SELECT tbl_user_info.*, tbl_accounts.*,  
       program.program_code, 
       section.year, 
       section.section  
FROM tbl_user_info 
JOIN tbl_accounts ON tbl_user_info.user_id = tbl_accounts.user_id 
LEFT JOIN tbl_students ON tbl_user_info.user_id = tbl_students.user_id
LEFT JOIN program ON tbl_students.program_id = program.program_id
LEFT JOIN section ON tbl_students.section_id = section.section_id
WHERE tbl_user_info.user_id = ?";
        $getArchiveUsersSTMT = $conn->prepare($getArchiveUsers);
        if ($getArchiveUsersSTMT) {
            $getArchiveUsersSTMT->bind_param('i', $archive_id);
        } else {
            echo handleError('Query preparation failed.');

        }
    } else {
        $getArchiveUsers = "SELECT tbl_user_info.*, tbl_accounts.* 
                        FROM tbl_user_info 
                        JOIN tbl_accounts ON tbl_user_info.user_id = tbl_accounts.user_id 
                        WHERE tbl_accounts.status = 'inactive'";
        $getArchiveUsersSTMT = $conn->prepare($getArchiveUsers);
        if (!$getArchiveUsersSTMT) {
            echo handleError('Query preparation failed.');
        }
    }


    if ($getArchiveUsersSTMT->execute()) {
        $result = $getArchiveUsersSTMT->get_result();

        if ($result->num_rows > 0) {
            $resultList = [];
            while ($row = $result->fetch_assoc()) {
                $resultList[] = $row;
            }
            echo json_encode(['response' => 1, 'data' => $resultList, 'message' => 'OK']);
        } else {
            echo handleError('Empty Result');
        }
    } else {
        echo handleError( $getArchiveUsersSTMT->error);
    }
}



if ($action == 'getAdvisers') {
    header('Content-Type: application/json');

    $getAdvListsql = "SELECT ui.*, acc.email, acc.status, hd_adv.*, p.*
    FROM tbl_user_info ui 
    JOIN tbl_accounts acc ON ui.user_id = acc.user_id 
    JOIN tbl_advisoryhandle hd_adv ON hd_adv.adv_id = ui.user_id 
    JOIN program p ON p.program_id = hd_adv.program_id 
    WHERE acc.status = 1 AND ui.user_type = 2;";

    $advList = mysqlQuery($getAdvListsql, '', []);

    for ($i = 0; $i < count($advList); $i++) {
        $handle_advList = mysqlQuery(
            "SELECT 
    sec.year_sec_Id, 
    CONCAT(sec.year, sec.section) AS yearSec, -- Proper concatenation of columns
    COUNT(*) AS total_students
FROM  tbl_students s
JOIN  section sec ON  sec.year_sec_Id = s.year_sec_Id
JOIN tbl_accounts acc on acc.user_id = s.user_id
WHERE 
    s.adv_id = ? and acc.status = 1
GROUP BY 
    sec.year_sec_Id, sec.year, sec.section -- Include all non-aggregated columns in GROUP BY
ORDER BY 
    sec.year_sec_Id",
            'i',
            [$advList[$i]['user_id']]
        );
        $advList[$i]['handleAdvisory'] = $handle_advList;
    }
    $data = [
        'response' => 1,
        'data' => $advList
    ];

    echo json_encode($data);
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
    header('Content-Type: application/json');
    $user_id = $_SESSION['log_user_id'];
    $file_id = $_GET['file_id'];
    $sql = "SELECT tbl_revision.*, tbl_user_info.* 
FROM tbl_revision JOIN tbl_user_info ON tbl_user_info.user_id = tbl_revision.user_id
WHERE tbl_revision.file_id = ?";

    $result = mysqlQuery($sql, 'i', [$file_id]);

    if (count($result) > 0) {
        for ($i = 0; $i < count($result); $i++) { // Start from 0 and loop through all rows
            $comment_id = $result[$i]['comment_id'];

            $attachments_sql = "SELECT * FROM revision_attachment WHERE comment_id = ?";
            $attachments_stmt = $conn->prepare($attachments_sql);
            $attachments_stmt->bind_param("i", $comment_id);
            $attachments_stmt->execute();

            // Fetch all attachments as an associative array
            $attachments_result = $attachments_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $result[$i]['attachment'] = $attachments_result; // Assign to 'attachment' key
        }
    }
    echo json_encode(['response' => 1, 'data' => $result]);
    exit();

}
if ($action == 'giveComment') {
    header('Content-Type: application/json');

    $revision_comment = $_POST['message'];
    $file_id = $_POST['file_id'];
    $user_id = $_SESSION['log_user_id'];
    $comment_date = date('Y-m-d H:i:s');

    if ((empty($_FILES['attachment']['name'][0]) && empty($revision_comment))){
       handleError('Empty commment');
    }
    $insert_revision_sql = "INSERT INTO tbl_revision (file_id,user_id ,comment, comment_date) VALUES (?, ?, ?, ?)";

    $comment_id = mysqlQuery($insert_revision_sql, 'iiss', [$file_id,$user_id , $revision_comment, $comment_date])[1];

    if (!empty($_FILES['attachment']['name'][0])) {

        foreach ($_FILES['attachment']['tmp_name'] as $key => $tmp_name) {
            $temp_file = $_FILES['attachment']['tmp_name'][$key];
            $file_type = $_FILES['attachment']['type'][$key];
            $file_name = uniqid() . '.' . pathinfo($_FILES['attachment']['name'][$key], PATHINFO_EXTENSION);
            $destination_directory = 'src/comments_img/';
            $destination_file = $destination_directory . $file_name;
            if (move_uploaded_file($temp_file, $destination_file)) {
                $insert_attachment_sql = "INSERT INTO revision_attachment (comment_id, attach_img_file_name) VALUES (?, ?)";

                mysqlQuery($insert_attachment_sql, 'is', [$comment_id, $file_name]);
            } else {
                handleError( "Error moving file to destination directory.");
            }
        }
    }

    echo json_encode(['response' => 1, 'message' => 'Comment has been sent']);
}


if ($action === 'Notes') {
    header('Content-Type: application/json');

    $response = 1;
    $responseMessage = '';
    $user_id = $_SESSION['log_user_id'];
    $note_title = isset($_POST['noteTitle']) ? sanitizeInput($_POST['noteTitle']) : '';
    $actionType = isset($_POST['actionType']) ? sanitizeInput($_POST['actionType']) : '';
    $message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';
    $announcement_id = isset($_POST['announcementID']) ? sanitizeInput($_POST['announcementID']) : '';
    $actionMessageType = '';
    if ($note_title !== ''  && $message !== '') {
        if ($actionType == 'edit'){
            $sql = "UPDATE announcement SET 
                        title = ?, 
                        description = ?, 
                        announcementUpdated = NOW() ,
                        status = 1
                    where announcement_id = ?";

            mysqlQuery($sql, 'ssi', [$note_title, $message, $announcement_id]);

            $actionMessageType = 'has updated a note';

            $responseMessage = 'Status has been updated!';


        }else {
            $sql = "INSERT INTO announcement  (user_id, title, description,type, status)
                    VALUES (?,?,?,'Notes', 1)";
            mysqlQuery($sql, 'iss', [$user_id, $note_title, $message]);
            $actionMessageType = 'has posted a new note';
            $responseMessage = 'Note has been posted!';

        }

        //emailing notification

        $advFname= $_SESSION['log_user_firstName'];
        $advLname = $_SESSION['log_user_lastName'];

        $subjectType = 'OJT Adviser note post request';
        $bodyMessage = "<H1><b>Notification</b></H1><br>";
        $bodyMessage .= "OJT Adviser: <b>". $advFname." ".$advLname."</b> ".$actionMessageType." <br>
                    Click to review : <a href='http://localhost/ReposyncNarrativeManagementSystem/src/login.php'>
                    Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus</a> ";

        $getAdminID = "SELECT * FROM tbl_user_info where user_type = 'admin'";

        $getAdminRes = mysqlQuery($getAdminID,'', []);
        foreach ($getAdminRes as $row){
            $recipient = getRecipient($row['user_id']);
            if(!email_queuing($subjectType, $bodyMessage, $recipient)){
                handleError('Admin didnt notified through email');
            }
        }

/*        $getAdvStudentsTargetRecipient = "SELECT advisory_list.*, tbl_accounts.status
FROM advisory_list JOIN tbl_accounts on tbl_accounts.user_id = advisory_list.adv_sch_user_id
where adv_sch_user_id = ? and tbl_accounts.status = 'active';";
        $getAdvStudentsTargetRecipientSTMT = $conn->prepare($getAdvStudentsTargetRecipient);
        $getAdvStudentsTargetRecipientSTMT ->bind_param('i', $noteDetails['user_id'] );// OJT adviser students
        $getAdvStudentsTargetRecipientSTMT->execute();
        $result = $getAdvStudentsTargetRecipientSTMT->get_result();
        $bodyMessageToStudents = '<h1>Notification</h1> <br><br>';

        $bodyMessageToStudents .= "<h3><b>Title: </b>".$noteDetails['title']." <h3><br>";
        $bodyMessageToStudents .= "<b>Description: </b> ".$noteDetails['description']." <br>";
        $bodyMessageToStudents .= "<br>Click to review:
 <a href='http://localhost/ReposyncNarrativeManagementSystem/src/index.php'>Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus</a><br>";
        while ($row = $result->fetch_assoc()){
            email_queuing($subjectType, $bodyMessageToStudents, getRecipient($row['stud_sch_user_id']));
        }
        */



        echo json_encode(['response' => $response,
            'message' => $responseMessage]);
    }
}

if ($action == 'getDashboardNotes') {

    if (!isset($_SESSION['log_user_id'])) {
        echo json_encode([
            'response' => 1,
            'data' => []
        ]);
        exit();
    }

    $user_id = $_SESSION['log_user_id'];




    if ( $_SESSION['log_user_type'] === 'student'){
        $stud_info = mysqlQuery('SELECT s.*
        FROM tbl_students s 
        WHERE s.user_id = ?', 'i', [$_SESSION['log_user_id']])[0];
        $adv_id = $stud_info['adv_id'];
        $user_id = $adv_id ;

    }
    $condition  =  $_SESSION['log_user_type'] === 'student' ? "AND status = 1 " : "AND status IN (1, 2, 3) ";


    header('Content-Type: application/json');
    $getNotes = "SELECT * FROM announcement WHERE user_id = ? $condition AND type = 'Notes' ORDER BY announcementUpdated DESC";
    $res = mysqlQuery($getNotes, 'i', [$user_id]);





    if ($res && count($res) > 0) {
        for ($i = 0; $i < count($res); $i++) {

            $formattedDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $res[$i]['announcementPosted'])->format('m/d/Y h:i A');
            $res[$i]['announcementPosted'] = $formattedDateTime;
        }
        echo json_encode([
            'response' => 1,
            'data' => $res
        ]);
    } else {
        handleError('No notes posted');
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
    header('Content-Type: application/json');
    $response = 1;
    $responseMessage = '';

    $user_id = $_SESSION['log_user_id'];
    $note_title = isset($_POST['Activitytitle']) ? sanitizeInput($_POST['Activitytitle']) : Null;
    $actionType = isset($_POST['actionType']) ? sanitizeInput($_POST['actionType']) : '';
    $actDescription = isset($_POST['description']) ? sanitizeInput($_POST['description']) : Null;
    $announcement_id = isset($_POST['announcementID']) ? sanitizeInput($_POST['announcementID']) : Null;;
    $startingDate = isset($_POST['startDate']) ? sanitizeInput($_POST['startDate']): null;
    $endinggDate = isset($_POST['endDate']) ? sanitizeInput($_POST['endDate']): Null;
    $announcementTarget = isset($_POST['announcementTarget']) ? sanitizeInput($_POST['announcementTarget']): 'N/A';
    $emailNotif = isset($_POST['emailNotif']) ? sanitizeInput($_POST['emailNotif']): Null;

    if ($note_title !== '' ) {
        if ($actionType == 'edit'){
            $sql = "UPDATE announcement SET 
                        title = ?, description = ?, starting_date = ?, end_date = ?, SchedAct_targetViewer = ?,
                        announcementPosted = NOW() 
                    where announcement_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssi', $note_title, $actDescription,$startingDate,$endinggDate,  $announcementTarget,$announcement_id);
            $stmt->execute();
            $responseMessage = '';
            $responseMessage = 'Activity has been updated';

        }else {
            $sql = "INSERT INTO announcement  (user_id, title, description , starting_date, end_date,type, status, SchedAct_targetViewer)
                    VALUES (?,?,?,?,?,'schedule and activities','Active', ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('isssss', $user_id, $note_title, $actDescription, $startingDate, $endinggDate, $announcementTarget);
            $stmt->execute();
            $responseMessage = 'New activity has been posted';

        }

        if ($emailNotif){
            $responseMessage .= ' ,users will get notified through email!';
            $userAnnouncementTarget = '';
            $userAnnouncementTargetSTMT = '';
            $activityDate = '';
            $subjectType = "Activity and Schedule Announcement";
            $bodyMessage = "<H1><b>Notification</b></H1><br><br>";
            $bodyMessage = "<h3><b>".$note_title."</b></h3><br>"
                .$actDescription."<br>";
            if ($startingDate === $endinggDate){
                $activityDate = date("M d, Y g:i A", strtotime($startingDate));
                $bodyMessage .= "Date: <b>".$activityDate. "</b>";
            }else{
                $bodyMessage .= "Start: <b>". date("M d, Y g:i A", strtotime($startingDate))."</b <br>";
                $bodyMessage .= "End: <b>". date("M d, Y g:i A", strtotime($endinggDate))."</b <br>";
            }

            if ($announcementTarget == 'All'){
                $userAnnouncementTarget = "SELECT tbl_user_info.user_id
FROM tbl_user_info
JOIN tbl_accounts ON tbl_accounts.user_id = tbl_user_info.user_id WHERE tbl_accounts.status = 'active' ";
                $userAnnouncementTargetSTMT = $conn->prepare($userAnnouncementTarget);

            }else{
                $userAnnouncementTarget = "SELECT tbl_user_info.user_id
FROM tbl_user_info 
	JOIN tbl_accounts ON tbl_accounts.user_id = tbl_user_info.user_id 
	JOIN tbl_students ON tbl_students.user_id = tbl_user_info.user_id, program
WHERE tbl_accounts.status = 'active' and  program.program_code = ?;";
                $userAnnouncementTargetSTMT = $conn->prepare($userAnnouncementTarget);
                $userAnnouncementTargetSTMT->bind_param('s', $announcementTarget);
            }
            $userAnnouncementTargetSTMT->execute();
            $result = $userAnnouncementTargetSTMT->get_result();
            while ($row = $result->fetch_assoc()){
                $recipient = getRecipient($row['user_id']);
                email_queuing($subjectType, $bodyMessage, $recipient);
            }
        }
        echo json_encode(['response' => $response, 'message' => $responseMessage]);
    }
}


if ($action == 'getDashboardActSched'){
    header('Content-Type: application/json');



    $actSched = "SELECT *    
    FROM announcement 
        WHERE 1 = 1
          AND status = 'Active'
            AND type = 'schedule and activities'
        ORDER BY starting_date;
        ";

    $res = mysqlQuery($actSched,'',[]);
    if (count($res) > 0) {
        for ($i = 0; $i < count($res); $i++) {
            $res[$i]['starting_date'] = date("F j, Y", strtotime($res[$i]['starting_date']));
            $res[$i]['end_date'] = date("F j, Y", strtotime($res[$i]['end_date']));
        }

        echo json_encode(['response' => 1,
            'data' => $res]);
    }else{
        handleError('No Activity and Schedule posted');
    }


}
if ($action == 'ProgYrSec') {
    header('Content-Type: application/json');
    $response = 1;
    $responseMessage = '';

    $program_code = isset($_POST['ProgramCode']) ? sanitizeInput($_POST['ProgramCode']) : '';
    $program_name = isset($_POST['ProgramName']) ? sanitizeInput($_POST['ProgramName']) : '';
    $year = isset($_POST['year']) ? sanitizeInput($_POST['year']) : '';
    $section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
    $actionType = isset($_POST['action_type']) ? sanitizeInput($_POST['action_type']) : '';
    $id = isset($_POST['ID']) ? sanitizeInput($_POST['ID']) : '';

    $update_program = "UPDATE program SET program_code = ?, program_name = ? WHERE program_id = ?";
    $insert_program = "INSERT INTO program (program_code, program_name) VALUES (?, ?)";
    $update_yrSec = "UPDATE section SET year = ?, section = ? WHERE year_sec_Id = ?";
    $insert_yrSec = "INSERT INTO section (year, section) VALUES (?, ?)";

    try {
        // Begin transaction
        $conn->begin_transaction();

        // Handle program insert/update
        if ($program_code !== '' && $program_name !== '') {
            if (isset($actionType) && $actionType == 'edit') {
                $stmt = $conn->prepare($update_program);
                $stmt->bind_param('ssi', $program_code, $program_name, $id);
                $responseMessage = 'Program information has been updated.';
                $stmt->execute();
            } else {
                $stmt = $conn->prepare($insert_program);
                $stmt->bind_param('ss', $program_code, $program_name);
                $responseMessage = 'New program has been added.';
                $stmt->execute();
                $program_id =  $conn->insert_id;
            }

            $stmt->close();

            // Handle ojt_course_json
            if (isset($_POST['ojt_course_json'])) {
                $course_jsonData = $_POST['ojt_course_json'];
                $decodedData = json_decode($course_jsonData, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    foreach ($decodedData as $item) {
                        $courseCode = $item['courseCode'] ?? null;
                        $ojtHoursOption = $item['ojtHoursOption'] ?? null;

                        if ($courseCode && $ojtHoursOption) {
                            $ojt_insert = "INSERT INTO tbl_course_code (course_code, ojt_hours, program_id) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($ojt_insert);
                            $stmt->bind_param('sii', $courseCode, $ojtHoursOption, $program_id);
                            $stmt->execute();
                            $stmt->close();
                        } else {
                            throw new Exception('Invalid course data.');
                        }
                    }
                } else {
                    throw new Exception('Invalid JSON data.');
                }
            }
        }

        // Handle year/section insert/update
        if ($year !== '' && $section !== '') {
            // Ensure year and section combination is unique
            $yrSec = "SELECT * FROM section WHERE year = ? AND section = ?";
            $stmt = $conn->prepare($yrSec);
            $stmt->bind_param('is', $year, $section);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                throw new Exception('Year and section already exist.');
            }
            $stmt->close();

            if (isset($actionType) && $actionType == 'edit') {
                $stmt = $conn->prepare($update_yrSec);
                $stmt->bind_param('isi', $year, $section, $id);
                $responseMessage = 'Year and section have been updated.';
            } else {
                $stmt = $conn->prepare($insert_yrSec);
                $stmt->bind_param('is', $year, $section);
                $responseMessage = 'New year and section added!';
            }
            $stmt->execute();
            $stmt->close();
        }

        // Commit the transaction
        $conn->commit();

        echo json_encode(['response' => $response, 'message' => $responseMessage]);
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo json_encode(['response' => 0, 'message' => $e->getMessage()]);
        exit();
    }
}


if ($action == 'getYrSecJson'){
    header('Content-Type: application/json');

    $yrSec = "SELECT * FROM  section order by year asc";
    $yrSecs = mysqlQuery($yrSec, '', []);

    echo json_encode(['response' => 1,
        'data' => $yrSecs]);
}






if ($action == 'getProgJSON') {
    header('Content-Type: application/json');

    $getProgQuery = "
        SELECT 
    program.program_id,
    program.program_code,
    program.program_name,
    COUNT(tbl_course_code.course_code) AS total_courses,
    SUM(tbl_course_code.ojt_hours) AS total_ojt_hours,
    GROUP_CONCAT(tbl_course_code.course_code_id SEPARATOR ', ') AS courses_id,
    GROUP_CONCAT(tbl_course_code.course_code SEPARATOR ', ') AS courses,
    GROUP_CONCAT(tbl_course_code.ojt_hours SEPARATOR ', ') AS course_ojt_hours
FROM 
    program
LEFT JOIN 
    tbl_course_code ON program.program_id = tbl_course_code.program_id
GROUP BY 
    program.program_id
ORDER BY 
    total_ojt_hours;
";

    $programs = mysqlQuery($getProgQuery, '', []);
    echo json_encode([
        'response' => 1,
        'data' => $programs,
    ]);
}







if ($action == 'getAdvNotes'){
    header('Content-Type: application/json');
    $getpendingAdvNotes= "SELECT announcement.*, tbl_user_info.*
    FROM announcement 
    JOIN tbl_user_info ON announcement.user_id = tbl_user_info.user_id
        WHERE announcement.status = 1
            AND type = 'Notes'
        ORDER BY announcement.announcementUpdated desc;";

    $res = mysqlQuery($getpendingAdvNotes,'', []);

    if (count($res) > 0){
        for ($i = 0; $i < count($res); $i++) {
            $res[$i]['starting_date'] = date("F j, Y", strtotime($res[$i]['starting_date']));
            $res[$i]['end_date'] = date("F j, Y", strtotime($res[$i]['end_date']));
        }

        echo json_encode(['response' => 1,
            'data' => $res]);

    }else{
        handleError('No pending adviser notes');
    }
}
if ($action == 'UpdateNotePostReq'){
    header('Content-Type: application/json');
    $noteStat = isset($_POST['NoteStat']) ? sanitizeInput($_POST['NoteStat']): '';
    $declineReason = isset($_POST['reason']) ? sanitizeInput($_POST['reason']): 'N/A';
    $announcement_id = isset($_POST['announcementID']) ? sanitizeInput($_POST['announcementID']): '';

    $noteStatChoices  = array('Pending', 'Hidden', 'Active', 'Declined');
    if (!in_array($noteStat, $noteStatChoices)){
        handleError("Note status has been modified please reload the page");

        exit();
    }

    if ($noteStat !== '' && $announcement_id !== ''){
        $sql = "UPDATE announcement SET 
                        status = ?,
                        reason = ?    
                    where announcement_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $noteStat, $declineReason, $announcement_id);
        if (!$stmt->execute()){
           handleError( $stmt->erro);
            exit();
        }

        if (isset($_POST['emailNotif']) and $_POST['emailNotif'] == 'Notify'){ // email notification toggled by admin
            $getAdvNotes= "SELECT * FROM announcement where announcement_id = ?";
            $getAdvNotesStmt = $conn->prepare($getAdvNotes);
            $getAdvNotesStmt->bind_param('i',$announcement_id);
            $getAdvNotesStmt->execute();
            $res = $getAdvNotesStmt->get_result();
            $noteDetails = $res->fetch_assoc();
            $getTargetRecipient = '';
            $subjectType = 'Note announcement';
            $bodyMessage = "<H1><b>Notification</b></H1><br><br>";
            if ($noteStat === 'Declined'){
                $bodyMessage .= "<h3>Note post request has been <b>Declined.</b></h3> <br>";
                $bodyMessage .= "<b>Title: </b>".$noteDetails['title']." <br>";
                $bodyMessage .= "<b>Description: </b>".$noteDetails['description']." <br>";
                $bodyMessage .= "<br><b>Reason: </b>".$declineReason." <br>";
                $bodyMessage .= "Click to review:
 <a href='http://localhost/ReposyncNarrativeManagementSystem/src/dashboard.php'>Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus</a><br>";
                $targetRecipient = getRecipient($noteDetails['user_id']);
                email_queuing($subjectType, $bodyMessage, $targetRecipient /*recipient OJT adviser*/);
            }elseif ($noteStat === 'Active'){
                $bodyMessage .= "<h3>Note post request has been Approved.</h3> <br>";
                $bodyMessage .= "<b>Title: </b>".$noteDetails['title']." <br>";
                $bodyMessage .= "<b>Description: </b>".$noteDetails['description'].". <br>";
                $bodyMessage .= "<br>Your students will also get notified about this post<br>";
                $bodyMessage .= "Click to review:
 <a href='http://localhost/ReposyncNarrativeManagementSystem/src/dashboard.php'>Insight: An online on-the-job training narrative report management system for Cavite State University - Carmona Campus</a><br>";
                $targetRecipient = getRecipient($noteDetails['user_id']);
                email_queuing($subjectType, $bodyMessage, $targetRecipient /*recipient OJT adviser*/);





            }
        }

        echo json_encode(['response'=> 1,
            'message' => 'Status has been updated']);
        exit();
    }else{
        echo "Please put the required input fields";
        exit();
    }

}
if ($action === 'getSubmittedNarrativeReport'){
header('Content-Type: application/json');
    $submittedUploadReq = "SELECT n.*, ui.*, s.*, semAY.* 
FROM narrativereports n
JOIN tbl_students s ON n.enrolled_stud_id = s.enrolled_stud_id
JOIN tbl_user_info ui ON ui.user_id = s.user_id
JOIN tbl_aysem semAY ON semAY.id = n.ay_sem_id
WHERE s.adv_id = ?
  AND semAY.Curray_sem = 1
  AND n.file_status = 3
  OR n.file_status IN (1, 2);";

    $result = mysqlQuery($submittedUploadReq, 'i', [$_SESSION['log_user_id']]);

    if (count($result) > 0){
        $dataByNarrativeId = [];
        foreach ($result as $row) {
            $key = $row['narrative_id'];
            $row['narrative_id'] = urlencode(encrypt_data($row['narrative_id'], $secret_key));
            $dataByNarrativeId[$key] = $row;
        }

        echo json_encode(['response' => 1,
            'data' => $dataByNarrativeId] );
        exit();

    }else{
        echo json_encode(['response' => 1,
            'data' => []] );
        exit();

    }

}


if ($action == 'getPublishedNarrativeReport'){
    header('Content-Type: application/json');
    $approveConvertedNarratives = "SELECT n.* , ui.*, s.*, p.*, ay.* FROM narrativereports n
    JOIN tbl_students s on n.enrolled_stud_id = s.enrolled_stud_id
    JOIN program p on p.program_id = s.program_id
    JOIN tbl_aysem ay on ay.id = n.ay_sem_id
    JOIN tbl_user_info ui on ui.user_id = s.user_id
    WHERE n.convertStatus = 3;";

    $approveConvertedNarrativesRes = mysqlQuery($approveConvertedNarratives, '' ,[]);


    if (count($approveConvertedNarrativesRes) > 0){
        $dataByNarrativeId = [];
        foreach ($approveConvertedNarrativesRes as $row) {
            $key = $row['narrative_id'];
            $row['narrative_id'] = urlencode(encrypt_data($row['narrative_id'], $secret_key));
            $dataByNarrativeId[$key] = $row;
        }

        echo json_encode(['response' => 1,
            'data' => $dataByNarrativeId] );
        exit();

    }else{
        echo json_encode(['response' => 1,
            'data' => []] );
        exit();

    }



}
if ($action == 'UpdStudSubNarrativeReport'){

    header('Content-Type: application/json');

    $narrative_id = getPostData('narrative_id');
    $uploadStat = getPostData('UploadStat');
    $reason = getPostData('reason', 'N/A');
    $status = [ "Approved" => 3, "Declined" => 2];

    if (!array_key_exists($uploadStat, $status)){
        handleError('Status not exist');
    }


    $selectedStat = $status[$uploadStat];
    try {
        $upd_narrative_stat_query = mysqlQuery(
            'UPDATE narrativereports SET file_status = ? , remarks = ?
                where narrative_id = ?', 'isi', [$selectedStat, $reason, $narrative_id]);

        if ($selectedStat === 3){
            QueueNarrativeReportFlipbookConversion($narrative_id);
        }
        echo json_encode(['response' => 1,
            'message' => 'Student narrative report status has been updated']);
    }catch (Exception $e){
        handleError($e->getMessage());
    }


}


if ($action == 'dshbGePendingFinalReports') {
    if ($_SESSION['log_user_type'] == 'admin') {

        $result = getTotalNarrativeReports('', 1,'');

    } else if ($_SESSION['log_user_type'] == 'adviser') {

        $result = getTotalNarrativeReports('', 1, $_SESSION['log_user_id']);
    }

    echo $result;

    exit();

}
if ($action == 'totalPublihedReport'){

    $file_status = $_GET['file_status'];// 1,2,3,4

    $result = getTotalNarrativeReports('', $file_status,'');
    echo $result;
    exit();
}


if ($action === 'total_Users'){
    $userType = $_GET['userType'];
    $accStatType = $_GET['accType'];
    $types = '';
    $params = [];
    if ($userType !== '' &$accStatType !== ''){
        $result = totalUsers($userType, $accStatType);
    }else{ // total adviser advisory
        $totalUserquery = "SELECT COUNT(tbl_students.adv_id) as totaluserCount
                            FROM tbl_students JOIN tbl_accounts 
                            ON tbl_students.user_id = tbl_accounts.user_id 
                            WHERE tbl_accounts.status = 1 
                            AND tbl_students.adv_id = ?;";
        $types = 'i';
        $params [] = $_SESSION['log_user_id'];
        $result = mysqlQuery($totalUserquery, $types, $params)[0]['totaluserCount'];
    }



    echo $result;


}
if ($action == 'dshbDeclinedFinalReports') {

    if ($_SESSION['log_user_type'] == 'adviser') {

        $total_declined = getTotalNarrativeReports('',2, $_SESSION['log_user_id']);
    }
    echo $total_declined;

    exit();

}
if ($action == 'dshbPendStudWeeklyReport'){
    $getTotalStudPedingWeeklyReport = "SELECT COUNT(*) AS totalStudentUnreadReport 
FROM `weeklyreport` 
    JOIN tbl_students on tbl_students.user_id = weeklyreport.stud_user_id 
WHERE tbl_students.adv_id = ? AND weeklyreport.readStatus = 'Unread';
";
    $getTotalStudPedingWeeklyReportSTMT  = $conn->prepare($getTotalStudPedingWeeklyReport);
    $getTotalStudPedingWeeklyReportSTMT->bind_param('i',$_SESSION['log_user_id']);
    $getTotalStudPedingWeeklyReportSTMT->execute();
    $result = $getTotalStudPedingWeeklyReportSTMT->get_result();
    if ($result->num_rows > 0) {
        echo $result->fetch_assoc()['totalStudentUnreadReport'];
    } else {
        echo 0;
    }
    exit();
}
if ($action == 'pendingADVnoteReq') {
    $userType = $_SESSION['log_user_type'];
    $userId = $_SESSION['log_user_id'];

    $query = "SELECT COUNT(*) AS totalPendingNoteReq FROM announcement WHERE  type = 'Notes'
                                                           and status = 'Pending'";
    $types = '';
    $params = [];
    if ($userType == 'adviser') {
        $query .= " AND user_id = ?";
        $types .= 'i';
        $params[] = $userId;
    }

    $result = mysqlQuery($query, $types, $params)[0];
    echo $result['totalPendingNoteReq'];
    exit();
}

if ($action == "get_User_info"){
    header('Content-Type: application/json');


    if (!isset($_SESSION['log_user_id'])){
        echo json_encode(['response' => 2, //no user login
            'data'=>[]]);
        exit();
    }

    $user_id = isset($_GET['data_id']) ? $_GET['data_id'] : $_SESSION['log_user_id'];

    $get_User_info = "SELECT ui.*, acc.*, 
       stud.enrolled_stud_id, 
       stud.adv_id,  
       stud.ojt_center, 
       stud.ojt_contact, 
       stud.OJT_started, 
       stud.OJT_ended, 
       p.*, ys.*
            FROM tbl_user_info ui
            INNER JOIN tbl_accounts acc ON ui.user_id = acc.user_id
            LEFT JOIN tbl_students stud on ui.user_id = stud.user_id
            LEFT JOIN program p on p.program_id = stud.program_id
            LEFT JOIN section ys on ys.year_sec_Id = stud.year_sec_Id
            WHERE ui.user_id = ?";

    $profile_Info = mysqlQuery($get_User_info, 'i', [$user_id])[0];


    echo json_encode(['response' => 1,
        'data'=>$profile_Info]);
}


if ($action == 'profileUpdate') {


    header('Content-Type: application/json');
    $user_id = $_SESSION['log_user_id'];

    try {
        updateBasicInfo($user_id, $_SESSION['log_user_type']);

        if ($_SESSION['log_user_type'] === 'student'){
            $ojt_contact = getPostData('stud_ojtContact');
            $ojt_center = getPostData('stud_OJT_center');
            $ojt_started = getPostData('OJT_started', null);
            $ojt_ended = getPostData('OJT_ended', null);
            $updStudInfo = "UPDATE tbl_students SET ojt_center= ?, ojt_contact = ?, 
                        OJT_started = ?, OJT_ended = ? where user_id = ?";

            mysqlQuery($updStudInfo, 'ssssi',[$ojt_center,$ojt_contact,$ojt_started,
                $ojt_ended, $user_id] );

        }

        // Handle profile image upload
        if (isset($_FILES['profileImg']) && $_FILES['profileImg']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profileImg']['tmp_name'];
            $fileName = $_FILES['profileImg']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $allowedfileExtensions = array('jpg', 'png', 'jpeg');

            // Validate file extension
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $targetDir = "src/userProfile/";
                $dest_path = $targetDir . $newFileName;

                // Delete old profile image
                $getUserProfile = "SELECT profile_img_file FROM tbl_user_info WHERE user_id = ?";
                $getUserProfileSTMT = $conn->prepare($getUserProfile);
                $getUserProfileSTMT->bind_param('i', $user_id);
                $getUserProfileSTMT->execute();
                $result = $getUserProfileSTMT->get_result();
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $oldFilePath = "src/userProfile/" . $row['profile_img_file'];
                    if ($row['profile_img_file'] !== 'N/A' && file_exists($oldFilePath)) {
                        unlink($oldFilePath);  // Delete the old file
                    }
                }

                // Save new profile image
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $updProfImg = "UPDATE tbl_user_info 
                                   SET profile_img_file = ? 
                                   WHERE user_id = ?";
                    $updProfImgSTMT = $conn->prepare($updProfImg);
                    $updProfImgSTMT->bind_param('si', $newFileName, $user_id);
                    $updProfImgSTMT->execute();
                    $_SESSION['log_user_profileImg'] = $newFileName;
                }
            } else {
                handleError("Invalid file type. Only 'jpg', 'png', 'jpeg' allowed.");
                return;
            }
        }

        header('Content-Type: application/json');
        $response = 1;
        $responseMessage = 'Profile has been updated';

        echo json_encode(['response' => $response, 'message' => $responseMessage]);
        exit;

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            handleError("Contact number already exists");
        } else {
            handleError('Error: ' . $e->getMessage());
        }
    }
}



if ($action == 'updateAcc'){
    header('Content-Type: application/json');
    $response = 1;
    $responseMessage = 'Acccount Information has been updated';
    $user_id = $_SESSION['log_user_id'];
    updateAccEmail($user_id);
    update_password($user_id);
    echo json_encode(['response' => $response,'message' => $responseMessage]);

}


if ($action == 'weeklyJournalList'){
    header('Content-Type: application/json');
    $weeklyJournalListlQuery = "SELECT s.* ,u.* FROM tbl_students s 
        JOIN tbl_user_info u ON s.user_id = u.user_id 
         WHERE s.adv_id = ? ORDER BY (SELECT activity_date 
        FROM activity_logs 
        WHERE file_id IN ( SELECT file_id FROM weeklyReport WHERE stud_user_id = u.user_id ) 
        ORDER BY activity_date DESC LIMIT 1) DESC;";

    $weeklyJournalList = mysqlQuery($weeklyJournalListlQuery, 'i', [$_SESSION['log_user_id']]);

    if (count($weeklyJournalList) > 0){

        for ($i = 0; $i < count($weeklyJournalList); $i++){

            $latest_activity = getLatestActivity($weeklyJournalList[$i]['user_id']) ?? null;
            $formatted_date_time = $latest_activity ? date("M d, Y g:i A", strtotime($latest_activity)) : 'No Activity';

            $weeklyJournalList[$i]['lastActivity'] = $formatted_date_time;


            if (checkNewWeeklyReports($weeklyJournalList[$i]['user_id'])){
                $weeklyJournalList[$i]['unreadJournal'] = true;
            }else{
                $weeklyJournalList[$i]['unreadJournal'] = false;
            }


            $weeklyJournalList[$i]['user_id'] = urlencode(encrypt_data($weeklyJournalList[$i]['user_id'], $secret_key));


        }

        echo json_encode(['response' => 1,
            'data' => $weeklyJournalList,
            'message' => 'Success']);
    }else{
        echo json_encode(['response' => 2,
            'data' => [],
            'message' => 'No Active / Assigned students found for this adviser.']);
    }

}

if ($action == 'newAy') {
    header('Content-Type: application/json');

    $aystartYear = getPostData('aystartYear', 'N/A');
    $ayendYear = getPostData('ayendYear', 'N/A');
    $semester = getPostData('semester', 'N/A');

    if (in_array('N/A', [$aystartYear, $ayendYear])) {
        handleError('Invalid academic year');
    }

    $semesters = [
        'First' => 1,
        'Second' => 2,
        'Midyear' => 3,
    ];

    if (!isset($semesters[$semester])) {
        handleError('Invalid semester');
    }

    $conn->begin_transaction();
    try {

        if ($ayendYear <= $aystartYear) {
            throw new Exception('Invalid academic year range: Ending year cannot be less than or equal to the starting year.');
        }
        if ($ayendYear > $aystartYear + 1) {
            throw new Exception('Invalid academic year range: Ending year cannot be more than one year greater than the starting year.');
        }

        $checkQuery = "SELECT COUNT(*) AS count 
                   FROM tbl_aysem 
                   WHERE ayStarting = ? AND ayEnding = ? AND Semester = ?";
        $result = mysqlQuery($checkQuery,'iii' ,[ $aystartYear, $ayendYear, $semesters[$semester]]);


        if ($result[0]['count'] > 0) {
            throw new Exception('Duplicate semester for this academic year.');
        }

        // If no duplicates, insert the new record
        $newAyquery = "INSERT INTO tbl_aysem (ayStarting, ayEnding, Semester, Curray_sem) VALUES (?, ?, ?, 2)";
        $newAyqueryStmt = $conn->prepare($newAyquery);
        $newAyqueryStmt->bind_param('iii', $aystartYear, $ayendYear, $semesters[$semester]);
        $newAyqueryStmt->execute();
        $aySem_id = $conn->insert_id;

        // Decode JSON data for available courses
        $available_ayCourse_json = $_POST['Ay_availableCourse'];
        $decoded_ayCourse_json = json_decode($available_ayCourse_json, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $insertAvailableCourseQuery = "
                INSERT INTO tbl_courseavailability (course_code_id, year_sec_Id, ay_sem_id) 
                VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertAvailableCourseQuery);

            foreach ($decoded_ayCourse_json as $available_ayCourse) {
                if (!isset($available_ayCourse['course_code_id'], $available_ayCourse['yearSec']) ||
                    !is_array($available_ayCourse['yearSec'])) {
                    throw new Exception('Invalid JSON structure.');
                }

                $course_code_id = $available_ayCourse['course_code_id'];
                $yrSecArray = $available_ayCourse['yearSec'];

                foreach ($yrSecArray as $yrSec_ID) {
                    $insertStmt->bind_param('iii', $course_code_id, $yrSec_ID, $aySem_id);
                    $insertStmt->execute();
                }
            }
        } else {
            throw new Exception('Invalid JSON data.');
        }

        $conn->commit();
        echo json_encode([
            'response' => 1,
            'message' => 'New academic year created'
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        //if error is duplicate entry put in the handle error if ayStarting "Duplicate entry for Starting year"
        //if error is duplicate entry put in the handle error if ayEnding  "Duplicate entry for Ending  year"
        handleError($e->getMessage());
    } finally {
        $conn->close();
    }
}

if ($action === 'updateAy') {
    header('Content-Type: application/json');

    $aystartYear = getPostData('aystartYear', 'N/A');
    $ayendYear = getPostData('ayendYear', 'N/A');
    $semester = getPostData('semester', 'N/A');
    $ay_ID = getPostData('ay_ID', 'N/A');

    if (in_array('N/A', [$aystartYear, $ayendYear, $ay_ID], true)) {
        handleError('Missing or invalid academic year or ID.');
    }

    $semesters = [
        'First' => 1,
        'Second' => 2,
        'Midyear' => 3,
    ];

    if (!isset($semesters[$semester])) {
        handleError('Invalid semester.');
    }

    // Begin database transaction
    $conn->begin_transaction();
    try {
        if ($ayendYear <= $aystartYear) {
            throw new Exception('Invalid academic year range: Ending year cannot be less than or equal to the starting year.');
        }
        if ($ayendYear > $aystartYear + 1) {
            throw new Exception('Invalid academic year range: Ending year cannot be more than one year greater than the starting year.');
        }

        $checkQuery = "SELECT COUNT(*) AS count 
                   FROM tbl_aysem 
                   WHERE ayStarting = ? AND ayEnding = ? AND Semester = ?";
        $result = mysqlQuery($checkQuery,'iii' ,[ $aystartYear, $ayendYear, $semesters[$semester]]);





        $updateAyQuery = "UPDATE tbl_aysem SET ayStarting = ?, ayEnding = ?, Semester = ? WHERE id = ?";
        $updateAyStmt = $conn->prepare($updateAyQuery);
        $updateAyStmt->bind_param('iiii', $aystartYear, $ayendYear, $semesters[$semester], $ay_ID);
        $updateAyStmt->execute();

        if ($updateAyStmt->affected_rows > 0){
            if ($result[0]['count'] > 0) {
                throw new Exception('Duplicate semester for this academic year.');
            }
        }


        // Delete existing course availability records
        $deleteCoursesQuery = "DELETE FROM tbl_courseavailability WHERE ay_sem_id = ?";
        $deleteCoursesStmt = $conn->prepare($deleteCoursesQuery);
        $deleteCoursesStmt->bind_param('i', $ay_ID);
        $deleteCoursesStmt->execute();

        if (!isset($_POST['Ay_availableCourse'])) {
            throw new Exception('Missing Ay_availableCourse in POST data.');
        }

        $availableCoursesJson = $_POST['Ay_availableCourse'];
        $decodedCourses = json_decode($availableCoursesJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decoding error: ' . json_last_error_msg());
        }

        // Insert new course availability records
        $insertCoursesQuery = "
            INSERT INTO tbl_courseavailability (course_code_id, year_sec_Id, ay_sem_id)
            VALUES (?, ?, ?)";
        $insertCoursesStmt = $conn->prepare($insertCoursesQuery);

        foreach ($decodedCourses as $course) {
            if (!isset($course['course_code_id'], $course['yearSec']) || !is_array($course['yearSec'])) {
                throw new Exception('Invalid JSON structure for course availability.');
            }

            $courseCodeId = $course['course_code_id'];
            foreach ($course['yearSec'] as $yearSecId) {

                $insertCoursesStmt->bind_param('iii', $courseCodeId, $yearSecId, $ay_ID);
                $insertCoursesStmt->execute();
            }
        }

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'response' => 1,
            'message' => 'Academic year has been updated successfully.',
        ]);
    } catch (Exception $e) {
        // Rollback transaction on failure
        $conn->rollback();
        handleError($e->getMessage());
    } finally {
        $conn->close();
    }
}


if ($action == 'AcadYears'){
    header('Content-Type: application/json');
    $acadYearsQuery = "SELECT * FROM tbl_aysem order by ayStarting asc";
    $result = mysqlQuery($acadYearsQuery, '', []);
    echo json_encode(['response' => 1,
        'data' => $result,]);
}
if ($action == 'avaialbleyrSecCourse'){
    $acadYearID = $_GET['acadYearID'];
    header('Content-Type: application/json');


    $avaialbleyrSecCoursesQuery = "SELECT * FROM tbl_courseavailability where ay_sem_id = ?";
    $result = mysqlQuery($avaialbleyrSecCoursesQuery, 'i', [$acadYearID]);
    echo json_encode(['response' => 1,
        'data' => $result,]);
}

if ($action == 'changeAcadYear') {
    header('Content-Type: application/json');
    $acadYearID = $_GET['acadYearID'];
    $resetUser = $_GET['resetUser'];

    $resetUserVal = ['Yes' => 'Yes', 'No' => 'No'];
    if (!isset($resetUserVal[$resetUser])) {
        handleError('Invalid reset option');
    }

    $conn->begin_transaction();
    try {
        if ($resetUserVal[$resetUser] === 'Yes') {
            // Get current academic year
            $currAcadYear = mysqlQuery(
                'SELECT id FROM tbl_aysem WHERE Curray_sem = 1',
                'i',
                []
            )[0]['id'];

            // Fetch student user IDs for current academic year
            $student_user_ids = [];
            $getStudNarrative = mysqlQuery(
                'SELECT ui.user_id 
                 FROM narrativereports n 
                 JOIN tbl_students s ON s.enrolled_stud_id = n.enrolled_stud_id 
                 JOIN tbl_user_info ui ON s.user_id = ui.user_id 
                 WHERE n.ay_sem_id = ?',
                'i',
                [$currAcadYear]
            );

            foreach ($getStudNarrative as $studs) {
                $student_user_ids[] = $studs['user_id'];
            }

            if (!empty($student_user_ids)) {
                // Prepare placeholders for `IN` clause
                $placeholders = implode(',', array_fill(0, count($student_user_ids), '?'));

                // Delete from `activity_logs`
                $delActLogs = $conn->prepare(
                    "DELETE act
                     FROM activity_logs AS act
                     JOIN weeklyreport AS wr ON act.file_id = wr.file_id
                     WHERE wr.stud_user_id IN ($placeholders)"
                );
                $delActLogs->bind_param(str_repeat('i', count($student_user_ids)), ...$student_user_ids);
                $delActLogs->execute();

                // Delete from `revision_attachment`
                $delRevAtt = $conn->prepare(
                    "DELETE ra
                     FROM revision_attachment AS ra
                     JOIN tbl_revision AS tr ON ra.comment_id = tr.comment_id
                     JOIN weeklyreport AS wr ON wr.file_id = tr.file_id
                     WHERE wr.stud_user_id IN ($placeholders)"
                );
                $delRevAtt->bind_param(str_repeat('i', count($student_user_ids)), ...$student_user_ids);
                $delRevAtt->execute();

                $delRev = $conn->prepare(
                    "DELETE tr
                     FROM tbl_revision AS tr
                     JOIN weeklyreport AS wr ON wr.file_id = tr.file_id
                     WHERE wr.stud_user_id IN ($placeholders)"
                );
                $delRev->bind_param(str_repeat('i', count($student_user_ids)), ...$student_user_ids);
                $delRev->execute();

                $delWeeklyReport = $conn->prepare(
                    "DELETE FROM weeklyreport WHERE stud_user_id IN ($placeholders)"
                );
                $delWeeklyReport->bind_param(str_repeat('i', count($student_user_ids)), ...$student_user_ids);
                $delWeeklyReport->execute();
            }

            $deactUsers = "UPDATE tbl_accounts acc 
                           SET acc.status = 2 
                           WHERE acc.user_id IN (
                               SELECT ui.user_id 
                               FROM tbl_user_info ui 
                               WHERE ui.user_type != 1
                           )";
            $conn->query($deactUsers);
        }

        $conn->query("UPDATE tbl_aysem SET Curray_sem = 2");

        $setAcadYearQuery = $conn->prepare("UPDATE tbl_aysem SET Curray_sem = 1 WHERE id = ?");
        $setAcadYearQuery->bind_param('i', $acadYearID);
        $setAcadYearQuery->execute();

       $conn->commit();
        echo json_encode(['response' => 1, 'message' => 'Academic year has been changed successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        handleError($e->getMessage());
    }
}

if ($action == 'acadyearPrograms'){
    header('Content-Type: application/json');
    $ay_id = $_GET['ay_id'];
    $getayPrograms = "SELECT DISTINCT p.* FROM tbl_courseavailability tca
                                JOIN tbl_course_code cc on cc.course_code_id = tca.course_code_id
                                JOIN program p ON p.program_id = cc.program_id
                                JOIN tbl_aysem aysem ON aysem.id = tca.ay_sem_id
                                
                                WHERE  aysem.id = ?;";
    $result = mysqlQuery($getayPrograms, 'i', [$ay_id]);



    echo json_encode(['response' => 1,
        'data' => $result]);
}

if ($action == 'AvailableCourse') {
    header('Content-Type: application/json');

    $programID = $_GET['programID'];

    $result = mysqlQuery("SELECT DISTINCT  cc.* FROM tbl_courseavailability tca
                                JOIN tbl_course_code cc on cc.course_code_id = tca.course_code_id
                                JOIN program p ON p.program_id = cc.program_id
                                JOIN tbl_aysem aysem ON aysem.id = tca.ay_sem_id
                                
                                WHERE  aysem.Curray_sem = 1 and p.program_id = ?; ", 'i', [$programID]);

    echo json_encode(['response' => 1, 'data' => $result]);
}

if ($action == 'AvailableyrSec') {
    header('Content-Type: application/json');

    $course_Id = $_GET['Course_Id'];

    $result = mysqlQuery("SELECT sec.* FROM tbl_courseavailability tca
JOIN section sec on sec.year_sec_Id = tca.year_sec_Id
JOIN tbl_aysem aysem ON aysem.id = tca.ay_sem_id
WHERE  aysem.Curray_sem = 1 and tca.course_code_id = ?; ", 'i', [$course_Id]);

    echo json_encode(['response' => 1, 'data' => $result]);
}


if ($action === 'updateJournalRemark') {
    header('Content-Type: application/json');

    $remark = $_GET['journalStatus'];
    $remarks = [
        'pending' => 1,
        'approved' => 2,
        'revision' => 3
    ];
    $file_id = $_GET['file_id'];
    if (!$file_id) {
        echo handleError("Invalid file ID.");

    }

    if (!array_key_exists($remark,$remarks)) {
        echo handleError('Invalid status');
    }


    $sql = "UPDATE weeklyreport SET upload_status = ? WHERE file_id = ?";
    if (!mysqlQuery($sql, 'si', [$remarks[$remark], $file_id])) {
        echo handleError("Failed to update status.");

    }

    $insert_activity_log = "INSERT INTO activity_logs (file_id, activity_type, activity_date) 
                                VALUES (?, 'status update', CURRENT_TIMESTAMP)";
    mysqlQuery($insert_activity_log, 'i', [$file_id]);

    $status = $remarks[$remark];

    switch ($status) {
        case 1:
            $formattedStatus = 'Pending';
            $status_color = 'text-warning';
            break;
        case 2:
            $formattedStatus = 'Approved';
            $status_color = 'text-success';
            break;
        case 3:
            $formattedStatus = 'With Revision';
            $status_color = 'text-info';
            break;

        default:
            $formattedStatus = 'Unknown';
            $status_color = 'text-danger';
            break;
    }
    $getWeeklyReport = "SELECT * FROM weeklyreport WHERE file_id = ?";
    $weeklyReportsRow = mysqlQuery($getWeeklyReport, 'i', [$file_id])[0];

    $subjectType = "Weekly Report Update";
    $messageBody = "Your submission of weekly report on <b>" .
        date("M d, Y g:i A", strtotime($weeklyReportsRow['upload_date'])) .
        "</b> has been reviewed and updated its status to <b>$formattedStatus</b>";
    $recipient = getRecipient($weeklyReportsRow['stud_user_id']);

    email_queuing($subjectType, $messageBody, $recipient);

    echo json_encode(['response' => 1, 'message' => "Student journal status has been updated successfully."]);



}


if ($action == 'ChartData'){
    header('Content-Type: application/json');
    $data = [];
    $renderCharData = $_GET['renderChartData'];

    if ($renderCharData == 'Users') {
        $activeStudent = totalUsers(3, 1);
        $activeAdv = totalUsers(2, 1);
        $archStudent = totalUsers(3, 2);
        $archAdv = totalUsers(2, 2);

        $data[] =['label' => 'Active student', 'value' => $activeStudent, 'onclickElement' => 'stud_list'];
        $data[] =['label' => 'Active adviser', 'value' => $activeAdv, 'onclickElement' => 'adv_list'];
        $data[] =['label' => 'Archived student', 'value' => $archStudent, 'onclickElement' => 'account_archived'];
        $data[] =['label' => 'Archived adviser', 'value' => $archAdv, 'onclickElement' => 'account_archived'];

    }
    if (
        $renderCharData == 'adminNarrative') {
        $totalNarrativePerAY = "SELECT  tbl_aysem.ayStarting, tbl_aysem.ayEnding, 
        COUNT(narrativereports.narrative_id) AS total_reports 
FROM 
    narrativereports
JOIN 
    tbl_aysem ON narrativereports.ay_sem_id = tbl_aysem.id
WHERE narrativereports.file_status = 3 and narrativereports.convertStatus = 3
GROUP BY 
    tbl_aysem.ayStarting, 
    tbl_aysem.ayEnding;
";
        $result = mysqlQuery($totalNarrativePerAY, '', []);
        foreach ($result as $row) {
            $data[] =['label' => $row['ayStarting'].' - '.  $row['ayEnding'] , 'value' => $row['total_reports'], 'onclickElement' => 'dashboard_narrative'];
        }


    }

    if ($renderCharData == 'adviserNarrative') {
        $numberOfNarrativeSubPerSec = "SELECT  
    section.year, 
    section.section, 
    COUNT(narrativereports.narrative_id) AS total_reports 
FROM 
    section
LEFT JOIN 
    tbl_students ON tbl_students.year_sec_Id = section.year_sec_Id
LEFT JOIN 
    narrativereports ON narrativereports.enrolled_stud_id = tbl_students.enrolled_stud_id
WHERE 
    tbl_students.adv_id = ?
GROUP BY 
    section.year, 
    section.section;";
        $result = mysqlQuery($numberOfNarrativeSubPerSec, 'i', [$_SESSION['log_user_id']]);
        foreach ($result as $row) {
            $data[] =['label' => $row['year'].  $row['section'] , 'value' => $row['total_reports'], 'onclickElement' => 'dashboard_ReviewUploadNarrative'];
        }


    }

    echo json_encode([
        'response' => 1,
        'data' => $data
    ]);
}


