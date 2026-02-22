import MahasiswaService from "../services/mahasiswa.service.js";

$(document).ready(function () {
    const mahasiswa = new MahasiswaService();

    mahasiswa.getAllData();

    $('#btnTambahMahasiswa').on('click', function () {
        $('#formSimpanMahasiswa')[0].reset();
        $('#mahasiswa_id').val('');

        $('#formSimpanMahasiswa .form-control').removeClass('is-valid is-invalid');
        $('.error-msg').text('');

        $('#modalInputMahasiswa').modal('show');
    });

    function validation() {
        $('#formSimpanMahasiswa').validate({
            rules: {
                nim: {
                    required: true
                },
                nama: {
                    required: true
                },
                angkatan: {
                    required: true
                }
            },
            messages: {
                nim: {
                    required: "NIM wajib diisi"
                },
                nama: {
                    required: "Nama wajib diisi"
                },
                angkatan: {
                    required: "Angkatan wajib diisi"
                },
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

    function checkingEdit() {
        return $('#mahasiswa_id').val() ? true : false;
    }

    $('#btnProsesMahasiswa').on('click', function (e) {
        e.preventDefault();
        if ($('#formSimpanMahasiswa').valid()) {
            mahasiswa.upsertData($('#formSimpanMahasiswa')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnEditMahasiswa', function () {
    const id = $(this).data('id');
    mahasiswa.getDataById(id);
    });

    $(document).on('click', '.btnHapusMahasiswa', function () {
        const id = $(this).data('id');
        mahasiswa.deleteData(id);
    });

    $('#modalInputMahasiswa').on('hidden.bs.modal', function () {
        $('#formSimpanMahasiswa')[0].reset();
        $('#formSimpanMahasiswa .form-control').removeClass('is-invalid is-valid');
        $('.error-msg').text('');
    });
});
