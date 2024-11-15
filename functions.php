<?php
$secret_key = 'TheSecretKey#02'; //id encryption password dont remove

function handleError($message) {
    echo json_encode(['response' => 0, 'message' => $message]);
    exit();
}
function getPostData($field, $default = '') {
    return !empty($_POST[$field]) ? sanitizeInput($_POST[$field]) : $default;
}
function encrypt_data($data, $key) {
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($encrypted . '::' . $iv);
}
function decrypt_data($data, $key) {
    $cipher = "aes-256-cbc";
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
}
function mysqlQuery($query, $valueType, $params){
    include 'DatabaseConn/databaseConn.php';
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    if (!empty($params)) {
        $stmt->bind_param($valueType, ...$params);
    }

    if (!$stmt->execute()){
        return  $stmt->error;
    }

    $queryType = strtolower(explode(' ', trim($query))[0]);

    if ($queryType === 'select') {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    } elseif (in_array($queryType, ['insert', 'update', 'delete'])) {
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        if ($queryType === 'insert'){
            $last_inserted_id = $conn->insert_id;
            return [$affectedRows, $last_inserted_id];
        }else{
            return $affectedRows;
        }


    } else {

        $stmt->close();
        return false;
    }
}


function generatePassword($school_id) {
    return "CVSUOJT".$school_id;
}

function getTotalNarrativeReports($program, $file_status, $ojtAdviser) {

    $sql = "SELECT COUNT(*) AS total FROM narrativereports JOIN tbl_students on
    tbl_students.enrolled_stud_id = narrativereports.enrolled_stud_id
                         WHERE 1 = 1";
    $types = '';
    $params = [];
    if ($program !== '') {
        $sql .= ' AND tbl_students.program_id = ?';
        $types .= 'i';
        $params[] = $program;
    }
    if ($file_status !== ''){
        $sql .= ' AND narrativereports.file_status = ? AND narrativereports.convertStatus = 3';
        $types .= 'i';
        $params[] = $file_status;
    }if ($ojtAdviser !== ''){
        $sql .= ' AND tbl_students.adv_id = ?';
        $types .= 'i';
        $params[] = $ojtAdviser;
    }

    $result = mysqlQuery($sql, $types, $params)[0];
    return $result['total'];

}

function getTotalAdvList($adv_user_id, $program_id, $section_id){

    $sql = "SELECT COUNT(*) AS total FROM tbl_students s
                         JOIN tbl_accounts acc on acc.user_id = s.user_id
                         WHERE s.adv_id = ? 
                                             and s.program_id = ? and s.year_sec_Id = ?
                                             and acc.status = 1
                            ";

    $total = mysqlQuery($sql, 'iii', [$adv_user_id, $program_id, $section_id])[0]['total'];

    return $total;
}

function insertActivityLog($activity_type, $file_id) {
    include 'DatabaseConn/databaseConn.php';
    $insert_activity_log = "INSERT INTO activity_logs (file_id, activity_type, activity_date) 
                            VALUES (?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($insert_activity_log);
    $stmt->bind_param("is", $file_id, $activity_type);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false; // Insertion failed
    }
}

function checkConversionStatus($jobId) {
    $url = "https://v2.convertapi.com/async/job/$jobId";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => $error];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {

        $decodedResponse = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return ['responseStatus' => $httpCode, 'JobId' => $jobId, 'dataResponse' => $decodedResponse];
        } else {
            return ['error' => 'Failed to decode JSON response'];
        }
    } else {
        return ['responseStatus' => $httpCode, 'JobId' => $jobId];
    }
}

function saveFlipbookPages($response, $file_name) {
    $basePath = "NarrativeReports_Images/";
    $subdirectoryName = str_replace(".pdf","",$file_name);

    if (!is_dir($basePath . $subdirectoryName)) {
        mkdir($basePath . $subdirectoryName, 0755);

    }
    $saveDirectory = $basePath . $subdirectoryName;

    $results = [];

    if (isset($response['Files']) && is_array($response['Files'])) {
        foreach ($response['Files'] as $file) {

            $fileData = $file['FileData'];
            $fileName = $file['FileName'];
            $savePath = $saveDirectory . '/' . $fileName;

            // Decode the base64 data
            $fileContent = base64_decode($fileData);

            if ($fileContent === false) {
                $results[] = ['error' => "Failed to decode base64 data for $fileName"];
                continue;
            }

            // Save the decoded content to a file
            if (file_put_contents($savePath, $fileContent) === false) {
                $results[] = ['error' => "Failed to save $fileName"];
            } else {
                $results[] = ['status' => "File saved successfully as $fileName"];
            }
        }
    } else {
        $results[] = ['error' => 'No files found in the response.'];
    }

    return $results;
}

function getLatestActivity($user_id){
    $sql = "SELECT * FROM activity_logs
JOIN weeklyReport on weeklyReport.file_id = activity_logs.file_id
WHERE weeklyReport.stud_user_id = ?
ORDER BY activity_logs.activity_date DESC LIMIT 1;";
    $result = mysqlQuery($sql, 'i', [$user_id]);
    if (count($result) > 0){
        return $result[0]['activity_date'];
    }else{
        return '';
    }

}



function checkNewWeeklyReports($stud_sch_user_id)
{
    $getUnreadWeeklyReports = "SELECT * FROM weeklyreport 
         where stud_user_id = ? and readStatus = 2";
    $res = mysqlQuery($getUnreadWeeklyReports, 'i' , [$stud_sch_user_id]);
    if (count($res) > 0){
        return true;
    }else{
        return false;
    }
}


