
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

<div class="flex flex-wrap  text-gray-700  ">
    <div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-5">
        <div class="relative flex flex-col min-w-0 break-words border border-dashed bg-clip-border rounded-2xl border-stone-200 bg-light/30">
            <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap pb-0 bg-transparent border-slate-300">
                <form class="flex w-full justify-between">
                    <div class="w-[40%]">
                        <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                    </div>
                    <div class="w-[40%]">
                        <select class="w-full h-10 rounded bg-slate-50 font-semibold" name="stud_program" id="programFilter" onchange="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                            <option value="">Select Program</option>
                            <?php
                            $sql = "SELECT * FROM  program";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            while ($row = $res->fetch_assoc()){
                                echo '<option >'.$row['program_code'].'</option>
                                               ';

                            }?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="block py-8 pt-6 px-9  ">
                <div class="overflow-auto h-[65vh]">
                    <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200" >
                        <thead class="align-bottom z-10">
                        <tr class="font-semibold text-[0.95rem] sticky top-0 z-10 text-secondary-dark bg-slate-200 rounded">
                            <th class="p-3 text-start">#</th>
                            <th class="p-3 text-start">Name</th>
                            <th class="p-3 text-end">Program</th>
                            <th class="p-3 text-end">View Report</th>
                        </tr>

                        </thead>
                        <tbody id="narrativeReportsTableBody" class="text-center">
                        <?php
                        /*
                        $condition = '';
                        if (isset($_SESSION['log_user_type']) and $_SESSION['log_user_type'] == 'student'){
                            $user_id = $_SESSION['log_user_id'];

                            $getStud = "SELECT * FROM tbl_students where user_id = ?";
                            $getStudstmt = $conn ->prepare($getStud);
                            $getStudstmt->bind_param('i', $user_id);
                            $res = $getStudstmt->get_result();
                            $row = $res->fetch_assoc();
                            $program_id = $row[''];

                            $condition = 'and '.$row[''];
                        }
*/


                        $getHomeNarraive = "SELECT *
                    FROM narrativereports
                    WHERE file_status = 'OK'
                    ORDER BY upload_date DESC";

                        $getHomeNarrativeStmt = $conn->prepare($getHomeNarraive);
                        $getHomeNarrativeStmt->execute();
                        $result = $getHomeNarrativeStmt->get_result();
                        $number = 1;

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr class="border-b border-dashed last:border-b-0 p-3">
            <td class="p-3 text-start">
                <span class="font-semibold text-light-inverse text-md/normal">' . $number++ . '</span>
            </td>
            <td class="p-3 text-start">
                <span class="font-semibold text-light-inverse text-md/normal">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
            </td>
            <td class="p-3 text-end">
                <span class="font-semibold text-light-inverse text-md/normal">' . $row["program"] . '</span>
            </td>
            <td class="p-3 text-end ">
                <a href="flipbook.php?view=' . urlencode(encrypt_data($row['narrative_id'], $secret_key)) . '" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
            </td>
          </tr>';
                        }

                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>



