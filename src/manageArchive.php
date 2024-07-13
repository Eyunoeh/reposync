<?php
?>

<div class="relative flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-2">
    <div class="relative flex flex-col min-w-0 break-words  h-full rounded-2xl border-stone-200 bg-light/30">
        <div class="px-9 pt-5 flex justify-center items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent ">
            <h1 class="text-2xl font-bold">Archived List</h1>
        </div>
        <div class="block py-8 pt-6 px-9">
            <div class="overflow-auto h-full">
                <table id="narrativeReportsTable" class="w-full my-0 border-neutral-200 text-sm">
                    <thead class="align-bottom z-20">
                    <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral">
                        <th class="p-3 text-start"></th>
                        <th class="p-3 text-end">Total</th>
                        <th class="p-3 text-end">View</th>

                    </tr>
                    </thead>
                    <tbody id="ArhivedTableBody" class="text-slate-600">
                    <tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">Narrative Reports</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">10</span>
                        </td>
                        <td class="p-3 text-end">
                            <a  target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr class="border-b border-dashed last:border-b-0">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">Users</span>
                        </td>
                        <td class="p-3 text-end">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">10</span>
                        </td>
                        <td class="p-3 text-end">
                            <a  target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
