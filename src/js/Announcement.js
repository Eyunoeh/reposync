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
