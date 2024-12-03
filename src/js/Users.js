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
async function updateUserInfo() {
    try {
        let response = await user_info();

        if (response.response === 1) {
            let data = response.data;
            let profPath;

            if (data.profile_img_file === 'N/A') {
                profPath = 'userProfile/prof.jpg';
            } else {
                profPath = 'userProfile/' + data.profile_img_file;
            }

            $('#side_tabName').html(`${data.first_name} ${data.last_name} - ${data.user_type.toUpperCase()}`);
            $('#navName').html(`${data.first_name} ${data.last_name}`);
            $("#profile_nav").attr("src", profPath);
        }
    } catch (error) {
        console.error("Error fetching user info:", error);
    }
}


