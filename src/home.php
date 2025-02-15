<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
?>

<img class="border-gray-200 shadow-lg w-full object-cover mx-auto" style="height: 280px" src="assets/HOME-4.jpg" alt="Narrative Report">

<div class="w-full h-3 bg-yellow-300 mb-8 rounded-b-lg"></div>

<section class="py-16">
    <div class="rounded-b-2xl max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Mission Card -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg">
                <a>
                    <img class="rounded-t-xl w-full h-auto" src="assets/1.jpg" alt="Mission Image" />
                </a>
                <div class="p-5">
                    <p class="mb-3 text-gray-700 text-left">
                        Cavite State University shall provide excellent, equitable, and relevant educational opportunities in the arts, sciences, and technology through quality instruction and responsive research and development activities. It shall produce professional, skilled, and morally upright individuals for global competitiveness.
                    </p>
                </div>
            </div>

            <!-- Vision Card -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg">
                <img class="rounded-t-lg w-full h-auto" src="assets/2.jpg" alt="Vision Image" />
                <div class="p-5">
                    <p class="mb-3 text-gray-700 text-left">
                        The premier university in historic Cavite globally recognized for excellence in character development, academics, research, innovation, and sustainable community engagement.
                    </p>
                </div>
            </div>

            <!-- Quality Policy Card -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg">
                <img class="rounded-t-lg w-full h-auto" src="assets/3.jpg" alt="Vision Image" />
                <div class="p-5">
                    <p class="mb-3 text-gray-700 text-left">
                    We Commit to the highest standards of education, value our stakeholders, Strive for continual improvement of our products and services, and Uphold the University's tenets of Truth, Excellence, and Service to produce globally competitive and morally upright individuals.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


<?php include 'footer.php'; ?>