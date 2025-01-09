<?php

$host = "localhost";
$username = "root";
$database = "insight_db_v2";

$conn = new mysqli($host, $username, '', $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
