import AktivitasService from "../services/aktivitas-perkuliahan.service.js";


$(document).ready(function () {
    const aktivitas = new AktivitasService();

    aktivitas.getAllData();
    aktivitas.loadDropdownData();

    $('#btnTambahAktivitas').on('click', function () {
        $('#formSimpanAktivitas')[0].reset();
        $('#id_aktivitas').val('');

        $('.form-select').val('').trigger('change');
        $('#formSimpanAktivitas .form-control, #formSimpanAktivitas .form-select').removeClass('is-valid is-invalid');

        $('#modalInputAktivitas').modal('show');
    });

    function validation() {
        $('#formSimpanAktivitas').validate({
            rules: {
                id_periode: { required: true },
                id_prodi: { required: true },
                id_kelas: { required: true }
            },
            messages: {
                id_periode: { required: "Periode akademik wajib dipilih" },
                id_prodi: { required: "Program studi wajib dipilih" },
                id_kelas: { required: "Kelas wajib dipilih" }
            },
            errorElement: 'small',
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
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
        return $('#id_aktivitas').val() ? true : false;
    }

    $('#btnProsesAktivitas').on('click', function (e) {
        e.preventDefault();
        if ($('#formSimpanAktivitas').valid()) {
            aktivitas.upsertData($('#formSimpanAktivitas')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnDetailAktivitas', function () {
        const id = $(this).data('id');
        aktivitas.goToDetail(id);
    });

    $(document).on('click', '.btnEditAktivitas', function () {
        const id = $(this).data('id');
        aktivitas.getDataById(id);
    });

    $(document).on('click', '.btnHapusAktivitas', function () {
        const id = $(this).data('id');
        aktivitas.deleteData(id);
    });

    $('#modalInputAktivitas').on('hidden.bs.modal', function () {
        $('#formSimpanAktivitas')[0].reset();
        $('.form-select').val('').trigger('change');
        $('#formSimpanAktivitas .form-control, #formSimpanAktivitas .form-select').removeClass('is-invalid is-valid');
    });
});
