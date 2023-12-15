// sweetAlertHelper.js

function showAlert(icon, title, text, timer = 1500) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: timer
    });
}
