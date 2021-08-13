const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

function isCapsLockOn(e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    var shiftKey = e.shiftKey ? e.shiftKey : ((keyCode == 16) ? true : false);
    return (((keyCode >= 65 && keyCode <= 90) && !shiftKey) || ((keyCode >= 97 && keyCode <= 122) && shiftKey))
}

function showCapsLockMsg(e) {
    if (isCapsLockOn(e)) {
        Toast.fire({
            icon: 'warning',
            title: 'Caps Lock is on',
        })
    }
}

document.onkeypress = function(e) {
    e = e || window.event;
    showCapsLockMsg(e);
}