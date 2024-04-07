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



