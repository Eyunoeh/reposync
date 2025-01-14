function deleteAnnoucement(id, modal_id) {
    $.ajax({
        url: `../ajax.php?action=deleteAnnouncement&data_id=${encodeURIComponent(id)}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response && parseInt(response) === 1) {
                typeof get_dashBoardnotes === 'function' && get_dashBoardnotes();
                typeof getActivitiesAndSched === 'function' && getActivitiesAndSched();
                typeof closeModalForm === 'function' && closeModalForm(modal_id);
            }
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}


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
                <p class=" text-xl text-black font-sans">No notes posted</p>
            `);
            $('#AdviserNotes').empty();
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
