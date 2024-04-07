<?php
$action = $_GET['action'];
if ($action== 'signUp'){
    extract($_POST);
    echo $first_name;
}
