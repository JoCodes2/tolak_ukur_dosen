import PenilaianMahasiswaDetailService from "../services/penilaian-mahasiswa-detail.service.js";

$(document).ready(function () {
    const penilaianDetail = new PenilaianMahasiswaDetailService();

    if (typeof idMengajarDetail !== 'undefined') {
        penilaianDetail.getDetailData();
    } else {
        errorAlert("ID Mengajar tidak ditemukan.");
    }

    $('#btnSimpanNilai').on('click', function (e) {
        e.preventDefault();

        let isInvalid = false;
        let isRowIncomplete = false;
        let anyInputFilled = false;

        $('#body-penilaian tr').each(function () {
            const row = $(this);
            const inputs = row.find('.input-nilai:not(:disabled)');

            if (inputs.length === 0) return;

            let filledInRow = 0;
            inputs.each(function () {
                const val = $(this).val();
                if (val !== "" && val !== null) {
                    filledInRow++;
                    anyInputFilled = true;
                }
            });

            if (filledInRow > 0 && filledInRow < inputs.length) {
                isRowIncomplete = true;
                inputs.each(function () {
                    if ($(this).val() === "") {
                        $(this).addClass('is-invalid');
                    }
                });
            }

            inputs.each(function () {
                const val = parseFloat($(this).val());
                if ($(this).val() !== "" && (isNaN(val) || val < 0 || val > 100)) {
                    isInvalid = true;
                    $(this).addClass('is-invalid');
                }
            });
        });

        if (!anyInputFilled) {
            return warningAlert("Belum ada nilai yang diinputkan.");
        }

        if (isInvalid) {
            return warningAlert("Terdapat nilai yang tidak valid (Rentang 0 - 100).");
        }

        if (isRowIncomplete) {
            return warningAlert("Jika mengisi nilai mahasiswa, seluruh komponen nilai mahasiswa tersebut wajib dilengkapi.");
        }

        penilaianDetail.saveDraft();
    });

    $('#btnFinalisasiNilai').on('click', function (e) {
        e.preventDefault();

        const inputs = $('.input-nilai:not(:disabled)');
        const hasZeroBobot = $('.input-nilai[data-bobot="0"]').length > 0;

        if (hasZeroBobot) {
            return errorAlert("Finalisasi gagal. Masih ada komponen dengan bobot 0%.");
        }

        let hasEmpty = false;
        inputs.each(function () {
            if ($(this).val() === "" || $(this).val() === null) {
                hasEmpty = true;
            }
        });

        if (hasEmpty) {
            confirmAlert(
                "Masih ada nilai yang kosong. Nilai kosong akan dianggap 0 setelah finalisasi. Lanjutkan?",
                () => {
                    penilaianDetail.finalisasi();
                }
            );
        } else {
            penilaianDetail.finalisasi();
        }
    });

    $(document).on('focus', '.input-nilai', function () {
        $(this).removeClass('is-invalid');
    });

    $(document).on('click', '#btn-penilaian-mahasiswa', function () {
        window.location.href = `${appUrl}/penilaian-mahasiswa/`;
    });
});
