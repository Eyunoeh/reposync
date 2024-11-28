<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header("Location: 404.php");
    exit();
}

include '../DatabaseConn/databaseConn.php';


include '../functions.php';


session_start()
?>

<div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">

        <div class=" flex items-center sticky top-0 p-5 bg-white shadow rounded z-50 justify-center ">
            <div class="">
                <h1 class="font-bold text-2xl text-slate-700 font-sans">Narrative report list</h1>
            </div>
        </div>


        <div class=" overflow-auto h-full inline-block text-left">
            <div id="treeview" class="p-4 text-lg grid gap-5">
                <ul class="list-none">
                    <li>
                        <button class="tree-toggle"><i class="fa-solid fa-plus"></i> AY 2021-2022</button>
                        <ul class="ml-4 hidden">
                            <li>
                                <button class="tree-toggle"><i class="fa-solid fa-plus"></i> First Semester</button>
                                <ul class="ml-4 hidden">
                                    <li>📄 Nested Child 1</li>
                                    <li>📄 Nested Child 2</li>
                                </ul>
                            </li>
                            <li>
                                <button class="tree-toggle"><i class="fa-solid fa-plus"></i> Second Semester</button>
                                <ul class="ml-4 hidden">
                                    <li>📄 Nested Child 1</li>
                                    <li>📄 Nested Child 2</li>
                                </ul>
                            </li>
                            <li>
                                <button class="tree-toggle"><i class="fa-solid fa-plus"></i> Midyear Semester</button>
                                <ul class="ml-4 hidden">
                                    <li>📄 Nested Child 1</li>
                                    <li>📄 Nested Child 2</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <button class="tree-toggle"><i class="fa-solid fa-plus"></i> Parent 2</button>
                        <ul class="ml-4 hidden">
                            <li>📄 Child 3</li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</div>

