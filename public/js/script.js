let menu = document.getElementById('menu'); // If it's an ID
let header = document.getElementById('header'); // Ensure header exists too

if (menu) {
    menu.onclick = function () {
        menu.classList.toggle('fa-times');
        if (header) {
            header.classList.toggle('active');
        }
    };
}

window.onscroll = function () {
    if (menu) menu.classList.remove('fa-times');
    if (header) header.classList.remove('active');
};
