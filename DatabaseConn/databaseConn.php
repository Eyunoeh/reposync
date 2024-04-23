<?php

$host = "localhost";
$username = "root";
$database = "reposync_db";

$conn = new mysqli($host, $username, '', $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
