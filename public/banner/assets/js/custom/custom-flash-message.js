const flashMessageDanger = document.getElementById('alertDanger');
const flashMessageSuccess = document.getElementById('alertSuccess');

if (flashMessageSuccess) {
    setTimeout(function () {
        flashMessageSuccess.style.display = 'none';
    }, 3000);
}

if (flashMessageDanger) {
    setTimeout(function () {
        flashMessageDanger.style.display = 'none';
    }, 3000);
}