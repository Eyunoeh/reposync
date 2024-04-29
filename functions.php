<?php
include 'vendor/autoload.php';
use \ConvertApi\ConvertApi;

function convert_pdf_to_image($file_name):bool{

    $basePath = "src/NarrativeReports_Images/";
    $subdirectoryName = $file_name;

    if (!is_dir($basePath . $subdirectoryName)) {
        mkdir($basePath . $subdirectoryName, 0755);

        ConvertApi::setApiSecret('1dLsWCbgR9f2Pgrw');
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

