let urlParams = new URLSearchParams(window.location.search);
//onload call
linkPages();
async function navigate(page) {
    return fetch(page + '.php', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Add a custom header
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('mainContent').innerHTML = html;

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
                    };
                    reader.readAsDataURL(file);
                }
            });

            let chatBoxElement = document.getElementById('chatBox');
            if (chatBoxElement) {
                chatBoxElement.addEventListener('submit', function(e) {
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



async function linkPages() {
    const user = await user_info(); // await used in an async function
    const urlParams = new URLSearchParams(window.location.search);
    const page = decodeURIComponent(urlParams.get('page')) || 'home';

    // Redirect to 'home' if no page parameter and user data is empty
    if (!urlParams.get('page') && user.data.length === 0) {
        return navigate('home');
    }

    // Handle navigation based on page
    switch (page) {
        case 'narratives':
            await navigate('narratives');
            return;
        case 'announcement':
            await navigate('announcement');
            return;
        default:
            break;
    }

    // If user is a student, handle student-specific navigation
    if (user.data['user_type'] === 'student' && user.data['status'] === 'active') {
        switch (page) {
            case 'weeklyJournal':
                await navigate('weeklyReports'); // Ensure navigate completes before other actions
                WeeklyReportForm_inp_lstner();
                get_WeeklyReports();
                break;
            case 'settings':
                await navigate('studentSettings'); // Ensure navigate completes before other actions
                getProfileInfo();
                break;
            default:
                return navigate('home'); // Fallback to 'home' for other cases
        }
    } else {
        navigate('home'); // Fallback for non-students
    }
}





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



async function getHomeActSched() {
    try {
        const response = await $.ajax({
            url: '../ajax.php?action=getDashboardActSched',
            method: 'GET',
            dataType: 'json'
        });

        let actAndschedList = '';
        const user = await user_info();
        const user_data = user.data;

        if (response.response === 1) {
            const actScheds = response.data;
            const actSchedsTargetViewer = actScheds.filter(actSched => {
                return (
                    actSched['SchedAct_targetViewer'] === 'All' ||
                    (user_data.user_type === 'student' && actSched['SchedAct_targetViewer'] === user_data.program_code)
                );
            });

            actSchedsTargetViewer.forEach(actSched => {
                actAndschedList += `
                    <div class="text-sm text-slate-700 sm:text-base flex transform max-w-[50rem] w-full transition duration-500 shadow rounded
                    hover:scale-105 hover:bg-slate-300 justify-start items-center cursor-pointer">
                        <div class="min-w-[12rem] p-2 sm:p-5 text-center flex flex-col justify-center text-sm">`;

                if (actSched['starting_date'] === actSched['end_date']) {
                    actAndschedList += `<h4 class="text-start">${actSched['starting_date']}</h4>`;
                } else {
                    actAndschedList += `
                        <h4 class="text-start">${actSched['starting_date']}</h4>
                        <h4 class="text-start">${actSched['end_date']}</h4>`;
                }

                actAndschedList += `
                        </div>
                        <div class="flex flex-col justify-center p-3">
                            <h1 class="font-semibold break-words">${actSched['title']}</h1>
                            <p class="text-justify text-sm pr-5 break-words">
                                ${actSched['description'].replace(/\r\n|\r|\n/g, '<br>')}
                            </p>
                        </div>
                    </div>`;
            });

            $('#actSched').html(actAndschedList);
        } else {
            $('#actSched').html(`
                <div class="flex transform w-[50rem] justify-center items-center">
                    <h1 class="font-semibold">No activity and schedule posted</h1>
                </div>`);
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}



async function getHomeNotes(){
    try {
        const response = await $.ajax({
            url: '../ajax.php?action=getDashboardNotes',
            method: 'GET',
            dataType: 'json'
        });

        let  user = await user_info();
        if (user.response === 1){
            let advNoteCard = '';
            if (response.response === 1){
                let adv_Notes = response.data;
                adv_Notes.forEach(note =>{


                    const notePosted = formatDateTime(note.announcementPosted)
                    advNoteCard += `<div class="shadow flex transition duration-500 transform scale-90 hover:scale-100 hover:bg-slate-300 cursor-pointer w-full">
    <div class="flex flex-col justify-center p-2 w-full">
        <h1 class="font-semibold">${note.title}</h1>
        <div class="max-h-[10rem] transition overflow-hidden hover:overflow-auto w-full">
            <p class="text-justify text-sm break-words w-full">${note.description}
            </p>
            <p class="text-[12px] text-slate-400 text-end">${notePosted}
        </div>
    </div>
</div>
`
                })
                $('#studNotes').html(advNoteCard);
            }
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }


}


document.getElementById('homeLink').addEventListener('click', async function(event) {
    event.preventDefault();
    navigate('home');
});

const narrativesLink = document.getElementById('narrativesLink')
narrativesLink?.addEventListener('click',  async function(event) {
    event.preventDefault();
    navigate('narratives');
});
let reportLink = document.getElementById('reportLink')
reportLink?.addEventListener('click', async function(event) {
    event.preventDefault();
    navigate('weeklyReports').then(()=>{

        get_WeeklyReports();
        WeeklyReportForm_inp_lstner()
    });

});

document.getElementById('announcement').addEventListener('click', async function(event) {
    event.preventDefault();
    navigate('announcement');
});

const accountSettings = document.getElementById('accountSettings');
accountSettings?.addEventListener('click', (e) => {
    e.preventDefault();
    navigate('studentSettings').then(()=> {
        getProfileInfo();
    });


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
document.getElementById('side-weeklyjournal')?.addEventListener('click', function(event) {
    event.preventDefault();
    navigate('weeklyReports').then(()=> {
        getUploadLogs();
        get_WeeklyReports();
    });

});
