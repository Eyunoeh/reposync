<?php


function updateBasicInfo($editUser_user_id, $edituser_type) {
    $responseMessages = [
        'adviser' => 'Adviser Information has been updated!',
        'student' => 'Student Information has been updated!'
    ];

    // Fetch user data using the helper function
    $editUser_first_name = getPostData('user_Fname');
    $editUser_middle_name = getPostData('user_Mname', 'N/A');
    $editUser_last_name = getPostData('user_Lname');
    $editUser_sex = getPostData('user_Sex');
    $editUser_contact_number = getPostData('contactNumber', null);
    $editUser_address = getPostData('user_address', null);

    // Validate required fields
    $requiredFields = [
        'First Name' => $editUser_first_name,
        'Last Name' => $editUser_last_name,
        'Sex' => $editUser_sex,
        'User ID' => $editUser_user_id,
        'User Type' => $edituser_type
    ];

    foreach ($requiredFields as $field => $value) {
        if (empty($value)) {
            handleError("Field $field is required.");
            exit();
        }
    }

    // Set response message based on user type
    $responseMessage = $responseMessages[$edituser_type] ?? 'Information has been updated!';

    try {
        // SQL Query to update user info and accounts in a single query
        $sql = "UPDATE tbl_user_info
            
                SET 
                    first_name = ?, 
                    last_name = ?, 
                    middle_name = ?, 
                    address = ?, 
                    contact_number = ?, 
                    sex = ?, 
                    user_type = ?
                   
                WHERE 
                    user_id = ?";

        // Bind parameter types
        $types = "sssssssi";

        // Parameters to bind
        $params = [
            $editUser_first_name,
            $editUser_last_name,
            $editUser_middle_name,
            $editUser_address,
            $editUser_contact_number,
            $editUser_sex,
            $edituser_type,
            $editUser_user_id
        ];

        // Execute the query with the bound parameters
        mysqlQuery($sql, $types, $params);

    } catch (mysqli_sql_exception $e) {
        // Handle duplicate entry errors specifically
        if ($e->getCode() == 1062) {
            $errorMessage = $e->getMessage();
            preg_match("/Duplicate entry '.*' for key '([^']+)'/", $errorMessage, $matches);
            $keyName = $matches[1] ?? 'unknown key';

            switch ($keyName) {
                case 'contact_number':
                    $responseMessage = "Duplicate contact number.";
                    break;

                default:
                    $responseMessage = "Duplicate entry for key '$keyName'.";
                    break;
            }
        } else {
            $responseMessage = $e->getMessage();
        }
        handleError($responseMessage);
    }

    return $responseMessage;
}

function updateAccEmail($user_id)
{
    $editUser_email = getPostData('user_Email');
    $requiredFields = [
        'User Email' => $editUser_email
    ];

    foreach ($requiredFields as $field => $value) {
        if (empty($value)) {
            handleError("Field $field is required.");
            exit();
        }
    }
    try {
        $sql = "UPDATE tbl_accounts
                SET 
                    email = ?
                WHERE 
                    user_id = ?";

        mysqlQuery($sql,'si', [$editUser_email,$user_id]);

    }catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $errorMessage = $e->getMessage();
            preg_match("/Duplicate entry '.*' for key '([^']+)'/", $errorMessage, $matches);
            $keyName = $matches[1] ?? 'unknown key';

            switch ($keyName) {
                case 'email':
                    $responseMessage = "Email already exists.";
                    break;
                default:
                    $responseMessage = "Duplicate entry for key '$keyName'.";
                    break;
            }
        } else {
            $responseMessage = $e->getMessage();
        }
        handleError($responseMessage);
    }
    return 'Email has been updated';
}
function update_password($user_id)
{
    $editUser_newPass = getPostData('user_password');
    $editUser_conf = getPostData('user_confPass');

    if ($editUser_conf != $editUser_newPass){
        handleError('Password do not match');
    }

    $hashed_password = password_hash($editUser_newPass, PASSWORD_DEFAULT);
    try {
        $sql = "UPDATE tbl_accounts
                SET 
                    password = ?
                WHERE 
                    user_id = ?";

        mysqlQuery($sql,'si', [$hashed_password,$user_id]);

    }catch (Exception $e) {

        $responseMessage = $e->getMessage();
        handleError($responseMessage);
    }
    return 'Password has been updated';
}



function updAdvisory($user_id = null)
{
    try {
        if ($user_id !== null) {
            mysqlQuery("DELETE FROM tbl_advisoryhandle WHERE adv_id = ?", 'i', [$user_id]);
        }

        $assignedadvListProg = $_POST['assignedProg'];
        $assignedadvsql = "INSERT INTO tbl_advisoryhandle (program_id, adv_id) VALUES (?,  ?)";
        $types = 'ii';
        $params = [$assignedadvListProg,  $user_id];
        mysqlQuery($assignedadvsql, $types, $params);

    } catch (Exception $e) {
        handleError($e->getMessage());
    }
}


function upd_stud_tbl($user_id)
{
    $editstud_shc_id = getPostData('school_id');
    $editStud_adviser = getPostData('stud_adviser');

    $editStud_ojtCenter =getPostData('stud_OJT_center', 'N/A');
    $edit_otjContact = getPostData('stud_ojtContact', 'N/A') ;

    $editStud_program = getPostData('stud_Program') ;
    $editStud_yr_sec = getPostData('stud_Section');
    $params = [];
    $requiredFields = [
        'Student ID' => $editstud_shc_id,
        'Student adviser' => $editStud_adviser,
        'Student program' => $editStud_program,
        'Student year and section' => $editStud_yr_sec,
        'OJT center' => $editStud_ojtCenter,
        'OJT Contact' => $edit_otjContact,
        'User ID' => $user_id,
    ];
    foreach ($requiredFields as $field => $value) {
        if (empty($value) ) {
            handleError("Field $field is required.");
            exit();
        }else{
            $params []= $value;
        }

    }

    $upd_stud_info = 'UPDATE tbl_students set enrolled_stud_id = ?, 
                        adv_id = ?, program_id= ?, year_sec_Id = ?,
                        ojt_center = ?, ojt_contact = ? 
                    where user_id = ?';
    mysqlQuery($upd_stud_info,'iiiissi', $params);

}