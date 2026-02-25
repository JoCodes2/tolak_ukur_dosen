class AktivitasDetailService {
    constructor() {
        this.tablePeserta = $('#tablePeserta');
        this.tableMengajar = $('#tableMengajar');
    }

    ajaxRequest(url, method, data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                method,
                data,
                processData: method === 'GET' ? true : false,
                contentType: method === 'GET' ? 'application/x-www-form-urlencoded' : false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: (response) => resolve(response),
                error: (xhr) => reject(xhr)
            });
        });
    }

    async initHeader(id) {
        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/get/${id}`, 'GET');
            const data = response.data;
            $('#detail-nama-kelas').html(`<i class="fa-solid fa-graduation-cap me-2 text-primary"></i> ${data.kelas.nama_kelas}`);
            $('#detail-periode').html(`<i class="fa-solid fa-calendar me-1"></i> ${data.periode.nama}`);
            $('#detail-prodi').html(`<i class="fa-solid fa-university me-1"></i> ${data.prodi.nama_prodi}`);
            $('#detail-prodi-modal').text(data.prodi.nama_prodi);
        } catch (error) {
            console.error("Gagal load header");
        }
    }

    async getPeserta(id) {
        if (!$.fn.dataTable.isDataTable(this.tablePeserta)) {
            this.tablePeserta.DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    emptyTable: this.getEmptyStateTemplate(
                        'fa-user-graduate',
                        'Belum Ada Peserta Kuliah',
                        'Mahasiswa belum terdaftar dalam kelas ini. Silakan tekan tombol <strong>Tambah Kolektif</strong> untuk memasukkan daftar mahasiswa.'
                    )
                }
            });
        }

        const dt = this.tablePeserta.DataTable();
        dt.clear();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/peserta/${id}`, 'GET');
            const data = response.data ?? [];

            data.forEach((item, index) => {
                const actions = `
                    <button class="btn btn-outline-danger btn-sm btnHapusPeserta" data-id="${item.id}">
                        <i class="fa fa-trash"></i>
                    </button>`;

                dt.row.add([
                    index + 1,
                    item.mahasiswa?.nim ?? '-',
                    item.mahasiswa?.nama ?? '-',
                    item.mahasiswa?.angkatan ?? '-',
                    `<span class="badge bg-label-warning"><i class="fa fa-star me-1"></i> ${item.rating ?? 'Beri Rating'}</span>`,
                    actions
                ]);
            });
            dt.draw();
        } catch (error) { console.error('Error load peserta'); }
    }

    async getPengajar(id) {
        if (!$.fn.dataTable.isDataTable(this.tableMengajar)) {
            this.tableMengajar.DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    emptyTable: this.getEmptyStateTemplate(
                        'fa-book-open-reader',
                        'Belum Ada Penugasan Dosen',
                        'Plotting mata kuliah dan dosen belum dilakukan. Silakan tekan tombol <strong>Tambah Penugasan</strong> untuk mulai plotting.'
                    )
                }
            });
        }

        const dt = this.tableMengajar.DataTable();
        dt.clear();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/pengajar/${id}`, 'GET');
            const data = response.data ?? [];

            data.forEach((item, index) => {
                const actions = `
                    <button class="btn btn-outline-danger btn-sm btnHapusPengajar" data-id="${item.id}">
                        <i class="fa fa-trash"></i>
                    </button>`;

                dt.row.add([
                    index + 1,
                    item.mata_kuliah?.kode_mk ?? '-',
                    item.mata_kuliah?.nama_mk ?? '-',
                    `<span class="badge bg-label-info">${item.mata_kuliah?.sks ?? 0} SKS</span>`,
                    item.dosen?.name ?? '-',
                    actions
                ]);
            });
            dt.draw();
        } catch (error) { console.error('Error load pengajar'); }
    }

    getEmptyStateTemplate(icon, title, message) {
        return `
            <div class="py-5 text-center">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                     style="width: 100px; height: 100px; background-color: #e8ebff;">
                    <i class="fa-solid ${icon} fa-3x" style="color: #696cff;"></i>
                </div>

                <h5 class="fw-bold" style="color: #566a7f;">${title}</h5>

                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="alert shadow-none mb-0"
                             style="background-color: #e8ebff; border: none; border-left: 5px solid #0026ff; border-radius: 8px;">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-circle-info fs-4 me-3" style="color: #0026ff;"></i>
                                <div class="text-start" style="color: #697a8d; font-size: 0.9rem;">
                                    ${message}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
    }
}

export default AktivitasDetailService;
