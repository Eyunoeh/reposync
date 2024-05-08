<?php
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
$secret_key = 'TheSecretKey#02'; //id encryption password
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