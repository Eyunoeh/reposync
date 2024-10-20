
<?php
session_start();
if (!isset($_SESSION['log_user_id'])){
    header("Location: 404.php");
}
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
include '../functions.php';
include '../DatabaseConn/databaseConn.php';
?>

<section class="w-full min-h-screen flex justify-center  mt-2">

    <div class="w-full max-w-7xl mx-auto p-5 rounded-lg shadow-lg bg-white min-h-[600px]">
        <div class="px-9 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <form class="flex w-full justify-between">
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                </div>
                <a class="btn  font-semibold btn-neutral
                    " id="newNarrative" >Upload Narrative Report</a>

            </form>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-full">
                <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200 text-sm">
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0  text-secondary-dark bg-slate-200 rounded text-neutral">
                        <th class="p-3 text-start">Code</th>
                        <th class="p-3 text-start">Program</th>

                        <th class="p-3 text-end">View</th>
                    </tr>
                    </thead>
                    <tbody id="narrativeReportsTableBody" class="text-slate-600">
                    <?php
                    $sql = "SELECT * FROM  program";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($row = $res->fetch_assoc()){
                        echo '<tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">'.$row['program_code'].'</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">'.$row['program_name'].'</span>
                        </td>
          
                        <td class="p-3 text-end">
                            <a href="dashboardViewnarrativeReports.php?program='.$row['program_code'].'" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
                        </td>
                    </tr>
                                               ';


                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>




