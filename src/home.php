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
         sm:text-left text-slate-900">REPOSYNC</span>
            <span class="text-3xl text-center
        text-slate-900"><br>An Online Narrative Report Management
            System for Cavite State University</span>
        </h2>
    </article>
    <img class=" w-[40%] object-fit" src="assets/open-book-clipart-07.png" alt="Rockets dab">
</div>
