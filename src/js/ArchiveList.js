

async function renderArchiveUsers(){
    const get_archiveUsers = await $.ajax({
        url: '../ajax.php?action=getArchiveUsers',
        method: 'GET',
        dataType: 'json'
    });
    let tableHeadRow;
    let tbRowData;


    let data = get_archiveUsers.data

    tableHeadRow = `
                    <th onclick="sortTable(0, 'ArhiveTable')" class="p-3 text-start min-w-10 cursor-pointer">Name<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(1, 'ArhiveTable')" class="p-3 text-start min-w-10 cursor-pointer">User type<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(2, 'ArhiveTable')" class="p-3 text-start min-w-10 cursor-pointer">Email<span class="sort-icon text-xs"></span></th>
                    <th  class="p-3 text-center ">Action</th>`;

    $('#archiveThRow').html(tableHeadRow);
    if (get_archiveUsers.response !== 1){
        $('#ArchiveTbody').html('<tr><td colspan="9">No Result</td></tr>');
    }else {
        console.log(data)

        let offset = (page_no - 1) * totalRecPerpage;
        total_page = Math.ceil( data.length/ totalRecPerpage);
        let paginatedList = data.slice(offset, offset + totalRecPerpage);
        paginatedList.forEach(user=>{
            let middleName = '';
            if (user.middle_name !== 'N/A') {
                middleName = user.middle_name;
            }

            tbRowData += ` <tr>

                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${user.first_name} ${middleName} ${user.last_name}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${user.user_type}</span>
                        </td>
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-md/normal">${user.email}</span>
                        </td>
                        <td class="p-3  text-center">
                            <a onclick="openModalForm('unarchiveModal'); $('#unarchiveLink').attr('data-archive_id', $(this).attr('data-archive_id'));"
                           data-archive_id="${user.acc_id}" 
                           class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out 
                           text-lg/normal text-secondary-inverse hover:text-accent">   <i class="fa-solid fa-pen-to-square"></i></a>
                       
                        </td>
                        </tr>`;
        })

        $('#noteText').html('Are you sure you want to recover this user account?')
        $('#ArchiveTbody').html(tbRowData);
    }
}
async function renderArchiveNarratives(){
    const get_archiveNarratives = await $.ajax({
        url: '../ajax.php?action=getPublishedNarrativeReport',
        method: 'GET',
        dataType: 'json'
    });
    let tableHeadRow;
    let tbRowData;

    let data = get_archiveNarratives.data

    tableHeadRow  = `<th onclick="sortTable(0, 'ArhiveTable')"  class="p-3 text-start cursor-pointer ">School ID<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(1, 'ArhiveTable')"   class="p-3 text-start cursor-pointer min-w-10">Name<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(2, 'ArhiveTable')"  class="p-3 text-start cursor-pointer min-w-10">Program<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(3, 'ArhiveTable')"  class="p-3 text-start cursor-pointer min-w-10">OJT adviser<span class="sort-icon text-xs"></span></th>
                    <th onclick="sortTable(4, 'ArhiveTable')"   class="p-3 text-start cursor-pointer min-w-10">Semester<span class="sort-icon text-xs"></th>
                    <th onclick="sortTable(5, 'ArhiveTable')"   class="p-3 text-start cursor-pointer min-w-10">Academic year<span class="sort-icon text-xs"></th>

                    <th class="p-3 text-end ">Action</th>`;

    $('#archiveThRow').html(tableHeadRow);
    const  adviserList  = await $.ajax({
        url: '../ajax.php?action=getAdvisers' ,
        method: 'GET',
        dataType: 'json'
    });

    let advisers = adviserList.data.reduce((acc, adviser) => {
        let { user_id, first_name, last_name } = adviser;
        if (!acc[user_id]) {
            acc[user_id] = { name: `${first_name} ${last_name}`, user_id: user_id };
        }
        return acc;
    }, {});




    let array_narrativeList = []


    Object.entries(data).forEach(([key, narrative]) => {
        if (narrative.file_status === 'Archived'){
            array_narrativeList.push(narrative)
        }
    });
    if (array_narrativeList.length === 0) {
        $('#ArchiveTbody').html('<tr><td colspan="9">No Result</td></tr>');


    }else {
        let offset = (page_no - 1) * totalRecPerpage;
        total_page = Math.ceil( array_narrativeList.length/ totalRecPerpage);
        let paginatedList = array_narrativeList.slice(offset, offset + totalRecPerpage);

        paginatedList.forEach(narrative =>{
            let years = narrative.ay_submitted.split(',');
            let startingAC = years[0].trim();
            let endingAC =  years[1].trim();
            let formattedSem = {
                First: '1st',
                Second: '2nd',
                Summer: 'Summer'
            };
            tbRowData += `<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">${narrative.enrolled_stud_id}</span>
                        </td>
                        <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${narrative.first_name} ${narrative.last_name}</span>
                        </td>
                         <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal  break-words">${narrative.program_code}</span>
                        </td>
                         <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal  break-words">${advisers[narrative.adv_id].name}</span>
                        </td>
                       
                        <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${formattedSem[narrative.sem_submitted]}</span>
                        </td>
                         <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${startingAC} - ${endingAC}</span>
                        </td>

                        <td class="p-3 text-end ">
                              <a onclick="openModalForm('unarchiveModal'); $('#unarchiveLink').attr('data-archive_id', $(this).attr('data-archive_id'));"
                           data-archive_id="${narrative.narrative_id}" 
                           class="hover:cursor-pointer mb-1 font-semibold transition-colors 
                           duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                           <i class="fa-solid fa-pen-to-square"></i></a>

                            <a href="flipbook.php?view=${narrative.narrative_id}" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>`;

        })
        $('#noteText').html('Are you sure you want to recover this student narrative report?')
        $('#ArchiveTbody').html(tbRowData);
    }

}



async function UnarchiveData(id) {
    const route = new URLSearchParams(window.location.search).get('route');

    let endpoint;

    if (route === 'Users') {
        endpoint = 'recoverUser';
    } else if (route === 'NarrativeReports') {
        endpoint = 'recoverNarrativeReport';
    } else {
        window.location.href = 'dashboard.php';
        return;
    }
    const unArchiveCall = await $.ajax({
        url: '../ajax.php?action=' + endpoint + '&archived_id=' + id,
        method: 'GET',
        dataType: 'json'
    });

    if (unArchiveCall.response === 1) {
        closeModalForm('unarchiveModal')
        Alert('notifBox', unArchiveCall.message, 'info');
        setTimeout(function (){
            window.location.href = 'manage_ArchiveList.php?route=' + route;

        },1000)
    } else {
        alert('Error: ' + unArchiveCall.message || 'Unknown error occurred.');
    }

}

async function archiveNarrative(narrative_id){
    const archiveCall = await $.ajax({
        url: '../ajax.php?action=archiveNarrative&narrative_id=' + narrative_id,
        method: 'GET',
        dataType: 'json'
    });

    if (archiveCall.response === 1){
        dashboard_student_NarrativeReports()
    }

    closeModalForm('archiveNarrativeModal')
    Alert('notifBox', archiveCall.message, 'info')

}



