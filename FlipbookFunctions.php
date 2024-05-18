<?php
include 'vendor/autoload.php';
use \ConvertApi\ConvertApi;

function convert_pdf_to_image($file_name):bool{

    $basePath = "src/NarrativeReports_Images/";
    $subdirectoryName = $file_name;

    if (!is_dir($basePath . $subdirectoryName)) {
        mkdir($basePath . $subdirectoryName, 0755);

        ConvertApi::setApiSecret('ibjeGR1eEpfx6tzY');
        $result = ConvertApi::convert('png', [
            'File' => 'src/NarrativeReportsPDF/'.$file_name.'.pdf',
            'FileName' => $file_name."_page",
            'ImageResolution' => '800',
        ], 'pdf'
        );

        $result->saveFiles($basePath.$subdirectoryName);
        return true;
    } else {
        return false;
    }
}
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function isPDF($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    return strtolower($extension) === 'pdf';
}
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        // Not a directory
        return false;
    }
    // Open the directory
    if ($handle = opendir($dir)) {
        // Iterate over the directory
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                // If it's a directory, recursively delete it
                if (is_dir($filePath)) {
                    $result = deleteDirectory($filePath);
                    if ($result !== true) {
                        return false; // Return false if error occurs
                    }
                } else {
                    // If it's a file, delete it
                    if (!unlink($filePath)) {
                        return false; // Return false if error occurs
                    }
                }
            }
        }
        // Close the directory handle
        closedir($handle);

        // Now the directory should be empty, so delete it
        if (is_dir($dir)) {
            if (!rmdir($dir)) {
                return false; // Return false if error occurs
            }
        } else {
            return false; // Return false if directory does not exist
        }
    } else {
        // Error opening directory
        return false;
    }
    return true; // Deletion successful
}

function delete_pdf($pdf){
    if (file_exists($pdf)) {
        if (unlink($pdf)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function check_uniq_stud_id($stud_id){
    include 'DatabaseConn/databaseConn.php';
    $sql = 'SELECT stud_school_id FROM narrativereports WHERE stud_school_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $stud_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        return false;
    } else {
        $stmt->close();
        return true;
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
