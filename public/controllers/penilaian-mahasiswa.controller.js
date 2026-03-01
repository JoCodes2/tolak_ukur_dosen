import PenilaianMahasiswaService from "../services/penilaian-mahasiswa.service.js";

$(document).ready(function () {
    const penilaian = new PenilaianMahasiswaService();

    penilaian.getDaftarMengajar();

    $('#btnRefresh').on('click', function () {
        const btn = $(this);
        const originalHtml = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

        penilaian.getDaftarMengajar().finally(() => {
            btn.prop('disabled', false).html(originalHtml);
        });
    });
    $(document).on('click', '.btnDetailPenilaian', function () {
        const id = $(this).data('id');

        loadingAllert('Menyiapkan lembar penilaian...', 'Harap tunggu sebentar');

        window.location.href = `${appUrl}/penilaian-mahasiswa/detail/${id}`;
    });
});
