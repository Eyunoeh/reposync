<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
?>

<div id="hero" class="max-w-7xl py-16 mx-auto px-4 sm:px-6 lg:px-8">
    
    <article class="">
        <h2 class=" ">
            <span class="text-4xl text-center sm:text-5xl
         sm:text-left  font-extrabold">Insight</span>

        </h2>
        <h2 class="ml-8 max-w-lg text-3xl text-start font-sans
        text-slate-900 "> An online on-the-job training narrative report
            management  system for Cavite  State University - Carmona Campus </h2>
    </article>
</div>
<hr class="border shadow-lg max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<section class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-gray-900">Mission, Vision, and Core Values</h2>
        <div class="mt-12 ml-8 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>

                </div>
                <div class="mt-5">
                    <h3 class="text-lg font-medium text-gray-900">Mission</h3>
                    <p class="mt-2 text-base text-gray-500">Cavite State University
                        shall provide excellent, equitable and relevant educational
                        opportunities in the arts, sciences and technology through quality instruction and responsive research and development activities. It shall produce professional, skilled and morally upright individuals for global competitiveness.</p>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                </div>
                <div class="mt-5">
                    <h3 class="text-lg font-medium text-gray-900">Vision</h3>
                    <p class="mt-2 text-base text-gray-500">The premier university in historic Cavite globally recognized for excellence in character development,
                        academics, research, innovation and sustainable community engagement.</p>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>

                </div>

                <div class="mt-5">
                    <h3 class="text-lg font-medium text-gray-900">Core Values</h3>
                    <div  class="mt-2 text-base text-gray-500">
                        <li>Truth</li>
                        <li>Excellence</li>
                        <li>Service</li>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>
