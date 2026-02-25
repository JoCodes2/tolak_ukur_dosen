import LoginService from "../services/login.service.js";

$(document).ready(function () {
    const loginService = new LoginService();

    // Reset validation errors on page load
    $('.error-msg').text('');
    $('.form-control').removeClass('is-invalid is-valid');

    function validation() {
        $('#formLogin').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Email wajib diisi",
                    email: "Format email tidak valid"
                },
                password: {
                    required: "Password wajib diisi"
                }
            },
            errorElement: 'small',
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                const errorId = '#error-' + element.attr('name');
                if ($(errorId).length) {
                    $(errorId).html(error);
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    }

    validation();

    $('#formLogin').on('submit', function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            const formData = new FormData(this);
            loginService.login(formData);
        }
    });

    // Handle logout if button exists (optional, usually in dashboard)
    $(document).on('click', '#btnLogout', function (e) {
        e.preventDefault();
        loginService.logout();
    });
});
