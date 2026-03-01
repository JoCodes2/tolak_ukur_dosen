import KontrakPerkuliahanService from "../services/kontrak-perkuliahan.service.js";

$(document).ready(function () {
    const service = new KontrakPerkuliahanService();
    const getIdFromUrl = () => window.location.pathname.split('/').pop();

    if ($('#kontrakTable').length > 0) {
        service.getAllKontrak();
    }

    if (window.location.pathname.includes('kontrak-perkuliahan/detail')) {
        service.getBobotPenilaian(getIdFromUrl());
    }

    $(document).on('click', '.btn-detail', function () {
        window.location.href = `${appUrl}/kontrak-perkuliahan/detail/${$(this).data('id')}`;
    });

    $(document).on('click', '#btnSyncKomponen', function () {
        service.syncKomponen(getIdFromUrl());
    });

    $(document).on('input', '.input-bobot', function () {
        let total = 0;
        $('.input-bobot').each(function () {
            let val = parseFloat($(this).val()) || 0;
            if (val > 100) $(this).val(100);
            if (val < 0) $(this).val(0);
            total += parseFloat($(this).val()) || 0;
        });
        $('#totalBobot').text(total);
        service.updateStatusBobot(total);
    });

    $(document).on('click', '#btnSimpanBobot', function () {
        service.storeBobot(getIdFromUrl());
    });

    $(document).on('click', '#btnRefresh', function () {
        service.getAllKontrak();
    });
});
