<?php


if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}



include_once 'DatabaseConn/databaseConn.php';
include_once 'functions.php';
session_start();
date_default_timezone_set('Asia/Manila');
function decrypt_data($data, $key) {
    $cipher = "aes-256-cbc";
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
}
function encrypt_data($data, $key) {
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($encrypted . '::' . $iv);
}
$secret_key = 'TheSecretKey#02';
$action = $_GET['action'];

if ($action== 'signUp'){
    extract($_POST);
    echo 1;
}
if ($action== 'login'){
    $_SESSION['log_user_type'] = 'admin';
    extract($_POST);
    echo 1;
}

if ($action == 'addWeeklyReport'){
    extract($_POST);
    echo $newWeeklyReport;
}
if ($action == 'resubmitReport'){
    extract($_POST);
    echo $resubmitReport;

}
if ($action == 'newFinalReport'){
    extract($_POST);
    $first_name = isset($_POST['first_name']) ? sanitizeInput($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitizeInput($_POST['last_name']) : '';
    $program = isset($_POST['program']) ? sanitizeInput($_POST['program']) : '';
    $section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
    $ojt_adviser = isset($_POST['ojt_adviser']) ? sanitizeInput($_POST['ojt_adviser']) : '';
    $school_id = isset($_POST['school_id']) && is_numeric($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';
    if ($first_name !== '' && $last_name !== '' && $program !== '' && $section !== '' && $ojt_adviser !== '' && $school_id !== '') {
        if(isset($_FILES['final_report_file'])) {
            $file_name = $_FILES['final_report_file']['name'];
            $file_temp = $_FILES['final_report_file']['tmp_name'];
            $file_type = $_FILES['final_report_file']['type'];
            $file_error = $_FILES['final_report_file']['error'];
            $file_size = $_FILES['final_report_file']['size'];

            if (isPDF($file_name)){

                $file_first_name = str_replace(' ', '', $first_name);
                $file_last_name = str_replace(' ', '', $last_name);
                $new_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section.".pdf";
                $current_date_time = date('Y-m-d H:i:s');
                $narrative_status = 'OK';
                if($file_error === UPLOAD_ERR_OK) {

                    $new_final_report = $conn->prepare("INSERT INTO narrativereports (stud_school_id, 
                              first_name, last_name, program, section, OJT_adviser,narrative_file_name, upload_date, file_status)
                              values (?,?,?,?,?,?,?,?,?)");

                    $new_final_report->bind_param("issssssss",
                        $school_id, $first_name, $last_name,
                        $program, $section, $ojt_adviser, $new_file_name,
                        $current_date_time, $narrative_status);


                    if (!$new_final_report->execute()){
                        echo 'query error';
                        exit();
                    }
                    $new_final_report->close();
                    $destination = "src/NarrativeReportsPDF/" . $new_file_name;
                    move_uploaded_file($file_temp, $destination);
                    $report_pdf_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section;

                    if (convert_pdf_to_image($report_pdf_file_name)){
                        echo 1;
                        exit();
                    }
                    else{
                        echo 'Flip book Conversion error';
                    }
                }
                else {
                    echo 'file error';
                }
            }else {
                echo 'not pdf';
            }
        }else{
            echo 'empty file';
        }
    }else{
        echo 'form data are empty';
    }
    exit();
}
if ($action == 'get_narrativeReports') {
    $sql = "SELECT * FROM narrativereports order by upload_date desc";
    $result = $conn->query($sql);
    $number = 1;
    if ($result === false) {
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (isset($_GET['homeTable']) && $_GET['homeTable'] == 'request') {
                    echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $number++ . '</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row["program"] . '</span>
                        </td>
                        <td class="p-3 text-end ">
                            <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>';
                }
                else if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'adviser' and isset($_GET['dashboardTable']) && $_GET['dashboardTable'] == 'request') {
                    echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">' . $row['OJT_adviser'] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-sm">' . $row["program"] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-sm">' . $row["section"] . '</span>
                        </td>
                        <td class="p-3 text-end ">
                            <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>';
                }
                else if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'admin' and isset($_GET['dashboardTable']) && $_GET['dashboardTable'] == 'request') {
                    echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row['OJT_adviser'] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row["program"] . '</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">' . $row["section"] . '</span>
                        </td>
                        <td class="p-3 text-end ">
                            <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                            <a onclick="openModalForm(\'EditNarrative\');editNarrative(this.getAttribute(\'data-narrative\'))" id="archive_narrative" data-narrative="' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) .'" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                      </tr>';
                }else{

                    exit();
                }
            }
        }
    }
    $conn->close();
}
if ($action == 'narrativeReportsJson'){

    $narrative_id = decrypt_data($_GET['narrative_id'], $secret_key);

    $sql = "SELECT * FROM narrativereports WHERE narrative_id = ? ORDER BY upload_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $narrative_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $row['narrative_id'] = encrypt_data($row['narrative_id'], $secret_key);
            echo json_encode($row);
        } else {
            echo "Error: No data found for the given narrative ID.";
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

if ($action === 'UpdateNarrativeReport'){
    extract($_POST);
    $first_name = isset($_POST['first_name']) ? sanitizeInput($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitizeInput($_POST['last_name']) : '';
    $program = isset($_POST['program']) ? sanitizeInput($_POST['program']) : '';
    $section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
    $ojt_adviser = isset($_POST['ojt_adviser']) ? sanitizeInput($_POST['ojt_adviser']) : '';
    $school_id = isset($_POST['school_id']) && is_numeric($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '';
    $narrative_id = isset($_POST['narrative_id']) ? sanitizeInput($_POST['narrative_id']) : '';
    if ($first_name !== '' && $last_name !== '' && $program !== '' && $section !== '' && $ojt_adviser !== '' && $school_id !== ''  && $narrative_id !== '') {
        $file_first_name = str_replace(' ', '', $first_name);
        $file_last_name = str_replace(' ', '', $last_name);
        $new_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section.".pdf";
        $current_date_time = date('Y-m-d H:i:s');
        $narrative_status = 'OK';
        $narrative_id = decrypt_data($narrative_id,$secret_key);
        $old_filename = '';
        $sql = "SELECT * FROM narrativereports WHERE narrative_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $narrative_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $old_filename = $row['narrative_file_name'];
            }
        }

        $update_final_report = $conn->prepare("UPDATE narrativereports
                                      SET stud_school_id = ?,
                                          first_name = ?,
                                          last_name = ?,
                                          program = ?,
                                          section = ?,
                                          OJT_adviser = ?,
                                          narrative_file_name = ?,
                                          upload_date = ?,
                                          file_status = ?
                                      WHERE narrative_id = ?");
        $update_final_report->bind_param("issssssssi",
            $school_id, $first_name, $last_name,
            $program, $section, $ojt_adviser, $new_file_name,
            $current_date_time, $narrative_status,$narrative_id);


        if (!$update_final_report->execute()){
            echo 'query error';
            exit();
        }else{
            if (isset($_FILES['final_report_file']) && $_FILES['final_report_file']['error'] === UPLOAD_ERR_OK){
                //replace existing by deleting and converting new

                $file_name = $_FILES['final_report_file']['name'];
                $file_temp = $_FILES['final_report_file']['tmp_name'];
                $file_type = $_FILES['final_report_file']['type'];
                $file_error = $_FILES['final_report_file']['error'];
                $file_size = $_FILES['final_report_file']['size'];

                if (isPDF($file_name)){
                    $pdf = 'src/NarrativeReportsPDF/'.$old_filename;
                    $flipbook_page_dir = 'src/NarrativeReports_Images/'. str_replace('.pdf','',$old_filename);
                    if (!delete_pdf($pdf) or !deleteDirectory($flipbook_page_dir)){
                        echo 'dir not deleted';
                        exit();
                    }

                    $file_first_name = str_replace(' ', '', $first_name);
                    $file_last_name = str_replace(' ', '', $last_name);
                    $new_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section.".pdf";
                    $pdf_file_path = "src/NarrativeReportsPDF/" . $new_file_name;
                    move_uploaded_file($file_temp, $pdf_file_path);
                    $report_pdf_file_name = $file_first_name."_".$file_last_name."_".$program."_".$section;
                    if (convert_pdf_to_image($report_pdf_file_name)){
                        echo 1;
                        exit();
                    }
                    else{
                        echo 'error Conversion';
                    }
                }

            }
            else {
                //Nirerename lang ung pdf sa src/NarrativeReportsPDF/  tapos directory
                // at ung lamang ng directory sa src/NarrativeReports_Images/
                // ung rename nito naka base lang sa laman ng database
                // ang purpose para ma reuse ang existing files

                $narrative_reportPDF_path = 'src/NarrativeReportsPDF/';
                $narrative_reportIMG_path = 'src/NarrativeReports_Images/';
                if (is_dir($narrative_reportPDF_path)) {
                    if ($handle = opendir($narrative_reportPDF_path)) {
                        // Rename the PDF file
                        while (false !== ($file = readdir($handle))) {
                            if (pathinfo($file, PATHINFO_EXTENSION) == 'pdf' && $file == $old_filename) {
                                // Rename the pdf file
                                $oldFilePath = $narrative_reportPDF_path . $old_filename;
                                $newFilePath = $narrative_reportPDF_path . $new_file_name;
                                if (rename($oldFilePath, $newFilePath)) {
                                    // Rename the flip book image directory
                                    $old_flipbook_page_directory = str_replace('.pdf', '', $old_filename);
                                    $new_flipbook_page_directory = str_replace('.pdf', '', $new_file_name);
                                    if (is_dir($narrative_reportIMG_path . $old_flipbook_page_directory)) {
                                        if (rename($narrative_reportIMG_path . $old_flipbook_page_directory, $narrative_reportIMG_path . $new_flipbook_page_directory)) {
                                            // Rename image files inside the flip book directory
                                            if (is_dir($narrative_reportIMG_path . $new_flipbook_page_directory)) {
                                                if ($handle_img = opendir($narrative_reportIMG_path . $new_flipbook_page_directory)) {
                                                    while (false !== ($file_img = readdir($handle_img))) {
                                                        if ($file_img != "." && $file_img != "..") {
                                                            // Construct the new filename based on the new directory name pattern
                                                            $oldImagePath = $narrative_reportIMG_path . $new_flipbook_page_directory . "/" . $file_img;
                                                            $newImageName = str_replace($old_flipbook_page_directory, $new_flipbook_page_directory, $file_img);
                                                            $newImagePath = $narrative_reportIMG_path . $new_flipbook_page_directory . "/" . $newImageName;

                                                            // Rename the image file
                                                            if (!rename($oldImagePath, $newImagePath)) {
                                                                echo "* Error renaming image file.";
                                                                echo 0;
                                                                exit();
                                                            }
                                                        }
                                                    }
                                                    closedir($handle_img);
                                                } else {
                                                    echo "Error opening image directory.";
                                                    echo 0;
                                                    exit();
                                                }
                                            } else {
                                                echo "New directory does not exist.";
                                                echo 0;
                                                exit();
                                            }
                                        } else {
                                            echo "Error renaming directory.";
                                            echo 0;
                                            exit();
                                        }
                                    } else {
                                        echo "Directory does not exist.";
                                        echo 0;
                                        exit();
                                    }
                                } else {
                                    echo "Error renaming PDF file.";
                                    echo 0;
                                    exit();
                                }
                            }
                        }
                        closedir($handle);
                    } else {
                        echo "Error opening PDF directory.";
                        echo 0;
                        exit();
                    }
                } else {
                    echo "PDF directory does not exist.";
                    echo 0;
                    exit();
                }
                echo 1;
                exit();
            }
        }
    }
}



