function get_dashBoardnotes (){
    $.ajax({
        url: '../ajax.php?action=getDashboardNotes',
        method: 'GET',
        dataType: 'html',
        success: function(response) {

            $('#AdviserNotes').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

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
            <span class=" text-light-inverse text-md/normal">${rowData['ojt_center']}</span>
        </td>
        <td class="p-3 text-start">
            <span class="d text-light-inverse text-md/normal">${rowData['ojt_location']}</span>
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
            <a href="ViewStudentWeeklyReport.php?checkStudent=${rowData['user_id']}">
                <div class="relative hover:cursor-pointer h-auto mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent">
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>
        </td>`
        })

    }else {
        weeklyJournalTbl = `<tr><td colspan="9">No Active / Assigned students found for this adviser.</td></tr>`
    }

    $('#AdvisoryWeeklyReportList').html(weeklyJournalTbl)
}
