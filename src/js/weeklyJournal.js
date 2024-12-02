

async function updateWeeklyJournal(file_id){
    let status = $('#report_Stat').val()
    const response = await $.ajax({
        url: '../ajax.php?action=updateJournalRemark&file_id=' + encodeURIComponent(file_id) + '&journalStatus=' + status,
        method: 'GET',
        dataType: 'json'
    });
    if (response.response === 1){
        setTimeout(() => {
            closeModalForm('loader');
        }, 800);

    }

}

async function updateReadStat(file_id) {
    const  response = await $.ajax({
        url: '../ajax.php?action=updateReadStat&file_id=' + file_id ,
        method: 'GET',
        dataType: 'json'
    });

}




