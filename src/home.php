<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
?>

<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <img class="rounded-2xl  border-gray-200 shadow-lg oject-cover w-full h-30  rounded-s-lg" src="assets/4.jpg" alt="">
        <div class="mt-12 ml-6 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">

            <!-- Mission Card -->
            <div class="max-w-sm bg-white border border-gray-200 rounded-2xl shadow-lg ">
                <a>
                    <img class="rounded-t-xl" src="assets/1.jpg" alt="Mission Image" />
                </a>
                <div class="p-5">
                    <p class="mb-3  text-gray-700  text-justify">
                        Cavite State University shall provide excellent, equitable, and relevant educational opportunities in the arts, sciences, and technology through quality instruction and responsive research and development activities. It shall produce professional, skilled, and morally upright individuals for global competitiveness.
                    </p>
                </div>
            </div>

            <!-- Vision Card -->
            <div class="max-w-sm bg-white border border-gray-200 rounded-2xl shadow-lg ">
                <img class="rounded-t-lg" src="assets/2.jpg" alt="Vision Image" />
                <div class="p-5">
                    <p class="mb-3  text-gray-700  text-justify">
                        The premier university in historic Cavite globally recognized for excellence in character development, academics, research, innovation, and sustainable community engagement.
                    </p>
                </div>
            </div>

            <!-- Quality Policy Card -->
            <div class="max-w-sm bg-white border border-gray-200 rounded-2xl shadow-lg ">
                <img class="rounded-t-lg w-full" src="assets/3.jpg" alt="Vision Image" />
                <div class="p-5">
                    <p class="mb-3  text-gray-700  text-justify">
                    We Commit to the highest standards of education, value our stakeholders, Strive for continual improvement of our products and services, and Uphold the University’s tenets of Truth, Excellence, and Service to produce globally competitive and morally upright individuals.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

