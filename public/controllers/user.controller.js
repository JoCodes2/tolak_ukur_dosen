import UserService from "../services/user.service.js";

$(document).ready(function () {
    const user = new UserService();

    user.getAllData();
    user.loadProdiDropdown();
    $('#btnTambahUser').on('click', function () {
        $('#formSimpanUser')[0].reset();
        $('#id_user').val('');
        $('#password_note').hide();
        $('#container_id_prodi').addClass('d-none');

        $('#formSimpanUser .form-control, #formSimpanUser .form-select').removeClass('is-valid is-invalid');
        $('.error-msg').text('');

        $('#modalInputUser').modal('show');
    });

    $('#role').on('change', function () {
        const role = $(this).val();
        if (role === 'prodi') {
            $('#container_id_prodi').removeClass('d-none');
        } else {
            $('#container_id_prodi').addClass('d-none');
            $('#id_prodi').val('').trigger('change');
        }
    });

    function validation() {
        $('#formSimpanUser').validate({
            rules: {
                nama: { required: true },
                email: { required: true, email: true },
                role: { required: true },
                id_prodi: {
                    required: function () {
                        return $('#role').val() === 'prodi';
                    }
                },
                password: {
                    required: function () {
                        return !$('#id_user').val();
                    },
                    minlength: 8
                },
                password_confirmation: {
                    required: function () {
                        return $('#password').val().length > 0;
                    },
                    equalTo: "#password"
                }
            },
            messages: {
                nama: "Nama lengkap wajib diisi",
                email: {
                    required: "Email wajib diisi",
                    email: "Format email tidak valid"
                },
                role: "Silakan pilih hak akses",
                id_prodi: "Program studi wajib dipilih untuk Kaprodi",
                password: {
                    required: "Password wajib diisi",
                    minlength: "Password minimal 8 karakter"
                },
                password_confirmation: {
                    required: "Konfirmasi password wajib diisi",
                    equalTo: "Konfirmasi password tidak cocok"
                }
            },
            errorElement: 'small',
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
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

    function checkingEdit() {
        return $('#id_user').val() ? true : false;
    }

    $('#btnProsesUser').on('click', function (e) {
        e.preventDefault();
        if ($('#formSimpanUser').valid()) {
            user.upsertData($('#formSimpanUser')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnEditUser', function () {
        const id = $(this).data('id');
        user.getDataById(id);
    });

    $(document).on('click', '.btnHapusUser', function () {
        const id = $(this).data('id');
        user.deleteData(id);
    });

    $('#modalInputUser').on('hidden.bs.modal', function () {
        $('#formSimpanUser')[0].reset();
        $('#id_prodi').val('').trigger('change');
        $('#formSimpanUser .form-control, #formSimpanUser .form-select').removeClass('is-invalid is-valid');
    });
});
