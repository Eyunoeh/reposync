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
get_dashBoardnotes();