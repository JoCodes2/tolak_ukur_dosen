class PenilaianMahasiswaService {
    constructor() {
        this.table = $('#penilaianTable');
    }

    ajaxRequest(url, method, data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                method,
                data,
                processData: method === 'GET' ? true : false,
                contentType: method === 'GET' ? 'application/x-www-form-urlencoded' : false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    resolve(response);
                },
                error: (xhr) => {
                    reject(xhr);
                }
            });
        });
    }

    getEmptyTemplate(icon, title, msg) {
        return `
        <div class="py-5 text-center">
            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                 style="width: 100px; height: 100px; background-color: #e8ebff;">
                <i class="fa-solid ${icon} fa-3x" style="color: #696cff;"></i>
            </div>

            <h5 class="fw-bold" style="color: #566a7f;">${title}</h5>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert shadow-none mb-0"
                         style="background-color: #e8ebff; border: none; border-left: 5px solid #0026ff; border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-info fs-4 me-3" style="color: #0026ff;"></i>
                            <div class="text-start" style="color: #697a8d; font-size: 0.9rem;">
                                ${msg}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    async getDaftarMengajar() {
        if (!$.fn.dataTable.isDataTable(this.table)) {
            this.table.DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    emptyTable: this.getEmptyTemplate(
                        'fa-graduation-cap',
                        'Belum Ada Jadwal Mengajar',
                        'Anda belum memiliki penugasan mata kuliah pada periode ini. Silakan hubungi bagian akademik jika terjadi kekeliruan data.'
                    )
                }
            });
        }

        const datatable = this.table.DataTable();
        datatable.clear();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/penilaian-mahasiswa/daftar-mengajar`, 'GET');
            const listData = response?.data ?? [];
            console.log(listData);

            if (!listData.length) {
                datatable.draw();
                return;
            }

            listData.forEach((item, index) => {
                const aktivitas = item.aktivitas || {};

                const actions = `
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-outline-primary btn-sm btnDetailPenilaian"
                                data-id="${item.id}" title="Detail Penilaian Mahasiswa">
                            <i class="fa fa-eye me-1"></i> Detail Penilaian
                        </button>
                    </div>`;

                datatable.row.add([
                    index + 1,
                    aktivitas.periode?.nama ?? '-',
                    aktivitas.prodi?.nama_prodi ?? '-',
                    aktivitas.kelas?.nama_kelas ?? '-',
                    `<strong>${item.mata_kuliah?.nama_mk ?? '-'}</strong><br><small>${item.mata_kuliah?.kode_mk ?? '-'}</small>`,
                    actions
                ]);
            });

            datatable.draw();

        } catch (error) {
            console.error('Gagal memuat daftar mengajar:', error);
        }
    }
}

export default PenilaianMahasiswaService;
