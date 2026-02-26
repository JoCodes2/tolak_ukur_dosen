class AktivitasService {
    constructor() {
        this.table = $('#aktivitasTable');
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
                    if (response.code === 409) {
                        reject({ status: 409, responseJSON: response });
                    } else {
                        resolve(response);
                    }
                },
                error: (xhr) => {
                    reject(xhr);
                }
            });
        });
    }

    async getAllData() {
        if (!$.fn.dataTable.isDataTable(this.table)) {
            this.table.DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    emptyTable: `
                <div class="py-5 text-center">
                    <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                         style="width: 100px; height: 100px; background-color: #e8ebff;">
                        <i class="fa-solid fa-calendar-check fa-3x" style="color: #696cff;"></i>
                    </div>

                    <h5 class="fw-bold" style="color: #566a7f;">Belum Ada Aktivitas Perkuliahan</h5>

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="alert shadow-none mb-0"
                                 style="background-color: #e8ebff; border: none; border-left: 5px solid #0026ff; border-radius: 8px;">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-circle-info fs-4 me-3" style="color: #0026ff;"></i>
                                    <div class="text-start" style="color: #697a8d; font-size: 0.9rem;">
                                        Sistem belum menemukan jadwal atau wadah perkuliahan yang dibuka.
                                        Silakan tekan tombol <strong>Tambah Aktivitas</strong>
                                        untuk mulai membuka kelas baru pada periode ini.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`
                }
            });
        }

        const datatable = this.table.DataTable();
        datatable.clear();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan`, 'GET');
            const data = response.data ?? [];

            if (data.length > 0) {
                data.forEach((item, index) => {
                    const actions = `
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-outline-primary btn-sm btnDetailAktivitas" data-id="${item.id}" title="Lihat Detail">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-info btn-sm btnEditAktivitas" data-id="${item.id}" title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm btnHapusAktivitas" data-id="${item.id}" title="Hapus">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>`;

                    datatable.row.add([
                        index + 1,
                        item.periode?.nama ?? '-',
                        item.prodi?.nama_prodi ?? '-',
                        `<span class="badge bg-label-primary">${item.kelas?.nama_kelas ?? '-'}</span>`,
                        `<span class="badge badge-center rounded-pill bg-info">${item.total_dosen ?? 0}</span>`,
                        `<span class="badge badge-center rounded-pill bg-success">${item.total_mahasiswa ?? 0}</span>`,
                        actions
                    ]);
                });
            }
            datatable.draw();
        } catch (error) {
            console.error('Gagal memuat data aktivitas:', error);
        }
    }

    async goToDetail(id) {
        window.location.href = `${appUrl}/aktivitas-perkuliahan/detail/${id}`;
    }

    async upsertData(formElement, checkingEdit) {
        const submitButton = $('#btnProsesAktivitas');
        const originalText = submitButton.html();

        try {
            const formData = new FormData(formElement);
            loadingAllert('Sedang memproses...', 'Harap tunggu sebentar');

            if (checkingEdit()) {
                const id = $('#id_aktivitas').val();
                await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/update/${id}`, 'POST', formData);
            } else {
                await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/create`, 'POST', formData);
            }

            Swal.close();
            await successAlert("Aktivitas perkuliahan berhasil disimpan");
            $('#modalInputAktivitas').modal('hide');
            realoadBrowser();
        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);

            if (error.status === 422) {
                warningAlert('Periksa kembali pilihan anda');
                const errors = error.responseJSON?.errors;
                const validator = $('#formSimpanAktivitas').validate();
                const errorList = {};
                $.each(errors, function (field, messages) { errorList[field] = messages[0]; });
                validator.showErrors(errorList);
            } else {
                errorAlert("Terjadi kesalahan sistem");
            }
        }
    }

    async getDataById(id) {
        try {
            loadingAllert('Mengambil data...');
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/get/${id}`, 'GET');
            const item = response.data;
            Swal.close();

            $('#id_aktivitas').val(item.id);
            $('#id_periode').val(item.id_periode).trigger('change');
            $('#id_prodi').val(item.id_prodi).trigger('change');
            $('#id_kelas').val(item.id_kelas).trigger('change');

            $('#modalInputAktivitas').modal('show');
        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil data aktivitas.");
        }
    }

    async deleteData(id) {
        confirmAlert("Menghapus wadah aktivitas akan berdampak pada data detail di dalamnya!", async () => {
            try {
                loadingAllert('Menghapus...');
                await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/delete/${id}`, 'DELETE');
                Swal.close();
                await successAlert("Data berhasil dihapus");
                realoadBrowser();
            } catch (error) {
                Swal.close();
                errorAlert("Gagal menghapus data.");
            }
        });
    }

    async loadDropdownData() {
        try {
            const [periode, prodi, kelas] = await Promise.all([
                this.ajaxRequest(`${appUrl}/sicici/periode`, 'GET'),
                this.ajaxRequest(`${appUrl}/sicici/prodi`, 'GET'),
                this.ajaxRequest(`${appUrl}/sicici/kelas`, 'GET')
            ]);

            const periodeAktif = periode.data.filter(item => item.status == 'aktif');

            this.populateSelect('#id_periode', periodeAktif, 'nama');
            this.populateSelect('#id_prodi', prodi.data, 'nama_prodi');
            this.populateSelect('#id_kelas', kelas.data, 'nama_kelas');
        } catch (error) {
            console.error("Gagal memuat data dropdown master:", error);
        }
    }

    populateSelect(selector, data, labelKey) {
        let options = `<option value="">-- Pilih --</option>`;
        if (data) {
            data.forEach(item => {
                options += `<option value="${item.id}">${item[labelKey]}</option>`;
            });
        }
        $(selector).html(options);
    }
}

export default AktivitasService;
