class KomponenService {
    constructor() {
        this.container = $('#komponenTableContainer');
        this.tbody = $('#komponenTableBody');
        this.emptyState = $('#komponenEmpty');
        this.loadingState = $('#komponenLoading');

        this._prodiMap = {};
        this._mkMap = {};
        this._periodeMap = {};
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
                success: (response) => {
                    if (response.code === 409) {
                        reject({ status: 409, responseJSON: response });
                    } else {
                        resolve(response);
                    }
                },
                error: (xhr) => reject(xhr)
            });
        });
    }

    /* ─── Dropdown loaders ──────────────────────────────────────── */

    async loadProdiDropdown() {
        try {
            const res = await this.ajaxRequest(`${appUrl}/sicici/prodi/`, 'GET');
            let opts = '<option value="">-- Pilih Program Studi --</option>';
            (res.data ?? []).forEach(p => {
                this._prodiMap[p.id] = p.nama_prodi;
                opts += `<option value="${p.id}">${p.nama_prodi}</option>`;
            });
            $('#id_prodi').html(opts);
            $('#filter_id_prodi').html('<option value="">Semua Program Studi</option>' + opts.replace('-- Pilih Program Studi --', 'Semua Program Studi'));
        } catch (_) { console.error('Gagal memuat daftar prodi'); }
    }

    async loadMatakuliahDropdown() {
        try {
            const res = await this.ajaxRequest(`${appUrl}/sicici/matakuliah/`, 'GET');
            let opts = '<option value="">-- Pilih Matakuliah --</option>';
            (res.data ?? []).forEach(mk => {
                this._mkMap[mk.id] = `${mk.kode_mk} - ${mk.nama_mk}`;
                opts += `<option value="${mk.id}">${mk.kode_mk} - ${mk.nama_mk}</option>`;
            });
            $('#id_mk').html(opts);
            $('#filter_id_mk').html('<option value="">Semua Matakuliah</option>' + opts.replace('-- Pilih Matakuliah --', 'Semua Matakuliah'));
        } catch (_) { console.error('Gagal memuat daftar matakuliah'); }
    }

    async loadPeriodeDropdown() {
        try {
            const res = await this.ajaxRequest(`${appUrl}/sicici/periode/`, 'GET');
            let opts = '<option value="">-- Pilih Periode --</option>';
            (res.data ?? []).forEach(p => {
                const label = [p.nama, p.semester, p.tahun_ajaran].filter(Boolean).join(' – ');
                this._periodeMap[p.id] = label;
                opts += `<option value="${p.id}">${label}</option>`;
            });
            $('#id_periode').html(opts);
        } catch (_) { console.error('Gagal memuat daftar periode'); }
    }

    /* ─── getAllData → render card ──────────────────────────────── */

    async getAllData() {
        this.loadingState.removeClass('d-none');
        this.container.addClass('d-none');
        this.emptyState.addClass('d-none');
        this.tbody.empty();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/komponen/`, 'GET');
            let data = response?.data ?? [];

            // Client-side filtering
            const filterProdi = $('#filter_id_prodi').val();
            const filterMk = $('#filter_id_mk').val();

            if (filterProdi) {
                data = data.filter(item => item.id_prodi === filterProdi);
            }
            if (filterMk) {
                data = data.filter(item => item.id_mk === filterMk);
            }

            this.loadingState.addClass('d-none');

            if (!data.length) {
                this.emptyState.removeClass('d-none');
                return;
            }

            this.container.removeClass('d-none');

            data.forEach((item, index) => {
                const prodiName = this._prodiMap[item.id_prodi] ?? item.id_prodi ?? '-';
                const mkName = this._mkMap[item.id_mk] ?? item.id_mk ?? '-';
                const periodeName = this._periodeMap[item.id_periode] ?? item.id_periode ?? '-';

                // Determine color based on Prodi Name
                let accent = '#696cff'; // Default Blue
                let bg = '#eef0ff';

                if (prodiName.toLowerCase().includes('informatika')) {
                    accent = '#ff3e1d'; // Red for TI
                    bg = '#fff5f4';
                } else if (prodiName.toLowerCase().includes('sistem informasi')) {
                    accent = '#ffab00'; // Yellow/Orange for SI
                    bg = '#fffbf0';
                } else {
                    // Fallback to rotating palettes for other prodi
                    const palettes = [
                        { accent: '#03c3ec', bg: '#e0f7fb' },
                        { accent: '#71dd37', bg: '#eafcd3' },
                        { accent: '#fd7e14', bg: '#fff0e0' },
                        { accent: '#e83e8c', bg: '#fce4ef' },
                        { accent: '#20c997', bg: '#d9f7f0' },
                    ];
                    ({ accent, bg } = palettes[index % palettes.length]);
                }

                const komponenList = Array.isArray(item.komponen) ? item.komponen : [];

                // 1. Render Group Header Row
                const headerRow = `
                    <tr class="group-header" style="background-color: ${bg}40;">
                        <td colspan="3" class="py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                         style="width: 35px; height: 35px; background-color: ${accent}20; color: ${accent};">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">${prodiName}</h6>
                                        <div class="d-flex gap-2 mt-1" style="font-size: 0.78rem; color: #697a8d;">
                                            <span><i class="fa-solid fa-book-open me-1" style="color: ${accent}"></i> ${mkName}</span>
                                            <span class="ms-2"><i class="fa-regular fa-calendar me-1" style="color: ${accent}"></i> ${periodeName}</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge rounded-pill" style="background-color: ${accent}; color: white; font-weight: 500;">
                                    ${komponenList.length} Komponen
                                </span>
                            </div>
                        </td>
                    </tr>
                `;
                this.tbody.append(headerRow);

                // 2. Render Individual Component Rows
                komponenList.forEach((k, i) => {
                    const row = `
                        <tr class="komponen-row">
                            <td class="ps-4 text-center text-muted" style="font-size: 0.85rem;">${i + 1}</td>
                            <td class="ps-3 fw-medium text-dark" style="font-size: 0.88rem;">
                                ${k.nama_komponen}
                            </td>
                            <td class="text-center py-2">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-icon btn-sm btn-outline-primary btnEditKomponen border-0" 
                                            data-id="${k.id}" 
                                            title="Edit"
                                            style="width: 32px; height: 32px; background-color: ${accent}10; color: ${accent};">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn btn-icon btn-sm btn-outline-danger btnHapusKomponen border-0" 
                                            data-id="${k.id}" 
                                            title="Hapus"
                                            style="width: 32px; height: 32px; background-color: #ff3e1d15; color: #ff3e1d;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    this.tbody.append(row);
                });
            });

        } catch (error) {
            this.loadingState.addClass('d-none');
            this.emptyState.removeClass('d-none');
            console.error('Gagal memuat data komponen:', error);
        }
    }

    /* ─── CRUD ───────────────────────────────────────────────────── */

    async upsertData(formElement, checkingEdit) {
        const submitButton = $('#btnProsesKomponen');
        const originalText = submitButton.html();

        try {
            const formData = new FormData(formElement);
            loadingAllert('Sedang memproses...', 'Harap tunggu sebentar');

            let responseData;
            if (checkingEdit()) {
                const id = $('#komponen_id').val();
                responseData = await this.ajaxRequest(`${appUrl}/sicici/komponen/update/${id}`, 'POST', formData);
            } else {
                responseData = await this.ajaxRequest(`${appUrl}/sicici/komponen/create`, 'POST', formData);
            }
            Swal.close();
            await successAlert();
            $('#modalInputKomponen').modal('hide');
            realoadBrowser();

        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);

            const status = error.status || error.responseJSON?.code || error.responseJSON?.status;

            if (status === 422) {
                warningAlert('Periksa Inputan anda');
                const errors = error.responseJSON?.data ?? error.responseJSON?.errors;
                const validator = $('#formSimpanKomponen').validate();
                const errorList = {};
                $.each(errors, function (field, messages) {
                    errorList[field] = messages[0];
                });
                validator.showErrors(errorList);
            } else if (status === 409) {
                warningAlert('Data sudah ada dalam sistem!');
            } else {
                errorAlert("Terjadi kesalahan sistem");
            }
        }
    }

    async getDataById(id) {
        try {
            loadingAllert('Mengambil data...');
            const responseData = await this.ajaxRequest(`${appUrl}/sicici/komponen/get/${id}`, 'GET');
            const item = responseData.data;
            Swal.close();

            $('#komponen_id').val(item.id);
            $('#id_prodi').val(item.id_prodi).trigger('change');
            $('#id_mk').val(item.id_mk).trigger('change');
            $('#id_periode').val(item.id_periode).trigger('change');
            $('#nama_komponen').val(item.nama_komponen);

            $('#modalInputKomponen').modal('show');

            if ($('#formSimpanKomponen').data('validator')) {
                $('#formSimpanKomponen').validate().resetForm();
            }
            $('#formSimpanKomponen .form-control, #formSimpanKomponen .form-select').removeClass('is-invalid');

        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil detail Komponen Penilaian.");
        }
    }

    async deleteData(id) {
        confirmAlert(
            "Data Komponen Penilaian yang dihapus tidak dapat dikembalikan!",
            async () => {
                try {
                    loadingAllert('Menghapus data...');
                    await this.ajaxRequest(`${appUrl}/sicici/komponen/delete/${id}`, 'DELETE');
                    Swal.close();
                    await successAlert();
                    realoadBrowser();
                } catch (error) {
                    Swal.close();
                    errorAlert("Gagal menghapus data Komponen Penilaian.");
                }
            }
        );
    }
}

export default KomponenService;
