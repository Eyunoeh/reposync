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
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-2.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-3.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-5.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-6.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-7.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-8.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-9.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-10.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-11.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-12.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-13.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-14.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-15.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-16.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-17.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-18.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-19.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-20.png)"></div>
            <div style="background-image:url(NarrativeReports_Images/Sample_name_BSIT_4A/Sample_name_BSIT_4A_page-21.png)"></div>
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