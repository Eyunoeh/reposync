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





