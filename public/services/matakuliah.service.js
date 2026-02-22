class MatakuliahService {
    constructor() {
        this.table = $('#matakuliahTable');
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

    // Inisialisasi DataTable sekali saja
    if (!$.fn.dataTable.isDataTable(this.table)) {
        this.table.DataTable({
            pageLength: 10,
            responsive: true,
            language: {
                emptyTable: `
                    <div class="py-5 text-center">
                        <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                             style="width: 90px; height: 90px; background-color: #e8ebff;">
                            <i class="fa-solid fa-user-graduate fa-3x" style="color: #696cff;"></i>
                        </div>

                        <h5 class="fw-bold text-muted">Belum Ada Data MK</h5>

                        <p class="text-muted small mb-0">
                            Silakan tekan tombol <strong>Tambah Matakuliah</strong>
                            untuk mulai mengisi data master.
                        </p>
                    </div>`
            }
        });
    }

    const datatable = this.table.DataTable();
    datatable.clear();

    try {
        const response = await this.ajaxRequest(`${appUrl}/sicici/matakuliah/`, 'GET');
        const matakuliahData = response?.data ?? [];

        if (!matakuliahData.length) {
            datatable.draw();
            return;
        }

        matakuliahData.forEach((item, index) => {

            const actions = `
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-info btn-sm btnEditMatakuliah"
                            data-id="${item.id}" title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm btnHapusMatakuliah"
                            data-id="${item.id}" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>`;

            datatable.row.add([
                index + 1,
                item.kode_mk ?? '-',
                item.nama_mk ?? '-',
                item.sks ?? '-',
                actions
            ]);
        });

        datatable.draw();

    } catch (error) {
        console.error('Gagal memuat data matakuliah:', error);
    }
}

    async upsertData(formElement, checkingEdit) {
        const submitButton = $('#btnProsesMatakuliah');
        const originalText = submitButton.html();

        try {
            const formData = new FormData(formElement);
            loadingAllert('Sedang memproses...', 'Harap tunggu sebentar');

            let responseData;
            if (checkingEdit()) {
                const id = $('#matakuliah_id').val();
                responseData = await this.ajaxRequest(`${appUrl}/sicici/matakuliah/update/${id}`, 'POST', formData);
            } else {
                responseData = await this.ajaxRequest(`${appUrl}/sicici/matakuliah/create`, 'POST', formData);
            }

            Swal.close();

            await successAlert();
            $('#modalInputMatakuliah').modal('hide');
            realoadBrowser();

        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);

            const status = error.status || error.responseJSON?.code || error.responseJSON?.status;

            if (status === 422) {
                warningAlert('Periksa Inputan anda');
                const errors = error.responseJSON?.data ?? error.responseJSON?.errors;
                const validator = $('#formSimpanMatakuliah').validate();
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
            const responseData = await this.ajaxRequest(`${appUrl}/sicici/matakuliah/get/${id}`, 'GET');
            const item = responseData.data;
            Swal.close();
            $('#matakuliah_id').val(item.id);
            $('#kode_mk').val(item.kode_mk);
            $('#nama_mk').val(item.nama_mk);
            $('#sks').val(item.sks);

            $('#modalInputMatakuliah').modal('show');

            if ($('#formSimpanMatakuliah').data('validator')) {
                $('#formSimpanMatakuliah').validate().resetForm();
            }
            $('#formSimpanMatakuliah .form-control').removeClass('is-invalid');

        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil detail Matakuliah.");
        }
    }

    async deleteData(id) {
        confirmAlert(
            "Data Matakuliah yang dihapus tidak dapat dikembalikan!",
            async () => {
                try {
                    loadingAllert('Menghapus data...');
                    const responseData = await this.ajaxRequest(`${appUrl}/sicici/matakuliah/delete/${id}`, 'DELETE');

                    Swal.close();
                    await successAlert();
                    realoadBrowser();
                } catch (error) {
                    Swal.close();
                    errorAlert("Gagal menghapus data Matakuliah.");
                }
            }
        );
    }
}

export default MatakuliahService;
