<?php
// Example JSON data
$jsonData = '[{"name":"John", "age":30, "city":"New York"},
              {"name":"Anna", "age":25, "city":"London"},
              {"name":"Mike", "age":32, "city":"Chicago"}]';

$data = json_decode($jsonData, true);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data.xls");
header("Pragma: no-cache");
header("Expires: 0");

$flag = false;
foreach($data as $row) {
    if(!$flag) {
        // Display column names as first row
        echo implode("\t", array_keys($row)) . "\n";
        $flag = true;
    }
    echo implode("\t", array_values($row)) . "\n";
}
exit;
?>

