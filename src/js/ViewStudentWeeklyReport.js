

function scrollToBottom() {
    let commentBody = document.getElementById('comment_body');
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







