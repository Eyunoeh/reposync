<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

include 'vendor/autoload.php';
use \ConvertApi\ConvertApi;
use setasign\Fpdi\Fpdi;

use Enqueue\AmqpLib\AmqpConnectionFactory;
use Enqueue\AmqpTools\RabbitMqDlxDelayStrategy;

class PDFWithWatermark extends FPDI
{
    function Header()
    {
    }

    function Footer()
    {

    }
}




function initiateAsyncPDFtoJPGConversion($localFilePath, $apiSecret, $file_name) {
    $apiUrl = "https://v2.convertapi.com/async/convert/pdf/to/jpg?Secret=$apiSecret";

    $postData = [
        'File' => new CURLFile($localFilePath),
        'FileName' => $file_name. "_page"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);


    $result = json_decode($response, true);

    return $result;
}
function pageWatermark($pdfPath, $watermarkImagePath)
{
    $pdf = new PDFWithWatermark();

    $pageCount = $pdf->setSourceFile($pdfPath);

// Loop through each page
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        $templateId = $pdf->importPage($pageNo);
        $size = $pdf->getTemplateSize($templateId);

        $pdf->AddPage();
        $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);

        // Add the watermark image centered on the page
        $watermarkWidth =150; // Width of the watermark
        $pdf->Image($watermarkImagePath, ($size['width'] - $watermarkWidth) / 2, ($size['height'] - $watermarkWidth) / 2, $watermarkWidth);
    }


    return $pdf->Output('F', $pdfPath);
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





function handleNarrativeUpload($old_filename, $new_file_name, $narrative_id) {
    $factory = new AmqpConnectionFactory([
        'host' => 'localhost',
        'port' => 5672,
        'login' => 'guest',
        'password' => 'guest',
        'vhost' => '/',
    ]);



    $file_temp = $_FILES['final_report_file']['tmp_name'];
    if ($old_filename !== ''){
        $pdf = 'src/NarrativeReportsPDF/' . $old_filename;
        $flipbook_page_dir = 'src/NarrativeReports_Images/' . str_replace('.pdf', '', $old_filename);
        delete_pdf($pdf);
        deleteDirectory($flipbook_page_dir);
    }
    $watermarkIMG = 'src/assets/cvsuproperty.png';
    $pdf_file_path = "src/NarrativeReportsPDF/" . $new_file_name;
    move_uploaded_file($file_temp, $pdf_file_path);




    pageWatermark($pdf_file_path, $watermarkIMG);


    $updtNarrativeJobID = "UPDATE narrativereports SET narrativeConvertJobID  = ? where narrative_id = ? ";
    try {
       // $apiSecret = '';
       // $job_id = initiateAsyncPDFtoJPGConversion($pdf_file_path,$apiSecret, $new_file_name)['JobId'];
        $job_id = uniqid();

        mysqlQuery($updtNarrativeJobID, 'si',[$job_id,$narrative_id]);



        $context = $factory->createContext();

        $queue = $context->createQueue('pdf_conversion_queue');
        $context->declareQueue($queue);



        $jobData = json_encode([
            'job_id' => $job_id,
            'pdf_path' => $pdf_file_path,
            'file_name' => $new_file_name,
            'narrative_id' => $narrative_id,
        ]);

        $message = $context->createMessage($jobData);
        $context->createProducer()->send($queue, $message);



    }catch (Exception $exception){
        handleError( $exception->getMessage());
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








//convert_pdf_to_image('Lando_Norrisss_BSCS_4B_212512123.pdf');