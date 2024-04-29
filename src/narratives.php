
<?php
session_start();
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}

?>

<div class="flex flex-wrap  text-gray-700  ">

        <div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-5">
            <div class="relative flex flex-col min-w-0 break-words border border-dashed bg-clip-border rounded-2xl border-stone-200 bg-light/30">
                <div class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent border-slate-300">
                    <form class="flex w-full justify-between">
                        <div class="w-[40%]">
                            <input class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline" id="searchNarrativeInput" type="text" placeholder="Search" onkeyup="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                        </div>
                        <div class="w-[40%]">
                            <select class="w-full h-10 rounded bg-slate-50 font-semibold" name="stud_program" id="programFilter" onchange="handleSearch('searchNarrativeInput', 'narrativeReportsTable')">
                                <option value="">Select Program</option>
                                <option value="BSIT">BSIT</option>
                                <option value="BSBM">BSBM</option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="block py-8 pt-6 px-9">
                    <div class="overflow-auto h-80">
                        <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200" >
                            <thead class="align-bottom z-20">
                            <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded">
                                <th class="p-3 text-start">#</th>
                                <th class="p-3 text-start">Name</th>
                                <th class="p-3 text-end">Program</th>
                                <th class="p-3 text-end">View Report</th>
                            </tr>
                            </thead>
                            <tbody id="narrativeReportsTableBody" class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





