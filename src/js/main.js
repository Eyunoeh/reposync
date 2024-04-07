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

function dropdownToggle() {
    var dropdown = document.getElementById("myDropdown");
    if (dropdown.classList.contains("hidden")) {
        dropdown.classList.remove("hidden");
    } else {
        dropdown.classList.add("hidden");
    }
}

document.addEventListener("click", function(event) {
    var dropdown = document.getElementById("myDropdown");
    var button = document.querySelector(".btn-success");

    if (!dropdown.contains(event.target) && event.target !== button) {
        dropdown.classList.add("hidden");
    }
}

);


var urlParams = new URLSearchParams(window.location.search);

function navigate(page) {
    fetch(page + '.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('mainContent').innerHTML = html;
            updateActiveLink(page);
        })
        .catch(error => console.error('Error fetching content:', error));
}

function updateActiveLink(page) {
    document.getElementById('narrativesLink').classList.remove('text-black', 'bg-gray-300', 'rounded');
    document.getElementById('announcementLink').classList.remove('text-black', 'bg-gray-300', 'rounded');
    document.getElementById('side-narrativesLink').classList.remove('bg-gray-200', 'text-black');
    document.getElementById('side-announcementLink').classList.remove('bg-gray-200', 'text-black');
    if (page === 'narratives') {
        document.getElementById('narrativesLink').classList.add('text-black', 'bg-gray-300', 'rounded');
        document.getElementById('side-narrativesLink').classList.add('bg-gray-200', 'text-black');
    } else if (page === 'announcement') {
        document.getElementById('announcementLink').classList.add('text-black', 'bg-gray-300', 'rounded');
        document.getElementById('side-announcementLink').classList.add('bg-gray-200', 'text-black');
    }
}

document.getElementById('narrativesLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('narratives');
});

document.getElementById('announcementLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('announcement');
});
document.getElementById('side-narrativesLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('narratives');
});

document.getElementById('side-announcementLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('announcement');
});
document.getElementById('homeLink').addEventListener('click', function(event) {
    event.preventDefault();
    navigate('home');
});

if (!urlParams.get('display_page')) {
    navigate('home');
} else if (urlParams.get('page') === 'narratives') {
    navigate('narratives');
} else if (urlParams.get('page') === 'announcement') {
    navigate('announcement');
}
