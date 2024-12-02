function viewImage(srcPath){
    let path = 'comments_img/'+ srcPath;
    $('#viewImage').attr('src', path);
}

function displaySelectedcommentAttachment(){
    let files = $('#commentAttachment')[0].files; // Access the 'files' property


    let attachments = ``;
    if (files.length === 0){
        $('#imgAttachment').html(`<img class="w-5" src="assets/clip-svgrepo-com.svg" alt="file upload icon" width="512" height="512">`)
    }else{
        for (let i = 0; i < files.length; i++) {
            attachments += `<img class="w-3" src="assets/${files[i].name}" alt="file upload icon" width="512" height="512">`

        }
        $('#imgAttachment').html(attachments)
    }



}
function commentBodyScrollBottom() {
    let commentBody = document.getElementById('comment_body');
    commentBody.scrollTop = commentBody.scrollHeight;
}
async function giveComment(file_id) {
    let message = $('#revision_comment').val();
    let files = $('#commentAttachment')[0].files;

    // Create FormData object
    let formData = new FormData();
    formData.append('file_id', file_id);
    formData.append('message', message);

    // Helper function to convert image file to JPEG
    function convertToJpeg(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const img = new Image();
                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Set canvas size to image size
                    canvas.width = img.width;
                    canvas.height = img.height;

                    // Set the canvas background color to white (or any color you want)
                    ctx.fillStyle = 'white'; // You can change this to any color you prefer
                    ctx.fillRect(0, 0, canvas.width, canvas.height); // Fill the background

                    // Draw the image to canvas (transparent parts will be white)
                    ctx.drawImage(img, 0, 0);

                    // Convert canvas to JPEG blob
                    canvas.toBlob(function (blob) {
                        // Resolve with the JPEG blob
                        resolve(blob);
                    }, 'image/jpeg');
                };

                img.onerror = reject; // In case of an error while loading the image
                img.src = e.target.result; // Load the image data
            };

            reader.onerror = reject; // In case of an error while reading the file
            reader.readAsDataURL(file); // Read the image file as a data URL
        });
    }

    // Loop over files and convert them to JPEG before appending
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const jpegBlob = await convertToJpeg(file); // Convert image to JPEG blob
        const jpegFile = new File([jpegBlob], file.name.replace(/\.[^/.]+$/, ".jpg"), { type: 'image/jpeg' });
        formData.append('attachment[]', jpegFile); // Append the JPEG file
    }

    try {
        const response = await $.ajax({
            url: '../ajax.php?action=giveComment',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json'
        });

        if (response.response === 1) {
            $('#commentAttachment').val(''); // Clear the file input
            displaySelectedcommentAttachment(); // Refresh or clear displayed files
            $('#revision_comment').val(''); // Clear the message input
            getComments(file_id);
            commentBodyScrollBottom();
        }

    } catch (error) {
        console.error('Error updating read status:', error);
        throw error;
    }
}




async function getComments(file_id) {
    commentBodyScrollBottom()
    const { data: userData } = await user_info();
    const { data: commentsData } = await $.ajax({
        url: `../ajax.php?action=getCommentst&file_id=${file_id}`,
        method: 'GET',
        dataType: 'json'
    });
    let displayComments = commentsData.length === 0 ? '' : commentsData.map(commentData => {
        const isUserComment = userData.user_id === commentData.user_id;
        const profileImage = commentData.profile_img_file !== 'N/A' ? commentData.profile_img_file : 'prof.jpg';
        const attachments = commentData.attachment.length > 0 ?
            `<div class="flex flex-wrap gap-1 w-full justify-${isUserComment ? 'end' : 'start'} mb-2">
                ${commentData.attachment.map(attachment => `
                    <img src="comments_img/${attachment.attach_img_file_name}" 
                        onclick="openModalForm('img_modal');viewImage('${attachment.attach_img_file_name}')"
                        class="hover:cursor-pointer min-h-[3rem] max-h-[5rem] h-[5rem] object-contain" alt="attachment">
                `).join('')}
            </div>` : '';

        return `
            <div class="grid place-items-center">
                <div class="flex ${isUserComment ? 'justify-end items-end' : 'justify-start items-start'} w-full mb-2">
                    ${isUserComment ? `
                    <div>
                        <p class="py-4 px-2 bg-slate-100 border rounded-lg min-w-8 text-sm text-slate-700 text-end ${commentData.comment === '' ||
        commentData.comment === null ? 'hidden' : ''}" id="ref_id">${commentData.comment === null? '' : commentData.comment}</p>
                    </div>
                    <div class="flex flex-col justify-center items-center">
                        <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="userProfile/${profileImage}" />
                            </div>
                        </div>
                        <span class="text-xs">${isUserComment ? 'You' : commentData.first_name}</span>
                    </div>
                    ` : `
                    <div class="flex flex-col justify-center items-center">
                        <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="userProfile/${profileImage}" />
                            </div>
                        </div>
                        <span class="text-xs">${isUserComment ? 'You' : commentData.first_name}</span>
                    </div>
                    <div>
                        <p class="py-4 px-2 bg-slate-100 border rounded-lg min-w-8 text-sm text-slate-700 text-start" id="ref_id">${commentData.comment}</p>  
                    </div>`}

                    
                </div>
                ${attachments}
            </div>
            <hr>
            <div class="w-full grid place-items-center">
                <p class="text-[10px] text-slate-400 text-center">${formatDateTime(commentData.comment_date)}</p>
            </div>
        `;
    }).join('');

    $('#comment_body').html(displayComments);
}
