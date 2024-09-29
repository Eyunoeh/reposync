<?php
function updateBasicInfo($editUser_user_id, $edituser_type)
{
    $resMes_adv = 'Adviser Information has been updated!';
    $resMes_stud = 'Student Information has been updated!';

    $editUser_first_name = isset($_POST['user_Fname']) ? sanitizeInput($_POST['user_Fname']) : '';
    $editUser_middle_name = isset($_POST['user_Mname']) ? sanitizeInput($_POST['user_Mname']) : 'N/A';
    $editUser_last_name = isset($_POST['user_Lname']) ? sanitizeInput($_POST['user_Lname']) : '';
    $editUser_sex = isset($_POST['user_Sex']) ? sanitizeInput($_POST['user_Sex']) : '';
    $editUser_contact_number = isset($_POST['contactNumber']) ? sanitizeInput($_POST['contactNumber']) : '';
    $editUser_address = isset($_POST['user_address']) ? sanitizeInput($_POST['user_address']) : '';
    $editUser_email = isset($_POST['user_Email']) ? sanitizeInput($_POST['user_Email']) : '';



    if ($editUser_first_name == '' &&
        $editUser_last_name == '' &&
        $editUser_sex == '' &&
        $editUser_contact_number == '' &&
        $editUser_address == '' &&
        $editUser_user_id == '' &&
        $editUser_email == ''&&
        $edituser_type == '') {

        $responseMessage = 'Error: Some required fields are empty.';
        handleError($responseMessage);

    }

    $responseMessage = $edituser_type == 'student' ? $resMes_stud : $resMes_adv;
    try {
        $sql = "UPDATE tbl_user_info ui
        JOIN tbl_accounts ta ON ui.user_id = ta.user_id
        SET 
            ui.first_name = ?, 
            ui.last_name = ?, 
            ui.middle_name = ?, 
            ui.address = ?, 
            ui.contact_number = ?, 
            ui.sex = ?, 
            ui.user_type = ?, 
            ta.email = ?
        WHERE 
            ui.user_id = ?";

        $types = "ssssssssi";

        $params = [
            $editUser_first_name,
            $editUser_last_name,
            $editUser_middle_name,
            $editUser_address,
            $editUser_contact_number,
            $editUser_sex,
            $edituser_type,
            $editUser_email,
            $editUser_user_id
        ];


        mysqlQuery($sql, $types, $params);


    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $errorMessage = $e->getMessage();
            preg_match("/Duplicate entry '.*' for key '([^']+)'/", $errorMessage, $matches);
            $keyName = $matches[1] ?? 'unknown key';

            if ($keyName == 'contact_number') {
                $responseMessage ="Duplicate contact number.";
            } elseif ($keyName == 'tbl_accounts') {
                $responseMessage = "Email already exist";
            } else {
                $responseMessage = "Duplicate entry for key '$keyName'.";
            }
        } else {
            $responseMessage = $e->getMessage();
        }
        handleError($responseMessage);
    }
    return $responseMessage;
}


function updAdvisory($user_id = null)
{
    try {
        // Delete only if user_id is provided
        if ($user_id !== null) {
            mysqlQuery("DELETE FROM tbl_advisoryhandle WHERE adv_id = ?", 'i', [$user_id]);
        }

        // Handle assigned advisory list
        $assignedadvList = json_decode($_POST['assignedAdvList'], true) ?? '';

        if ($assignedadvList !== '') {
            foreach ($assignedadvList as $assignedadv) {
                $assignedadvsql = "INSERT INTO tbl_advisoryhandle (program_id, year_sec_Id, adv_id) VALUES (?, ?, ?)";
                $types = 'iii';
                $params = [$assignedadv['program'], $assignedadv['section'], $user_id];
                mysqlQuery($assignedadvsql, $types, $params);
            }
        }
    } catch (Exception $e) {
        handleError($e->getMessage());
    }
}
