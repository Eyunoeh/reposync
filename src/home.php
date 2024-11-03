<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
?>

<div id="hero" class="flex flex-col-reverse justify-center sm:flex-row p-6 items-center gap-8">
    <article class="w-1/2">
        <h2 class="max-w-md ">
            <span class="text-4xl text-center sm:text-5xl
         sm:text-left text-slate-900">Insight</span>
            <span class="text-3xl text-center
        text-slate-900"><br>An online on-the-job training narrative report
            management  system for Cavite  State University - Carmona Campus </span>
        </h2>
    </article>
    <img class=" w-[40%] object-fit" src="assets/insightlogo2.png" alt="logo">
</div>
