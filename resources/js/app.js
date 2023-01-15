let modal = document.getElementById('myModal');
let btnOpen = document.getElementById('btnOpenModal');
let btnClose = document.getElementById('btnCloseModal');

btnOpen.onclick = function () {
    modal.style.display = "block";
}

btnClose.onclick = function () {
    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


