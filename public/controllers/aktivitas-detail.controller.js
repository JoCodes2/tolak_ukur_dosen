import AktivitasDetailService from "../services/aktivitas-detail.service.js";


$(document).ready(function () {
    const detail = new AktivitasDetailService;
    detail.initHeader(aktivitasId);
    detail.getPeserta(aktivitasId);
    detail.getPengajar(aktivitasId);


    $('#btnTambahMahasiswaKolektif').on('click', function () {
        detail.loadMahasiswaKolektif(aktivitasId);
    });

    $(document).on('change', '#checkAllMhs', function () {
        $('.check-mhs').prop('checked', $(this).prop('checked'));
    });

    $('#btnSimpanKolektif').on('click', function () {
        const selectedIds = $('.check-mhs:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            return warningAlert("Pilih minimal satu mahasiswa!");
        }

        detail.storePesertaKolektif(aktivitasId, selectedIds);
    });

    $(document).on('click', '.btnHapusPeserta', function () {
        const id = $(this).data('id');
        detail.deletePeserta(id, aktivitasId);
    });


    $('#btnTambahPenugasan').on('click', function () {
        $('#formPenugasan')[0].reset();
        $('.select2-modal').val('').trigger('change');

        detail.loadDropdownPenugasan();

        $('#modalPenugasan').modal('show');
    });

    function validationPenugasan() {
        $('#formPenugasan').validate({
            rules: {
                id_mk: { required: true },
                id_dosen: { required: true }
            },
            messages: {
                id_mk: { required: "Mata kuliah wajib dipilih" },
                id_dosen: { required: "Dosen pengajar wajib dipilih" }
            },
            errorElement: 'small',
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    }

    validationPenugasan();

    $('#btnSimpanPenugasan').on('click', function (e) {
        e.preventDefault();
        if ($('#formPenugasan').valid()) {
            detail.storePenugasan(aktivitasId, $('#formPenugasan')[0]);
        }
    });

    $(document).on('click', '.btnHapusPengajar', function () {
        const id = $(this).data('id');
        detail.deletePengajar(id, aktivitasId);
    });
});
