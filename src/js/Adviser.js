
async function get_dashBoardnotes() {
    try {
        const response = await $.ajax({
            url: '../ajax.php?action=getDashboardNotes',
            method: 'GET',
            dataType: 'json'
        });

        let AdviserNotes = '';


        if (response.response !== 1) {
            $('#advNotePageLoader').html(`
                <h1 class="font-semibold text-xl text-black font-sans">No notes posted</h1>
            `);
        }else {

            let notes = response.data;


            notes.forEach(note => {
                AdviserNotes += `
                <div onclick="removeTrashButton(); getNotes(${note['announcement_id']});openModalForm('Notes');" 
                     class="transform w-full md:w-[18rem] transition duration-500 shadow rounded hover:scale-110 hover:bg-slate-300 
                            justify-center items-center cursor-pointer p-3 h-[10rem]">
                    <div class="h-[8rem] overflow-hidden hover:overflow-auto">
                        <h1 class="font-semibold">${note['title']}</h1>
                        <p class="text-start text-sm break-words">${note['description']}</p>
                        <p class="text-[12px] text-slate-400 text-end">${note['announcementPosted']}</p>
                    </div>
                </div>`;
            });
            $('#advNotePageLoader').empty();

            $('#AdviserNotes').html(AdviserNotes);
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}
function getNotes(note_id){
    $.ajax({
        url: '../ajax.php?action=announcementJson&data_id=' + encodeURIComponent(note_id),
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){
                let status = data.status;
                let status_class = '';

                $('#status_Box').append('<p class="" id="NoteStat"></p>');
                document.getElementById('NoteStat').className = '';
                $('#status_Box').find('#NoteStat').html(status);
                $('#status_Box').find('#declineReason').remove();
                switch (status) {
                    case 'Declined':
                        $('#status_Box').append('<p class="text-slate-700  text-xs pl-2" id="declineReason">' +
                            '<strong>Reason:</strong> ' + data.reason +
                            '</p>');
                        status_class = 'text-error';
                        break;
                    case 'Pending':
                        status_class = 'text-warning';
                        break;
                    case 'Active':
                        status_class = 'text-success';
                        break;
                    default:
                        status_class = ''; // Default class if none of the cases match
                }

                document.getElementById('NoteStat').classList.add('font-semibold', 'text-sm', 'pl-2', status_class);


                $('#NotesForm input[name="noteTitle"]').val(data.title);
                $('#NotesForm textarea[name="message"]').val(data.description);
                $('#NotesForm input[name="announcementID"]').val(data.announcement_id);
                $('#NotesForm input[name="actionType"]').val('edit');
                $('#NoteTitle').append('<div id="trashAnnouncementBtn" class="trash tooltip tooltip-bottom tooltip-error text-sm" data-tip="Delete note">' +
                    '<a  onclick="deleteAnnoucement(this.getAttribute(\'data-id\'),\'Notes\')" data-id="' + data.announcement_id + '" class="btn-sm btn btn-circle btn-ghost hover:cursor-pointer text-error"><i class="fa-solid fa-trash"></i></a>' +
                    '</div>');
            }
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
        weeklyJournalTbl = `<tr><td colspan="9">No Result</td></tr>`
    }


    $('#AdvisoryWeeklyReportList').html(weeklyJournalTbl);


    $('#tableadvLoader').empty();
}
function convertToFlibookLister(){
    let uploadstat = document.getElementById('UploadStat');
    uploadstat.addEventListener("change", function (){
        if (uploadstat.value === 'Declined'){
            $('#declineUploadReason').append('<label class="form-control w-full ">\n' +
                '                            <div class="label">\n' +
                '                                <span class="label-text text-slate-700 font-bold">Remarks</span>\n' +
                '                            </div>\n' +
                '                            <input type="text" required  name="remark" class="input input-error w-full" placeholder="Type here">' +
                '                        </label>')
        }
        else{
            $('#declineUploadReason').empty();
        }

        if (uploadstat.value === 'Convert'){
            $('#update_btn').text('Convert')
        }else {
            $('#update_btn').text('Save')
        }
    })


}