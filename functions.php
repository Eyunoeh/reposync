<?php
$secret_key = 'TheSecretKey#02'; //id encryption password dont remove

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
function getTotalAdvList($adv_user_id){
    include 'DatabaseConn/databaseConn.php';

    $sql = "SELECT COUNT(*) AS total FROM advisory_list WHERE adv_sch_user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adv_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total = $row['total'];
    $stmt->close();

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
function handleError($message) {
    echo json_encode(['response' => 0, 'message' => $message]);
    exit();
}

