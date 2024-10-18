function user_info(user_id = null) {
    return new Promise((resolve, reject) => {
        let url = '../ajax.php?action=get_User_info';
        if (user_id !== null) {
            url += '&data_id=' + encodeURIComponent(user_id);
        }

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}