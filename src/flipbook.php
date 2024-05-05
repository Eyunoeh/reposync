<?php
include_once '../DatabaseConn/databaseConn.php';
include '../encryptionFunction.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js"crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/all.css">
    <script type="text/javascript" src="turnjs4/extras/jquery.min.1.7.js"></script>
    <script type="text/javascript" src="turnjs4/extras/modernizr.2.5.3.min.js"></script>
    <title>Document</title>
</head>
<body class="bg-slate-200 ">

<div class="flipbook-viewport overflow-auto ">
    <div class="container grid place-items-center ">
        <div class="flipbook">
            <div style="background-image:url(NarrativeReportsPDF/Capture.PNG)"></div>
            <?php
            $encrypt_key = 'TheSecretKey#02';
            if (isset($_GET['view'])){
                $decrypted_narrative_id = decrypt_data($_GET['view'], $encrypt_key);

                if (!$decrypted_narrative_id){
                    header("Location: index.php");
                }
                $sql = "SELECT * FROM narrativereports WHERE narrative_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $decrypted_narrative_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    $path = 'NarrativeReports_Images/' . str_replace(' ','',
                            $row['first_name']. "_" ). str_replace(' ','',$row['last_name']). "_" .
                        $row['program'] . "_" . $row['section']. "_" .$row['stud_school_id'];

                    $files = scandir($path);
                    $files_with_page = [];
                    $files_without_page = [];
                    foreach ($files as $file) {
                        if ($file != "." && $file != "..") {
                            if (preg_match('/page-\d+\.png$/', $file)) {
                                $files_with_page[] = $file;
                            } else {
                                $files_without_page[] = $file;
                            }
                        }
                    }
                    natsort($files_with_page);
                    natsort($files_without_page);
                    $sorted_files = array_merge($files_without_page, $files_with_page);
                    foreach ($sorted_files as $file) {

                        echo '<div style="background-image:url('.$path.'/'.$file.')"></div>';
                    }

                }
            }else{
                header("Location: index.php");
            }
            $conn->close();
            ?>
            <div style="background-image:url()"></div>
            <div style="background-image:url(NarrativeReportsPDF/backcover.PNG)"></div>

        </div>
    </div>
</div>
<script type="text/javascript">

    function loadApp() {
      $('.flipbook').turn({
            width:1000,
            height:600,
            elevation: 50,
            gradients: true,
            autoCenter: true

        });
    }
    yepnope({
        test : Modernizr.csstransforms,
        yep: ['turnjs4/lib/turn.js'],
        nope: ['turnjs4/lib/turn.html4.min.js'],
        both: ['css/basicFlip.css'],
        complete: loadApp
    });
</script>
</body>