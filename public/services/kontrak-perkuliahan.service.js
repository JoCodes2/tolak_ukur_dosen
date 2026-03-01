class KontrakPerkuliahanService {
    constructor() {
        this.tableKontrak = $('#kontrakTable');
    }

    ajaxRequest(url, method, data = null) {
        const isFormData = data instanceof FormData;
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                method: method,
                data: data,
                processData: isFormData ? false : true,
                contentType: isFormData ? false : 'application/x-www-form-urlencoded',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => resolve(response),
                error: (xhr) => reject(xhr)
            });
        });
    }

    async getAllKontrak() {
        if (!$.fn.dataTable.isDataTable(this.tableKontrak)) {
            this.tableKontrak.DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    emptyTable: this.getEmptyTemplate(
                        'fa-file-circle-xmark',
                        'Belum Ada Jadwal Mengajar',
                        'Anda belum memiliki penugasan mata kuliah pada periode ini.'
                    )
                }
            });
        }
        const dt = this.tableKontrak.DataTable();
        dt.clear();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/kontrak-perkuliahan/data`, 'GET');
            (response.data ?? []).forEach((item, index) => {
                const aktivitas = item.aktivitas || {};

                dt.row.add([
                    index + 1,
                    aktivitas.periode?.nama ?? '-',
                    aktivitas.prodi?.nama_prodi ?? '-',
                    aktivitas.kelas?.nama_kelas ?? '-',
                    `<strong>${item.mata_kuliah?.nama_mk ?? '-'}</strong><br><small>${item.mata_kuliah?.kode_mk ?? '-'}</small>`,
                    `${item.mata_kuliah?.sks ?? 0} SKS`,
                    `<button type="button" class="btn btn-outline-primary btn-sm btn-detail" data-id="${item.id}">
                        <i class="fa fa-eye me-1"></i> Detail Komponen
                    </button>`
                ]);
            });
            dt.draw();
        } catch (e) {
            console.error(e);
        }
    }

    async syncKomponen(idMengajarDetail) {
        try {
            loadingAllert('memproses data...');
            let response = await this.ajaxRequest(`${appUrl}/sicici/kontrak-perkuliahan/sync/${idMengajarDetail}`, 'POST');
            console.log(response);

            Swal.close();
            successAlert("Sinkronisasi berhasil.");
            this.getBobotPenilaian(idMengajarDetail);
        } catch (e) {
            Swal.close();
            errorAlert("Gagal sinkronisasi komponen.");
        }
    }

    async getBobotPenilaian(idMengajarDetail) {
        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/kontrak-perkuliahan/komponen-tersedia/${idMengajarDetail}`, 'GET');
            const data = response.data;

            if (data && data.identitas) {
                const iden = data.identitas;
                const aktivitas = iden.aktivitas || {};

                $('#detail-nama-mk').text(iden.mata_kuliah?.nama_mk ?? '-');
                $('#detail-kode-mk').text(iden.mata_kuliah?.kode_mk ?? '-');
                $('#detail-sks').text(iden.mata_kuliah?.sks ?? 0);
                $('#detail-dosen').text(iden.dosen?.nama ?? '-');
                $('#detail-kelas').text(aktivitas.kelas?.nama_kelas ?? '-');
                $('#detail-periode').text(aktivitas.periode?.nama ?? '-');
                $('#detail-prodi').text(aktivitas.prodi?.nama_prodi ?? '-');

                let html = '';
                let total = 0;
                const listBobot = data.bobot || [];

                if (listBobot.length === 0) {
                    html = `<tr>
                        <td colspan="2">
                            ${this.getEmptyTemplate(
                        'fa-sync-alt',
                        'Belum Sinkronisasi',
                        'Daftar komponen penilaian belum ditarik. Silakan klik tombol <strong>Sinkronisasi</strong> untuk mengambil data dari Prodi.'
                    )}
                        </td>
                    </tr>`;
                    $('#btnSimpanBobot').hide();
                } else {
                    $('#btnSimpanBobot').show();
                    listBobot.forEach((item) => {
                        const nilaiBobot = item.bobot || 0;
                        total += parseFloat(nilaiBobot);
                        html += `
                        <tr>
                            <td class="align-middle fw-semibold">${item.komponen?.nama_komponen ?? '-'}</td>
                            <td style="width: 150px;">
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control input-bobot text-center"
                                        data-id-komponen="${item.id_komponen}"
                                        value="${nilaiBobot}" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </td>
                        </tr>`;
                    });
                }

                $('#bodyBobot').html(html);
                $('#totalBobot').text(total);
                this.updateStatusBobot(total);
            }
        } catch (e) {
            console.error("Gagal load data:", e);
        }
    }

    async storeBobot(idMengajarDetail) {
        const bobotItems = [];
        $('.input-bobot').each(function () {
            bobotItems.push({
                id_komponen: $(this).data('id-komponen'),
                bobot: $(this).val()
            });
        });

        const total = bobotItems.reduce((a, b) => a + parseFloat(b.bobot || 0), 0);

        if (total !== 100) {
            return warningAlert(`Total bobot harus 100%. Saat ini: ${total}%`);
        }

        try {
            loadingAllert('Menyimpan bobot...');
            const payload = {
                id_mengajar_detail: idMengajarDetail,
                bobot_items: bobotItems,
            };

            await this.ajaxRequest(`${appUrl}/sicici/kontrak-perkuliahan/store`, 'POST', payload);

            Swal.close();
            successAlert("Bobot penilaian berhasil diperbarui.");
            this.getBobotPenilaian(idMengajarDetail);
        } catch (error) {
            Swal.close();
            errorAlert("Gagal menyimpan bobot.");
        }
    }

    updateStatusBobot(total) {
        const label = $('#totalBobot');
        const btn = $('#btnSimpanBobot');

        if (total === 100) {
            label.removeClass('text-danger').addClass('text-success');
            btn.prop('disabled', false);
        } else {
            label.removeClass('text-success').addClass('text-danger');
            btn.prop('disabled', true);
        }
    }

    getEmptyTemplate(icon, title, msg) {
        return `
        <div class="py-5 text-center">
            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                 style="width: 80px; height: 80px; background-color: #f0f2ff;">
                <i class="fa-solid ${icon} fa-2x" style="color: #696cff;"></i>
            </div>
            <h5 class="fw-bold" style="color: #566a7f;">${title}</h5>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="alert shadow-none mb-0" style="background-color: #f5f5f9; border: none; border-left: 5px solid #696cff; border-radius: 8px;">
                        <div class="d-flex align-items-center text-start">
                            <i class="fa-solid fa-circle-info fs-5 me-3" style="color: #696cff;"></i>
                            <div style="color: #697a8d; font-size: 0.85rem;">${msg}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }
}

export default KontrakPerkuliahanService;
