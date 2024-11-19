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




if ($action == 'updateReadStat'){
    $file_id = $_GET['file_id'];
    $updateReadStat = "UPDATE weeklyreport SET readStatus = 'Read' where file_id = ?";
    $updateReadStatSTMT = $conn->prepare($updateReadStat);
    $updateReadStatSTMT->bind_param('i', $file_id);

    if ($updateReadStatSTMT->execute()){
        echo 1;
    }else{
        echo $updateReadStatSTMT->error;
    }
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


    $ay_Submitted = isset($_POST['startYear']) && isset($_POST['endYear']) ? sanitizeInput($_POST['startYear']).','.sanitizeInput($_POST['endYear']) :'';
    $sem_Submitted = getPostData('semester');


    $stud_info = mysqlQuery('SELECT s.*, ui.* 
from tbl_students s 
    JOIN tbl_user_info ui on ui.user_id = s.user_id
   
where s.user_id = ?', 'i', [$_SESSION['log_user_id']])[0];



    $ojt_adviser_UID = $stud_info['adv_id'];
    $school_id = $stud_info['enrolled_stud_id'];
    $new_file_name =  $school_id.'_'. uniqid('', true) . ".pdf";
    $current_date_time = date('Y-m-d H:i:s');

    try {
        $new_final_report = "INSERT INTO narrativereports
    (enrolled_stud_id ,  ay_submitted, sem_submitted,  narrative_file_name)
    values (?,?,?,?)";

        $valueTypes = "isss";
        $params = [$school_id, $ay_Submitted,$sem_Submitted, $new_file_name ];

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

    $ay_Submitted = isset($_POST['startYear']) && isset($_POST['endYear']) ? sanitizeInput($_POST['startYear']) . ',' . sanitizeInput($_POST['endYear']) : '';
    $sem_Submitted = getPostData('semester');

    $update_query = 'UPDATE narrativereports SET ay_submitted = ?, sem_submitted = ?,file_status = 1, upload_date = NOW()';
    $types = 'ss';
    $params = [$ay_Submitted, $sem_Submitted];

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

    $submtdNarratives = mysqlQuery('SELECT * from narrativereports JOIN tbl_students
         on tbl_students.enrolled_stud_id = narrativereports.enrolled_stud_id  
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


    $total_narrativequery = "SELECT COUNT(*) as totalSubNarrative FROM narrativereports n
                JOIN tbl_students s on s.enrolled_stud_id = n.enrolled_stud_id where user_id = ?";


    $total_result = mysqlQuery($total_narrativequery ,'i', [$_SESSION['log_user_id']])[0];

    $checkStudProgramQuery = "SELECT * FROM program p 
    JOIN  tbl_students s on s.program_id = p.program_id where s.user_id = ?";

    $StudProgramLimitNarratives = mysqlQuery($checkStudProgramQuery, 'i', [$_SESSION['log_user_id']])[0];

    $isStudCanSubmitNewNarrative = $total_result['totalSubNarrative'] < $StudProgramLimitNarratives['totalNarratives'] ?
        true : false;


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


if ($action === 'ExcelImport'){
    header('Content-Type: application/json');
    $user_type = getPostData('user_type', 'student');

    $excel_data = json_decode($_POST['excelStudData'], true);

    try {
        $conn->begin_transaction();

        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($excel_data as $row) {
                $firstName = $row['First name'];
                $middleName = $row['Middle name'];
                $lastName = $row['Last name'];
                $contactNo = $row['Contact No'];
                $address = $row['Address'];
                $sex = strtolower($row['Sex']);

                $tbl_user_infoQ = 'INSERT INTO tbl_user_info (first_name, middle_name, last_name, address, contact_number, user_type) VALUES (?, ?, ?, ?, ?, ?)';
                $tbl_user_infoSTMT = $conn->prepare($tbl_user_infoQ);
                $tbl_user_infoSTMT->bind_param('ssssss', $firstName, $middleName, $lastName, $address, $contactNo, $user_type);
                $tbl_user_infoSTMT->execute();

                $stud_user_ref_id = $tbl_user_infoSTMT->insert_id;

                $studNo = $row['Student No'];
                $OJT_Center = $row['OJT Center'];
                $OJT_loc = $row['OJT Location'];
                $adviser_id = getPostData('stud_adviser', '');
                $yrSec_ID = getPostData('stud_Section', '');
                $prog_id = getPostData('stud_Program', '');


                $tbl_studntsQ = 'INSERT INTO tbl_students (enrolled_stud_id, user_id, adv_id, 
                          program_id, year_sec_Id, ojt_center, ojt_location) VALUES (?, ?, ?, ?, ?, ?, ?)';

                $tbl_studntsSTMT = $conn->prepare($tbl_studntsQ);
                $tbl_studntsSTMT->bind_param('iiiiiss', $studNo,
                    $stud_user_ref_id, $adviser_id, $prog_id,
                    $yrSec_ID, $OJT_Center, $OJT_loc);
                $tbl_studntsSTMT->execute();

                $Acc_Email = strtolower($row['Acc Email']);
                $studuser_def_password = generatePassword($studNo);
                $hashed_password = password_hash($studuser_def_password, PASSWORD_DEFAULT);

                $stud_account_q = 'INSERT INTO tbl_accounts (user_id, email, password, status) VALUES (?, ?, ?, 1)';
                $account_stmt = $conn->prepare($stud_account_q);
                $account_stmt->bind_param('iss', $stud_user_ref_id, $Acc_Email, $hashed_password);
                $account_stmt->execute();

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
        }
        // Commit the transaction if everything succeeds
        $conn->commit();
        echo json_encode(['response' => 1,
            'message' => 'All student records imported and accounts created successfully.']);

    } catch (Exception $e) {
        $conn->rollback();
        handleError($e->getMessage());
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


    $getAdvListsql = "SELECT ui.*, prog.*, sec.* , acc.*
    FROM tbl_user_info ui 
    LEFT JOIN  tbl_advisoryhandle hndl_class ON ui.user_id = hndl_class.adv_id 
    LEFT JOIN tbl_accounts acc ON ui.user_id = acc.user_id 
    LEFT JOIN program prog ON hndl_class.program_id = prog.program_id 
    LEFT JOIN section sec ON hndl_class.year_sec_Id = sec.year_sec_Id 
    WHERE acc.status = 1 AND ui.user_type = 2;";


    $advList = mysqlQuery($getAdvListsql, '', []);

    for ($i = 0; $i < count($advList); $i++){
        $advList[$i]['totalStud'] = getTotalAdvList($advList[$i]['user_id'],
            $advList[$i]['program_id'], $advList[$i]['year_sec_Id']);
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
    $user_id = $_SESSION['log_user_id'];
    $file_id = $_GET['file_id'];
    $sql = "SELECT tbl_revision.*, tbl_user_info.* 
FROM tbl_revision JOIN tbl_user_info ON tbl_user_info.user_id = tbl_revision.user_id
WHERE tbl_revision.file_id = ?";
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
                            <div class="flex flex-col justify-center items-center">
                                <div class="avatar">
                                    <div class="w-10 rounded-full">
                                     <img src="userProfile/'. ($row['profile_img_file'] != 'N/A' ? $row['profile_img_file'] : 'prof.jpg' ).'" />                               
                                     </div>
                                    </div>
                             <span class="text-xs">You</span>
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
                        <div class="flex flex-col justify-center items-center">
                           <div class="avatar">
                                <div class="w-10 rounded-full">
                                    <img src="userProfile/'. ($row['profile_img_file'] != 'N/A' ? $row['profile_img_file'] : 'prof.jpg' ).'" />
                                </div>
                            </div>
                            <span class="text-xs">'.$row['first_name'].'</span>
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
        WHERE announcement.status = 'Pending'
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
    $submittedUploadReq = "SELECT n.* , ui.*, s.* FROM narrativereports n
    JOIN tbl_students s on n.enrolled_stud_id = s.enrolled_stud_id
    JOIN tbl_user_info ui on ui.user_id = s.user_id
    WHERE s.adv_id = ? and n.file_status IN (1, 2, 3);";

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
    $approveConvertedNarratives = "SELECT n.* , ui.*, s.*, p.* FROM narrativereports n
    JOIN tbl_students s on n.enrolled_stud_id = s.enrolled_stud_id
    JOIN program p on p.program_id = s.program_id
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
        $totalUserquery = "SELECT COUNT(tbl_user_info.user_id) AS totaluserCount 
FROM tbl_user_info JOIN tbl_accounts ON tbl_accounts.user_id = tbl_user_info.user_id 
WHERE tbl_user_info.user_type = ? AND tbl_accounts.status = ?";
        $types = 'ii';
        $params[] = $userType;
        $params[] = $accStatType;

    }else{ // total adviser advisory
        $totalUserquery = "SELECT COUNT(tbl_students.adv_id) as totaluserCount
                            FROM tbl_students JOIN tbl_accounts 
                            ON tbl_students.user_id = tbl_accounts.user_id 
                            WHERE tbl_accounts.status = 1 
                            AND tbl_students.adv_id = ?;";
        $types = 'i';
        $params [] = $_SESSION['log_user_id'];

    }

    $result = mysqlQuery($totalUserquery, $types, $params)[0]['totaluserCount'];


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
       stud.ojt_contact     , 
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



    $user_id = $_SESSION['log_user_id'];

    try {
        updateBasicInfo($user_id, $_SESSION['log_user_type']);

        if ($_SESSION['log_user_type'] === 'student'){
            $ojt_loc = getPostData('stud_ojtLocation');
            $ojt_center = getPostData('stud_OJT_center');
            $updStudInfo = "UPDATE tbl_students SET ojt_center= ?, ojt_location = ? where user_id = ?";

            mysqlQuery($updStudInfo, 'ssi',[$ojt_center,$ojt_loc, $user_id] );

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
