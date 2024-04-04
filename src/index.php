
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js"crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body  class="min-h-screen bg-slate-50 ">

    <?php include('home_nav.php') ?>

<main  class="max-w-6xl mx-auto">

    <?php
    $pages = array('narratives', 'announcement');
    $page = 'land_page';
    if (isset($_GET['page'])){
        if (in_array($_GET['page'],$pages)){
            $page = $_GET['page'];
        }
    } ?>
    <?php include $page.'.php' ?>
</main>
</body>
</html>