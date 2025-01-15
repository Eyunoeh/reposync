

async function renderWeeklyJournaltbl(){
    const { response, data: weeklyJournalList } = await $.ajax({
        url: '../ajax.php?action=weeklyJournalList',
        method: 'GET',
        dataType: 'json'
    });

    let weeklyJournalTbl = '';
    if (response === 1){
        let offset = (page_no - 1) * totalRecPerpage;
        total_page = Math.ceil( weeklyJournalList.length / totalRecPerpage);


        let paginatedList = weeklyJournalList.slice(offset, offset + totalRecPerpage);

        paginatedList.forEach(rowData => {
            weeklyJournalTbl += `
<tr class="border-b border-dashed last:border-b-0 p-3 hover">
        <td class="p-3 text-start">
            <span class=" text-light-inverse text-md/normal">${rowData['enrolled_stud_id']}</span>
        </td>
        <td class="p-3 text-start">
            <span class=" text-light-inverse text-md/normal">${rowData['first_name']} ${rowData['last_name']}</span>
        </td>
        <td class="p-3 text-start w-[300px] break-words">
            <span class=" text-light-inverse text-md/normal">${rowData['ojt_center'] === null ? 'Not yet started': rowData['ojt_center']}</span>
        </td>

        <td class="p-3 text-center">
            <span class=" text-light-inverse text-md/normal">${rowData['lastActivity']}</span>
        </td>
        <td class="p-3 text-end relative">
        `
            if (rowData['unreadJournal']){
                weeklyJournalTbl += `<span class="absolute top-0 right-0 badge badge-sm badge-info">New</span>`
            }

            weeklyJournalTbl += `
            <a href="StudentWeeklyJournal.php?checkStudent=${rowData['user_id']}">
                <div class="relative hover:cursor-pointer h-auto mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>
        </td>`
        })

    }else {
        weeklyJournalTbl = `<tr><td colspan="9">No Result</td></tr>`
    }


    $('#AdvisoryWeeklyReportList').html(weeklyJournalTbl);


}
function convertToFlibookLister(){
    let uploadstat = document.getElementById('UploadStat');
    uploadstat.addEventListener("change", function (){
        if (uploadstat.value === 'Declined'){
            $('#declineUploadReason').html('<label class="form-control w-full ">' +
                '                            <div class="label">' +
                '                                <span class="label-text text-slate-700 font-semibold">Reason</span>' +
                '                            </div>' +
                '                            <textarea  required  name="reason" class="textarea textarea-warning" placeholder="Type here"></textarea>' +
                '                        </label>')
        }
        else{
            $('#declineUploadReason').empty();
        }

        if (uploadstat.value === 'Approved'){
            $('#update_btn').text('Convert')

            $('#upd_SubNarrativ_note').html('<p class="text-slate-700 text-sm" ><i class="fa-solid fa-circle-info"></i> This will notify the admin</p>')
        }else {
            $('#update_btn').text('Save')
            $('#upd_SubNarrativ_note').empty();
        }
    })
}


async function getStudSubmittedNarratives(){
    const { response, data: submittedNarrativesList } = await $.ajax({
        url: '../ajax.php?action=getSubmittedNarrativeReport',
        method: 'GET',
        dataType: 'json'
    });
    let subNarrativesTbl = ''
    if (submittedNarrativesList && Object.keys(submittedNarrativesList).length > 0) {
        Object.entries(submittedNarrativesList).forEach(([key, narratives]) => {


            let startingAC = narratives.ayStarting
            let endingAC =  narratives.ayEnding
            let formattedSem = {
                First: '1st',
                Second: '2nd',
                Midyear: 'Midyear'
            };

            let narrativeStatuses = {Pending: ['text-warning', 'Unread'],
                Declined: ['text-info','With Revision'],
                Approved: ['text-success', 'Approved']}


            subNarrativesTbl += `
            <tr class="border-b border-dashed last:border-b-0 p-3">
                <td class="p-3 text-start w-[10rem]">
                    <span class=" text-light-inverse text-md/normal break-words">${narratives.enrolled_stud_id}</span>
                </td>
                <td class="p-3 text-start">
                    <span class=" text-light-inverse text-md/normal">${narratives.first_name} ${narratives.last_name}</span>
                </td>
                <td class="p-3 text-start">
                    <span class=" text-light-inverse text-md/normal">${narrativeStatuses[narratives.file_status][1]}</span>
                </td>
                 <td class="p-3 text-start">
                    <span class=" text-light-inverse text-md/normal">${formattedSem[narratives.Semester]}, ${startingAC + ' - '+ endingAC}</span>
                </td>
                
                <td class="p-3 text-start">
                    <span class=" text-light-inverse text-md/normal">${formatDateTime(narratives.upload_date)}</span>
                </td> 
                <td class="p-3 text-start">
                    <span class=" text-light-inverse text-md/normal">${capitalizeFirstLetter(narratives.convertStatus)}</span>
                </td>
                <td class="p-3 text-end">`
            if (narrativeStatuses[narratives.file_status][1] === 'Approved') {
                subNarrativesTbl += `
                    <a href="flipbook.php?view=${narratives.narrative_id}" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2">
                        <i class="fa-regular fa-eye"></i>
                    </a>`
            }

            subNarrativesTbl += `
                    <a onclick="openModalForm('EditNarrativeReq'); upd_SubmittedNarrative(this.getAttribute('data-narrative'))" data-narrative="${key}" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>`;
        });
    }else {
        subNarrativesTbl =`
            <tr>
            <td colSpan="9">No Result</td>
        </tr>`
    }

    $('#narrativeReportsReqTableBody').html(subNarrativesTbl);

}


async function upd_SubmittedNarrative(key){
    const { response, data: submittedNarrativesList } = await $.ajax({
        url: '../ajax.php?action=getSubmittedNarrativeReport',
        method: 'GET',
        dataType: 'json'
    });
    let narrative = submittedNarrativesList[key];
    $('#dlLink').attr('href', 'NarrativeReportsPDF/' + narrative.narrative_file_name);
    $('#UpdSubNarrativeReport select[name="UploadStat"]').val(narrative.file_status);
    $('#UpdSubNarrativeReport input[name="narrative_id"]').val(key);
    if (narrative.file_status === 'Declined'){
        $('#declineUploadReason').html(`
    <label class="form-control w-full">
        <div class="label">
            <span class="label-text text-slate-700 font-semibold">Reason</span>
        </div>
        <textarea required name="reason" class="textarea textarea-warning" placeholder="Type here">${narrative.remarks}</textarea>
    </label>
`);

    }else {
        $('#declineUploadReason').empty();

    }





}