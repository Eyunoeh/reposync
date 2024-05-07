<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}
include '../DatabaseConn/databaseConn.php';
include '../functions.php';
$secret_key ='TheSecretKey#02';





session_start();
?>

<div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <form class="flex w-full justify-start">
                <div class="w-[40%]">
                    <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searcbox" type="text" placeholder="Search" onkeyup="handleSearch('searcbox', 'AdvisoryWeeklyReportTbl')">
                </div>
            </form>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-96">
                <table id="AdvisoryWeeklyReportTbl" class="w-full my-0 border-neutral-200 text-sm" >
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral" >
                        <th class="p-3 text-start ">School ID</th>
                        <th class="p-3 text-start ">Name</th>
                        <th class="p-3 text-end ">Last Activity</th>
                        <th class="p-3 text-end ">Check Reports</th>
                    </tr>
                    </thead>
                    <tbody id="AdvisoryWeeklyReportList" class="text-center text-slate-600">

                    <?php
                    function getLatestActivity($user_id){
                        include '../DatabaseConn/databaseConn.php';
                        $sql = "SELECT * FROM activity_logs WHERE file_id IN (
                SELECT file_id FROM weeklyReport WHERE stud_user_id = ?
            ) ORDER BY activity_date DESC LIMIT 1";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();

                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $latest_activity = $result->fetch_assoc();
                            return $latest_activity['activity_date'];
                        } else {
                            return null;
                        }
                    }

                    $adv_sch_user_id = $_SESSION['log_user_id'];

                    $adv_list_tbl = "SELECT 
                    u.school_id,
                    u.first_name,
                    u.user_id,
                    u.last_name
                FROM 
                    advisory_list a
                JOIN 
                    tbl_user_info u ON a.stud_sch_user_id = u.user_id
                WHERE 
                    a.adv_sch_user_id = ?
                ORDER BY 
                    (SELECT activity_date FROM activity_logs WHERE file_id IN (
                        SELECT file_id FROM weeklyReport WHERE stud_user_id = u.user_id
                    ) ORDER BY activity_date DESC LIMIT 1) DESC";



                    $stmt = $conn->prepare($adv_list_tbl);
    $stmt->bind_param("i", $adv_sch_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Loop through each row of the result set
        while ($row = $result->fetch_assoc()) {
            $latest_activity = getLatestActivity($row['user_id']); // Assuming $db_connection is your database connection object

            $formatted_date_time = $latest_activity ? date("M d, Y g:i A", strtotime($latest_activity)) : 'No Activity';

            // Output the information in a table row format
            echo '<tr class="border-b border-dashed last:border-b-0 p-3">
                    <td class="p-3 text-start">
                        <span class="font-semibold text-light-inverse text-md/normal">' . $row['school_id'] . '</span>
                    </td>
                    <td class="p-3 text-start">
                        <span class="font-semibold text-light-inverse text-md/normal">' . $row['first_name'] . ' ' . $row['last_name'] . '</span>
                    </td>
                    <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal">'.$formatted_date_time.'</span>
                        </td>
                    <td class="p-3 text-end">
                        <a href="ViewStudentWeeklyReport.php?checkStudent= '.urlencode(encrypt_data($row['user_id'], $secret_key)).'" class="hover:cursor-pointer
                        mb-1 font-semibold transition-colors duration-200
                        ease-in-out text-lg/normal text-secondary-inverse
                        hover:text-accent" target="_blank"><i class="fa-solid fa-arrow-right"></i></a>
                    </td>
                </tr>';
        }
    } else {
        // If no results found, display a message
        echo '<tr><td colspan="3">No students found for this adviser.</td></tr>';
    }

    // Close the prepared statement and release the result set
    $stmt->close();
?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
