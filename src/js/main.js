let urlParams = new URLSearchParams(window.location.search);

function navigate(page) {
    fetch(page +'.php', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Add a custom header
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('mainContent').innerHTML = html ;

            updateActiveLink(page);
            get_WeeklyReports();
            getUploadLogs();
            getHomeActSched();
            getHomeNotes();

            let chatBoxElement = document.getElementById('chatBox');

            if (document.getElementById('chatBox')) {
                chatBoxElement.addEventListener('submit', function (e){
                    e.preventDefault();

                    let formData = new FormData(e.target);

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
                            } else {
                                console.log(response);
                            }
                            e.target.reset();
                        },
                    });
                });
            }

        })
        .catch(error => console.error('Error fetching content:', error));
}





function updateActiveLink(page) {
    const narrativesLink = document.getElementById('narrativesLink');
    const announcement = document.getElementById('announcement');
    const sideNarrativesLink = document.getElementById('side-narrativesLink');
    const sideAnnouncement = document.getElementById('side-announcement');
    const reportLink = document.getElementById('reportLink');
    if (narrativesLink && announcement && sideNarrativesLink && sideAnnouncement && reportLink) {
        narrativesLink.classList.remove('text-black', 'bg-gray-300', 'rounded');
        announcement.classList.remove('text-black', 'bg-gray-300', 'rounded');
        sideNarrativesLink.classList.remove('bg-gray-200', 'text-black');
        sideAnnouncement.classList.remove('bg-gray-200', 'text-black');
        reportLink.classList.remove('text-black', 'bg-gray-300', 'rounded');


        if (page === 'narratives') {
            narrativesLink.classList.add('text-black', 'bg-gray-300', 'rounded');
            sideNarrativesLink.classList.add('bg-gray-200', 'text-black');
        } else if (page === 'announcement') {
            announcement.classList.add('text-black', 'bg-gray-300', 'rounded');
            sideAnnouncement.classList.add('bg-gray-200', 'text-black');
        } else if (page === 'weeklyReports') {
            reportLink.classList.add('text-black', 'bg-gray-300', 'rounded');
        }
    } else {
        console.error('One or more elements not found.');
    }
}

document.getElementById('homeLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('home');
});

document.getElementById('narrativesLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('narratives');
});
document.getElementById('reportLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('weeklyReports');
});

document.getElementById('announcement').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('announcement');
});

document.getElementById('side-narrativesLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('narratives');
});
document.getElementById('side-announcement').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('announcement');
});

document.addEventListener('DOMContentLoaded', function() {
    var urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.get('page')) {
        navigate('home');
    } else {
        // Decode the page parameter to handle special characters
        var page = decodeURIComponent(urlParams.get('page'));
        if (page === 'narratives') {
            navigate('narratives');
        } else if (page === 'announcement') {
            navigate('announcement');
        }
        else {
            navigate('home');
        }
    }
});



function openNav() {
    document.getElementById("mySidenav").style.transform = "translateX(0)";
}
function closeNav() {
    document.getElementById("mySidenav").style.transform = "translateX(-100%)";
}
function toggleNav() {
    var sideNav = document.getElementById("mySidenav");

    if (sideNav.style.transform === "translateX(0%)") {
        closeNav();
    } else {
        openNav();
    }
}





function home_student_NarrativeReports() {
    $.ajax({
        url: '../ajax.php?action=get_narrativeReports&homeTable=request',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#narrativeReportsTableBody').html(response);
        },
        error: function(xhr, status, error) {

            console.error('Error fetching data:', error);
        }
    });
}

function get_WeeklyReports (){
    $.ajax({
        url: '../ajax.php?action=getWeeklyReports',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#Weeklyreports').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

function resubmitWeeklyReport(weeklyReport_id){
    document.querySelector('#resubmitReport input[name="file_id"]').value = weeklyReport_id;
}

function getUploadLogs(){
    $.ajax({
        url: '../ajax.php?action=getUploadLogs',
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#logsTable_body').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
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
function viewImage(srcPath){
    let path = 'comments_img/'+ srcPath;
    $('#viewImage').attr('src', path);
}
function scrollToBottom() {
    let commentBody = document.getElementById('comment_body');
    commentBody.scrollTop = commentBody.scrollHeight;
}


function getHomeActSched(){
    $.ajax({
        url: '../ajax.php?action=getHomeActSched' ,
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            if (response){
                $('#actSched').html(response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });

}
function getHomeNotes(){
    $.ajax({
        url: '../ajax.php?action=getHomeNotes' ,
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            if (response){
                $('#studNotes').html(response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });

}

