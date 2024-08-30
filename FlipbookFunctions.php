<?php
include 'vendor/autoload.php';
use \ConvertApi\ConvertApi;

function convert_pdf_to_image($file_name):bool{

    $basePath = "src/NarrativeReports_Images/";
    $subdirectoryName = $file_name;

    if (!is_dir($basePath . $subdirectoryName)) {
        mkdir($basePath . $subdirectoryName, 0755);

        ConvertApi::setApiSecret('oc2ymqnjEq3ZQDbk');
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

function handleNarrativeUpload($fields, $old_filename, $new_file_name) {
    $file_name = $_FILES['final_report_file']['name'];
    $file_temp = $_FILES['final_report_file']['tmp_name'];

    if (isPDF($file_name)) {
        $pdf = 'src/NarrativeReportsPDF/' . $old_filename;
        $flipbook_page_dir = 'src/NarrativeReports_Images/' . str_replace('.pdf', '', $old_filename);
        if (!delete_pdf($pdf) || !deleteDirectory($flipbook_page_dir)) {
            $message = 'Error deleting old files.';
            handleError($message);
            exit();
        }

        $pdf_file_path = "src/NarrativeReportsPDF/" . $new_file_name;
        move_uploaded_file($file_temp, $pdf_file_path);

        $report_pdf_file_name = "{$fields['first_name']}_{$fields['last_name']}_{$fields['program']}_{$fields['section']}_{$fields['school_id']}";
        if (convert_pdf_to_image($report_pdf_file_name)) {
            echo json_encode(['response' => 1, 'message' => 'Narrative report has been updated!']);
            exit();
        } else {
            echo json_encode(['response' => 2, 'message' => 'Error during PDF to image conversion.']);
            exit();
        }
    }
}


function handleNarrativeFileRename($old_filename, $new_file_name) {
    $pdf_dir = 'src/NarrativeReportsPDF/';
    $img_dir = 'src/NarrativeReports_Images/';
    if (is_dir($pdf_dir)) {
        if ($handle = opendir($pdf_dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file == $old_filename) {
                    $oldFilePath = $pdf_dir . $old_filename;
                    $newFilePath = $pdf_dir . $new_file_name;
                    if (rename($oldFilePath, $newFilePath)) {
                        $old_flipbook_page_dir = str_replace('.pdf', '', $old_filename);
                        $new_flipbook_page_dir = str_replace('.pdf', '', $new_file_name);
                        renameFlipbookDirectory($img_dir, $old_flipbook_page_dir, $new_flipbook_page_dir);
                    } else {
                        handleError("Error renaming PDF file.");
                    }
                }
            }
            closedir($handle);
        } else {
            handleError("Error opening PDF directory.");
        }
    } else {
        handleError("PDF directory does not exist.");
    }
}


function renameFlipbookDirectory($img_dir, $old_dir, $new_dir) {
    if (is_dir($img_dir . $old_dir)) {
        if (rename($img_dir . $old_dir, $img_dir . $new_dir)) {
            if (is_dir($img_dir . $new_dir)) {
                if ($handle_img = opendir($img_dir . $new_dir)) {
                    while (false !== ($file_img = readdir($handle_img))) {
                        if ($file_img != "." && $file_img != "..") {
                            $oldImagePath = $img_dir . $new_dir . "/" . $file_img;
                            $newImageName = str_replace($old_dir, $new_dir, $file_img);
                            $newImagePath = $img_dir . $new_dir . "/" . $newImageName;
                            if (!rename($oldImagePath, $newImagePath)) {
                                handleError("Error renaming image file.");
                            }
                        }
                    }
                    closedir($handle_img);
                } else {
                    handleError("Error opening image directory.");
                }
            } else {
                handleError("New image directory does not exist.");
            }
        } else {
            handleError("Error renaming image directory.");
        }
    } else {
        handleError("Old image directory does not exist.");
    }
}


function handleError($message) {
    echo json_encode(['response' => 0, 'message' => $message]);
    exit();
}

