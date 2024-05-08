let commentBody = document.getElementById('comment_body');

function scrollToBottom() {
    commentBody.scrollTop = commentBody.scrollHeight;
}
document.getElementById('chatBox').addEventListener('submit', function (e){
    e.preventDefault();
    formData = new FormData(e.target);

    $.ajax({
        url: '../ajax.php?action=giveComment',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (parseInt(response) === 1 || parseInt(response) === 2) {
                getComments(formData.get('file_id'));
                scrollToBottom();
            }else {
                console.log(response);
            }
            e.target.reset();
        },
    });
});


function viewImage(srcPath){
    let path = 'comments_img/'+ srcPath;
    $('#viewImage').attr('src', path);
}
function getComments(file_id){
    $.ajax({
        url: '../ajax.php?action=getCommentst&file_id=' + file_id,
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            if (response){
                $('#comment_body').html(response);
                $('#chatBox input[name="file_id"]').val(file_id);
                scrollToBottom();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

function updateWeeklyReportStat(weeklyReport_id){
    $.ajax({
        url: '../ajax.php?action=updateWeeklyreportStat&file_id=' + weeklyReport_id,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data){
                $('#week').text(data.weeklyFileReport);
                $('#WeeklyReportForm select[name="report_Stat"]').val(data.upload_status);
                $('#WeeklyReportForm input[name="file_id"]').val(data.file_id);

            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}