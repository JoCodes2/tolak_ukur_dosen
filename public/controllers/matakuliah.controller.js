import MatakuliahService from "../services/matakuliah.service.js";

$(document).ready(function () {
    const matakuliah = new MatakuliahService();

    matakuliah.getAllData();

    $('#btnTambahMatakuliah').on('click', function () {
        $('#formSimpanMatakuliah')[0].reset();
        $('#matakuliah_id').val('');

        $('#formSimpanMatakuliah .form-control').removeClass('is-valid is-invalid');
        $('.error-msg').text('');

        $('#modalInputMatakuliah').modal('show');
    });

    function validation() {
        $('#formSimpanMatakuliah').validate({
            rules: {
                kode_mk: {
                    required: true
                },
                nama_mk: {
                    required: true
                },
                sks: {
                    required: true
                }
            },
            messages: {
                kode_mk: {
                    required: "Kode Matakuliah wajib diisi"
                },
                nama_mk: {
                    required: "Nama Matakuliah wajib diisi"
                },
                sks: {
                    required: "SKS wajib diisi"
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
        return $('#matakuliah_id').val() ? true : false;
    }

    $('#btnProsesMatakuliah').on('click', function (e) {
        e.preventDefault();
        if ($('#formSimpanMatakuliah').valid()) {
            matakuliah.upsertData($('#formSimpanMatakuliah')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnEditMatakuliah', function () {
    const id = $(this).data('id');
    matakuliah.getDataById(id);
    });

    $(document).on('click', '.btnHapusMatakuliah', function () {
        const id = $(this).data('id');
        matakuliah.deleteData(id);
    });

    $('#modalInputMatakuliah').on('hidden.bs.modal', function () {
        $('#formSimpanMatakuliah')[0].reset();
        $('#formSimpanMatakuliah .form-control').removeClass('is-invalid is-valid');
        $('.error-msg').text('');
    });
});
