

const route = new URLSearchParams(window.location.search).get('route');


document.addEventListener('DOMContentLoaded', function (){
    let  endpoint;
    let tableHeadRow;
    if (route === 'NarrativeReports'){
        endpoint = 'getArchiveNarrative';

        tableHeadRow = `<th class="p-3 text-start ">School ID</th>
                    <th class="p-3 text-start min-w-10">Name</th>
                    <th class="p-3 text-start min-w-10">Program</th>
                    <th class="p-3 text-start min-w-10">OJT adviser</th>
                    <th class="p-3 text-start min-w-10">Batch</th>
                    <th class="p-3 text-end ">Action</th>`;

    }else if (route === 'Users'){
        endpoint = 'getArchiveUsers';
        tableHeadRow = `<th class="p-3 text-start min-w-10">School ID</th>
                    <th class="p-3 text-start min-w-10">Name</th>
                    <th class="p-3 text-start min-w-10">User type</th>
                    <th class="p-3 text-start min-w-10">Email</th>
                    <th class="p-3 text-center ">Action</th>`;

    }
    $('#archiveThRow').html(tableHeadRow);
    $.ajax({
        url: '../ajax.php?action=' + encodeURIComponent(endpoint),
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.response === 1){
                let tbRowData;
                let  data = response.data;
                if (route === 'NarrativeReports'){
                    let flipbookCodes = response.flipbookCode;

                    for (let i = 0; i < data.length ; i++){
                        let  schoolYear = data[i].sySubmitted.split(",");
                        let middleName = '';
                        if (data[i].middle_name !== 'N/A' ){
                            middleName = data[i].middle_name;
                        }

                        tbRowData += `<td class="p-3 text-start w-[10rem]">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${data[i].stud_school_id}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].first_name} ${middleName} ${data[i].last_name}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].program}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].OJT_adviser_Fname} ${data[i].OJT_adviser_Lname}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${schoolYear.join(" - ")}</span>
                        </td>
                        <td class="p-3  flex gap-2 justify-end">
                            <a href="flipbook.php?view=${flipbookCodes[i]}" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-regular fa-eye"></i></a>
                            <a href="" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>`;
                    }
                }else if (route === 'Users'){
                    for (let i = 0; i < data.length ; i++){
                        let middleName = '';
                        if (data[i].middle_name !== 'N/A' ){
                            middleName = data[i].middle_name;
                        }
                        tbRowData += ` <td class="p-3 text-start w-[10rem]">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${data[i].school_id}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].first_name} ${middleName} ${data[i].last_name}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].user_type}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${data[i].email}</span>
                        </td>
                        <td class="p-3  text-center">
                            <a href="" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent"><i class="fa-solid fa-circle-info"></i></a>
                        </td>`;
                    }
                }
                $('#ArchiveTbody').html(tbRowData);
            }else if (response.response === 0){
                $('#ArchiveTbody').html('<tr><td colspan="9">No Result</td></tr>');
            }else{
                console.log(response.message)
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });


})
