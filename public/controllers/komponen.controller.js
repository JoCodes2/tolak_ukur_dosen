import KomponenService from "../services/komponen.service.js";

$(document).ready(function () {
    const komponen = new KomponenService();

    // Tunggu semua dropdown (lookup map) selesai dulu, baru render card
    async function init() {
        await Promise.all([
            komponen.loadProdiDropdown(),
            komponen.loadMatakuliahDropdown(),
            komponen.loadPeriodeDropdown(),
        ]);
        komponen.getAllData();
    }

    init();

    // Event Filter
    $('#filter_id_prodi, #filter_id_mk').on('change', function () {
        komponen.getAllData();
    });

    $('#btnResetFilter').on('click', function () {
        $('#filter_id_prodi, #filter_id_mk').val('').trigger('change');
    });

    $('#btnTambahKomponen').on('click', function () {
        $('#formSimpanKomponen')[0].reset();
        $('#komponen_id').val('');

        $('#formSimpanKomponen .form-control, #formSimpanKomponen .form-select').removeClass('is-valid is-invalid');
        $('.error-msg').text('');

        $('#modalInputKomponen').modal('show');
    });

    function validation() {
        $('#formSimpanKomponen').validate({
            rules: {
                id_prodi: {
                    required: true
                },
                id_mk: {
                    required: true
                },
                id_periode: {
                    required: true
                },
                nama_komponen: {
                    required: true
                },
            },
            messages: {
                id_prodi: {
                    required: "Program Studi wajib dipilih"
                },
                id_mk: {
                    required: "Matakuliah wajib dipilih"
                },
                id_periode: {
                    required: "Periode wajib dipilih"
                },
                nama_komponen: {
                    required: "Nama komponen wajib diisi"
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
        return $('#komponen_id').val() ? true : false;
    }

    $('#btnProsesKomponen').on('click', function (e) {
        e.preventDefault();
        if ($('#formSimpanKomponen').valid()) {
            komponen.upsertData($('#formSimpanKomponen')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnEditKomponen', function () {
        const id = $(this).data('id');
        komponen.getDataById(id);
    });

    $(document).on('click', '.btnHapusKomponen', function () {
        const id = $(this).data('id');
        komponen.deleteData(id);
    });

    $('#modalInputKomponen').on('hidden.bs.modal', function () {
        $('#formSimpanKomponen')[0].reset();
        $('#formSimpanKomponen .form-control, #formSimpanKomponen .form-select').removeClass('is-invalid is-valid');
        $('.error-msg').text('');
    });
});
