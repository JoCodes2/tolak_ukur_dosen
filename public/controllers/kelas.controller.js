import KelasService from "../services/kelas.service.js";

$(document).ready(function () {
    const kelas = new KelasService();

    kelas.getAllData();

    $('#btnTambahKelas').on('click', function () {
        $('#formSimpanKelas')[0].reset();
        $('#kelas_id').val('');

        $('#formSimpanKelas .form-control').removeClass('is-valid is-invalid');
        $('.error-msg').text('');

        $('#modalInputKelas').modal('show');
    });

    function validation() {
        $('#formSimpanKelas').validate({
            rules: {
                nama_kelas: {
                    required: true
                },
            },
            messages: {
                nama_kelas: {
                    required: "Nama kelas wajib diisi"
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
        return $('#kelas_id').val() ? true : false;
    }

    $('#btnProsesKelas').on('click', function (e) {
        e.preventDefault();
        if ($('#formSimpanKelas').valid()) {
            kelas.upsertData($('#formSimpanKelas')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnEditKelas', function () {
        const id = $(this).data('id');
        kelas.getDataById(id);
    });

    $(document).on('click', '.btnHapusKelas', function () {
        const id = $(this).data('id');
        kelas.deleteData(id);
    });

    $('#modalInputKelas').on('hidden.bs.modal', function () {
        $('#formSimpanKelas')[0].reset();
        $('#formSimpanKelas .form-control').removeClass('is-invalid is-valid');
        $('.error-msg').text('');
    });
});
