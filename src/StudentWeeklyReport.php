<?php
include '../vendor/autoload.php';
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);




include "../DatabaseConn/databaseConn.php";
include "../PhpMailer_producer.php";
include '../functions.php';
session_start();


if (!isset($_SESSION['log_user_type']) || ($_SESSION['log_user_type'] !== 'admin' && $_SESSION['log_user_type'] !== 'adviser')) {
    header("Location: index.php");
    exit();
}







function countFileComments($file_id){
    include "../DatabaseConn/databaseConn.php";

    $sql = "SELECT COUNT(*) AS comment_count FROM tbl_revision WHERE file_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $file_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    $comment_count = $row['comment_count'];

    return $comment_count;
}



if (!isset($_GET['checkStudent'])) {
    header("Location: dashboard.php");
    exit();
}
$secret_key = 'TheSecretKey#02';
$student_user_id = decrypt_data($_GET['checkStudent'], $secret_key);


if (!$student_user_id){
    header("Location: dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head data-theme="light">
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
    <link rel="icon" type="image/x-icon" href="assets/cvsulogo-removebg-preview.png">

    <title>Student Weekly Reports</title>
</head>
<body  class="min-h-screen bg-slate-200">
<main class="max-w-6xl mx-auto grid place-items-center text-gray-700 overflow-auto" id="mainContent">
    <div class="w-full max-w-full ">
        <div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-5">
            <div class="relative flex flex-col min-w-0 break-words border border-dashed bg-clip-border rounded-2xl border-stone-200 bg-light/30">
                <!-- card header -->
                <div class="px-9 pt-5 flex justify-between
                 items-stretch flex-wrap min-h-[75px] pb-0 bg-transparent">
                    <a href="dashboard.php" class="btn btn-sm btn-outline font-bold text-slate-700"><i class="fa-solid fa-circle-left"></i> Dashboard</a>

                    <form class="flex w-[40%] justify-between hidden sm:inline-flex">
                        <div class="w-full">
                            <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
            focus:outline-none focus:shadow-outline" id="weeklyReportSearch" type="text" placeholder="Search"
                                   onkeyup="handleSearch('weeklyReportSearch','stud_weekly_report')">
                        </div>
                    </form>
                </div>
                <div class="px-9 flex justify-end w-full">
                </div>
                <div class="block py-8 pt-6 px-9">
                    <div id="table_card" class="overflow-y-auto overflow-x-hidden h-[70vh] scroll-smooth">
                        <table class="w-full my-0  border-neutral-200 " id="stud_weekly_report" >
                            <thead class="align-bottom  z-20">
                            <tr class="font-semibold text-[0.95rem] sticky top-0  z-20 text-secondary-dark bg-slate-200 rounded">
                                <th class="p-3  ">#</th>
                                <th class="p-3  ">Week</th>
                                <th class="p-3 ">Remark</th>
                                <th class="p-3 ">Read Status</th>
                                <th class="p-3 ">Action</th>
                            </tr>
                            </thead>
                            <tbody class=" text-center ">

                            <?php
                            $week = 1;
                            $sql = "SELECT *  FROM weeklyReport WHERE stud_user_id = ? ORDER BY upload_date asc";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $student_user_id); // Assuming $stud_user_id contains the user ID
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {

                                $status = $row['upload_status'];

                                switch ($status) {
                                    case 'pending':
                                        $formattedStatus = 'Pending';
                                        $status_color = 'text-warning';
                                        break;
                                    case 'revision':
                                        $formattedStatus = 'With Revision';
                                        $status_color = 'text-info';

                                        break;
                                    case 'approved':
                                        $formattedStatus = 'Approved';
                                        $status_color = 'text-success';
                                        break;
                                    default:
                                        $formattedStatus = 'Unknown';
                                        break;
                                }



                                echo ' <tr class="border-b border-dashed last:border-b-0">

                                <td class="p-3 pr-0 ">
                                    <span class=" text-light-inverse text-md/normal">' . $week . '</span>
                                </td>
                                <td class="p-3 pr-0 ">
                                    <span class=" text-light-inverse text-md/normal">' . $row['week'] . '</span>
                                </td>

                                <td class="p-3 pr-0 ">
                                    <span class="'.$status_color.'   text-light-inverse text-md/normal">' . $formattedStatus . '</span>
                                </td>
                       
                                   <td class="p-3 pr-0 ">
                                    <span class=" text-light-inverse text-md/normal">' . $row['readStatus'] . '</span>
                                </td>
                                <td class="p-3 pr-0  ">
                                    <div  class="tooltip tooltip-bottom"  data-tip="View">
                          <a href="StudentWeeklyReportViewer.php?checkStudent='.urlencode(encrypt_data($student_user_id, $secret_key)).'&weeklyJournal='. urlencode(encrypt_data($row['file_id'],$secret_key)) .'" class="text-light-inverse text-md/normal mb-1 hover:cursor-pointer  transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                                    </div>
                       
                            </tr>';
                                $week++;

                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<dialog id="remarkForm" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[60vw] sm:w-[20rem] h-[50vh] lg:h-[20rem]  flex flex-col text-slate-700 overflow-auto">
        <div class="card-title sticky">
            <form method="dialog">
                <button class=" btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('remarkForm')">✕</button>
            </form>
            <h3 class="font-bold text-lg text-center text-black  top-0  p-5" id="week">Week 1</h3>
        </div>
        <div class="p-3">
            <form id="WeeklyReportForm" method="post" action="StudentWeeklyReport.php" class="flex flex-col justify-center gap-12 items-center">
                <label class="form-control w-full max-w-xs">
                    <div class="label">
                        <span class="label-text">Update remark</span>
                    </div>
                    <select id="report_Stat" name="report_Stat" class="select select-bordered">
                        <option selected value="pending">Pending</option>
                        <option value="revision">With revision</option>
                        <option value="approved">Approve</option>
                    </select>
                </label>
                <input type="hidden" value="<?php echo $student_user_id?>" name="stud_id">
                <input type="hidden" name="file_id" value="">
                <div>
                    <input type="submit" name="Update_remark" value="Update" class="btn-accent btn btn-md">
                </div>
            </form>
        </div>
    </div>
</dialog>
<dialog id="comments" class="modal bg-black  bg-opacity-40">
    <div class="card bg-slate-50 w-[100vw] sm:w-[50rem] h-[100vh] lg:h-[37rem]  flex flex-col text-slate-700 overflow-auto">
        <div class="card-title sticky">
            <form method="dialog">
                <button class=" btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModalForm('comments')">✕</button>
            </form>
            <h3 class="font-bold text-lg text-center text-black  top-0  p-5">Report Comments</h3>
        </div>
        <div class="overflow-auto card-body" id="comment_body">
            <div class="grid place-items-center">
                <div class="flex justify-end items-end">
                    <p class="py-4 px-2 border bg-slate-200 rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lor
                        em ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea
                        ex facilis magni modi, molestias perferendis placeat quam quasi, qui
                        ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="assets/prof.jpg" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-1 w-full justify-end">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                </div>
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-[10px] text-slate-400 text-center">2:30pm</p>
                <p class="text-[10px] text-slate-400 text-center">4/16/2024</p>
            </div>
            <div class="grid place-items-center">
                <div class="flex justify-end items-end">
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="assets/prof.jpg" />
                        </div>
                    </div>
                    <p class="py-4 px-2 border bg-slate-200 rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lor
                        em ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea
                        ex facilis magni modi, molestias perferendis placeat quam quasi, qui
                        ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>

                </div>
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-[10px] text-slate-400 text-center">2:30pm</p>
                <p class="text-[10px] text-slate-400 text-center">4/16/2024</p>
            </div>
            <div class="grid place-items-center">
                <div class="flex justify-end items-end">
                    <p class="py-4 px-2 border bg-slate-200 rounded-lg min-w-8 text-sm text-slate-700 text-justify" id="ref_id">Lor
                        em ipsum dolor sit amet, consectetur adipisicing elit. Consectetur ea
                        ex facilis magni modi, molestias perferendis placeat quam quasi, qui
                        ratione recusandae sapiente totam. Aut consectetur dolore ex quod temporibus!</p>
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="assets/prof.jpg" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1">
                    <img onclick="openModalForm('img_modal')" class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                    <img onclick="openModalForm('img_modal')"  class=" hover:cursor-pointer min-h-[3rem] max-h-[5rem] object-contain" src="comments_img/Capture.PNG" alt="report comment">
                </div>
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-[10px] text-slate-400 text-center">2:30pm</p>
                <p class="text-[10px] text-slate-400 text-center">4/16/2024</p>
            </div>



        </div>
        <div class="m-3 shadow-2xl rounded bg-gray-300 bg-opacity-70 ">
            <form enctype="multipart/form-data" id="chatBox" class="flex sm:flex-row flex-col justify-evenly items-center p-2 flex-wrap sm:gap-2 gap-0">
                <textarea name="revision_comment" class=" sm:w-auto w-full flex-grow textarea  max-h-24 textarea-bordered  bg-slate-100" placeholder="Type here"></textarea>
                <input type="hidden" name="file_id" value="">
                <div class=" flex  justify-center sm:justify-evenly sm:w-auto w-full flex-grow items-center p-2 sm:p-0">
                    <label class="form-control max-w-xs">
                        <div class="label">
                            <span class="label-text text-slate-700">Image attachment</span>
                        </div>
                        <input name="final_report_file[]" accept="image/png, image/jpeg" multiple  type="file"
                               class="block  text-sm text-black file:mr-4
                    file:py-2 file:px-4 file:rounded-full file:border-0
                    file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                    </label>
                    <button class="btn btn-accent">Send <i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>
</dialog>
<dialog id="img_modal" class="modal bg-black  bg-opacity-40">
    <div class="card  w-[100vw] sm:w-[55rem] h-[100vh]
          flex flex-col text-slate-700 overflow-auto">
        <div class="card-title sticky">
            <form method="dialog">
                <button class=" btn btn-sm btn-circle absolute right-2 top-2" onclick="closeModalForm('img_modal')">✕</button>
            </form>
        </div>
        <div class="card-body flex justify-center items-center max-h-[100vh] overflow-auto">
            <img id="viewImage" src="" class="h-full sm:h-[75vh] object-scale-down">
        </div>
    </div>
</dialog>


<script src='js/ViewStudentWeeklyReport.js'></script>
<script src="js/Datatables.js"></script>
<script src="js/buttons_modal.js"></script>
</body>


