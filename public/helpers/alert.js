/**
 * Global Helper Alert SICICI
 * Theme: Blue & White (Modern Soft Palette)
 */

function realoadBrowser() {
    window.location.reload();
}

function confirmAlert(message, callback) {
    Swal.fire({
        title: '<span style="font-size: 20px; font-weight: 600; color: #566a7f;">Konfirmasi</span>',
        text: message,
        showCancelButton: true,
        showConfirmButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Lanjutkan',
        reverseButtons: true,
        confirmButtonColor: '#696cff',
        cancelButtonColor: '#ebeef0', // Soft Grey-Blue
        customClass: {
            cancelButton: 'text-dark border-0',
            popup: 'rounded-4 shadow-lg border-0'
        }
    }).then((result) => {
        if (result.isConfirmed && typeof callback === "function") {
            callback();
        }
    });
}

function successAlert(message) {
    return Swal.fire({
        title: '<span style="font-weight: 600; color: #566a7f;">Berhasil!</span>',
        text: message,
        icon: 'success',
        iconColor: '#71dd37', // Soft Green
        showConfirmButton: false,
        timer: 1500,
        customClass: {
            popup: 'rounded-4 shadow-lg border-0'
        }
    });
}

function errorAlert(message = 'Terjadi kesalahan!') {
    return Swal.fire({
        title: '<span style="font-weight: 600; color: #566a7f;">Error</span>',
        text: message,
        icon: 'error',
        iconColor: '#ff3e1d', // Soft Red
        showConfirmButton: true,
        confirmButtonColor: '#696cff',
        customClass: {
            popup: 'rounded-4 shadow-lg border-0'
        }
    });
}

function warningAlert(message) {
    Swal.fire({
        title: '<span style="font-weight: 600; color: #566a7f;">Peringatan!</span>',
        text: message,
        icon: 'warning',
        iconColor: '#ffab00', // Soft Orange/Yellow
        showConfirmButton: true,
        confirmButtonText: 'Ok',
        confirmButtonColor: '#696cff',
        customClass: {
            popup: 'rounded-4 shadow-lg border-0'
        }
    });
}

function emailOrPasswordWrong() {
    return Swal.fire({
        title: '<span style="font-weight: 600; color: #566a7f;">Gagal Login</span>',
        text: 'Username atau password anda salah!',
        icon: 'warning',
        iconColor: '#ffab00',
        confirmButtonColor: '#0026ff',
        customClass: {
            popup: 'rounded-4 shadow-lg border-0'
        }
    });
}

const loadingAllert = (title = 'Mohon Tunggu', text = 'Sedang memproses data...') => {
    return Swal.fire({
        title: `<span style="color: #566a7f;">${title}</span>`,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        customClass: {
            popup: 'rounded-4 shadow-lg border-0'
        },
        didOpen: () => {
            Swal.showLoading();
            const loader = Swal.getPopup().querySelector('.swal2-loader');
            if (loader) {
                loader.style.borderTopColor = '#0026ff';
            }
        }
    });
};
