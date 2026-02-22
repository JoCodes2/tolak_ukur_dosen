import PeriodeService from "../services/periode.service.js";

$(document).ready(function () {
    const periode = new PeriodeService();

    periode.getAllData();

    $('#btnTambahPeriode').on('click', function () {
        $('#formSimpanPeriode')[0].reset();
        $('#periode_id').val('');

        $('#formSimpanPeriode .form-control').removeClass('is-valid is-invalid');
        $('.error-msg').text('');

        $('#modalInputPeriode').modal('show');
    });

    function validation() {
        $('#formSimpanPeriode').validate({
            rules: {
                nama: {
                    required: true
                },
                semester: {
                    required: true
                },
                tahun_ajaran: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                nama: {
                    required: "Nama wajib diisi"
                },
                semester: {
                    required: "Semester wajib diisi"
                },
                tahun_ajaran: {
                    required: "Tahun Ajaran wajib diisi"
                },
                status: {
                    required: "Status wajib diisi"
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

    function checkingEdit() {
        return $('#periode_id').val() ? true : false;
    }

    $('#btnSimpanPeriode').on('click', function (e) {        e.preventDefault();
        if ($('#formSimpanPeriode').valid()) {
            periode.upsertData($('#formSimpanPeriode')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnEditPeriode', function () {
    const id = $(this).data('id');
    periode.getDataById(id);
    });

    $(document).on('click', '.btnHapusPeriode', function () {
        const id = $(this).data('id');
        periode.deleteData(id);
    });

    $('#modalInputPeriode').on('hidden.bs.modal', function () {
        $('#formSimpanPeriode')[0].reset();
        $('#formSimpanPeriode .form-control').removeClass('is-invalid is-valid');
        $('.error-msg').text('');
    });
});
