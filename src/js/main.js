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

            getHomeActSched();
            getHomeNotes();
            let profileImgInput = document.getElementById('profileImg');
            profileImgInput?.addEventListener('change', function(event) {

                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('selectedProfile').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });


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
    }
}


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

document.getElementById('homeLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('home');
});

const narrativesLink = document.getElementById('narrativesLink')
narrativesLink?.addEventListener('click', function(event) {
    event.preventDefault();
    navigate('narratives');
});
let reportLink = document.getElementById('reportLink')
reportLink?.addEventListener('click', function(event) {
    event.preventDefault();
    navigate('weeklyReports');
    getUploadLogs();
    get_WeeklyReports();
});

document.getElementById('announcement').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('announcement');
});

const accountSettings = document.getElementById('accountSettings');
accountSettings?.addEventListener('click', (e) => {
    e.preventDefault();
    navigate('studentSettings');
    getProfileInfo();
    console.log(123123);

});


const side_narrativesLink = document.getElementById('side-narrativesLink')
side_narrativesLink?.addEventListener('click', function(event) {
    event.preventDefault();
    navigate('narratives');
});
document.getElementById('side-announcement').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('announcement');
});
