import ProdiService from "../services/prodi.service.js";

$(document).ready(function () {
    const prodi = new ProdiService();

    prodi.getAllData();

    $('#btnTambahProdi').on('click', function () {
        $('#formSimpanProdi')[0].reset();
        $('#prodi_id').val('');

        $('#formSimpanProdi .form-control').removeClass('is-valid is-invalid');
        $('.error-msg').text('');

        $('#modalInputProdi').modal('show');
    });

    function validation() {
        $('#formSimpanProdi').validate({
            rules: {
                kode_prodi: {
                    required: true,
                    minlength: 2
                },
                nama_prodi: {
                    required: true
                },
            },
            messages: {
                kode_prodi: {
                    required: "Kode prodi wajib diisi",
                    minlength: "Minimal 2 karakter"
                },
                nama_prodi: {
                    required: "Nama program studi wajib diisi"
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
        return $('#prodi_id').val() ? true : false;
    }

    $('#btnProsesProdi').on('click', function (e) {
        e.preventDefault();
        if ($('#formSimpanProdi').valid()) {
            prodi.upsertData($('#formSimpanProdi')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnEditProdi', function () {
        const id = $(this).data('id');
        prodi.getDataById(id);
    });

    $(document).on('click', '.btnHapusProdi', function () {
        const id = $(this).data('id');
        prodi.deleteData(id);
    });

    $('#modalInputProdi').on('hidden.bs.modal', function () {
        $('#formSimpanProdi')[0].reset();
        $('#formSimpanProdi .form-control').removeClass('is-invalid is-valid');
        $('.error-msg').text('');
    });
});
