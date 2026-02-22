class PeriodeService {
    constructor() {
        this.table = $('#periodeTable');
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

                        <h5 class="fw-bold text-muted">Belum Ada Data Periode</h5>

                        <p class="text-muted small mb-0">
                            Silakan tekan tombol <strong>Tambah Periode</strong>
                            untuk mulai mengisi data master.
                        </p>
                    </div>`
            }
        });
    }

    const datatable = this.table.DataTable();
    datatable.clear();

    try {
        const response = await this.ajaxRequest(`${appUrl}/sicici/periode/`, 'GET');
        const periodeData = response?.data ?? [];

        if (!periodeData.length) {
            datatable.draw();
            return;
        }

        periodeData.forEach((item, index) => {

            const actions = `
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-info btn-sm btnEditPeriode"
                            data-id="${item.id}" title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm btnHapusPeriode"
                            data-id="${item.id}" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>`;

            const statusBadge = item.status === 'aktif'
                ? `<span class="text-success fs-5">
                        <i class="fa-solid fa-circle-check"></i>
                </span>`
                : `<span class="text-danger fs-5">
                        <i class="fa-solid fa-circle-xmark"></i>
                </span>`;

            datatable.row.add([
                index + 1,
                item.nama ?? '-',
                item.semester ?? '-',
                item.tahun_ajaran ?? '-',
                statusBadge,
                actions
            ]);
        });

        datatable.draw();

    } catch (error) {
        console.error('Gagal memuat data periode:', error);
    }
}

    async upsertData(formElement, checkingEdit) {
        const submitButton = $('#btnSimpanPeriode');
        const originalText = submitButton.html();

        try {
            const formData = new FormData(formElement);
            loadingAllert('Sedang memproses...', 'Harap tunggu sebentar');

            let responseData;
            if (checkingEdit()) {
                const id = $('#periode_id').val();
                responseData = await this.ajaxRequest(`${appUrl}/sicici/periode/update/${id}`, 'POST', formData);
            } else {
                responseData = await this.ajaxRequest(`${appUrl}/sicici/periode/create`, 'POST', formData);
            }

            Swal.close();

            await successAlert();
            $('#modalInputPeriode').modal('hide');
            realoadBrowser();

        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);

            const status = error.status || error.responseJSON?.code || error.responseJSON?.status;

            if (status === 422) {
                warningAlert('Periksa Inputan anda');
                const errors = error.responseJSON?.data ?? error.responseJSON?.errors;
                const validator = $('#formSimpanPeriode').validate();
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
            const responseData = await this.ajaxRequest(`${appUrl}/sicici/periode/get/${id}`, 'GET');
            const item = responseData.data;
            Swal.close();
            $('#periode_id').val(item.id);
            $('#nama').val(item.nama);
            $('#semester').val(item.semester);
            $('#tahun_ajaran').val(item.tahun_ajaran);
            $('#status').val(item.status);

            $('#modalInputPeriode').modal('show');

            if ($('#formSimpanPeriode').data('validator')) {
                $('#formSimpanPeriode').validate().resetForm();
            }
            $('#formSimpanPeriode .form-control').removeClass('is-invalid');

        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil detail Periode.");
        }
    }

    async deleteData(id) {
        confirmAlert(
            "Data Periode yang dihapus tidak dapat dikembalikan!",
            async () => {
                try {
                    loadingAllert('Menghapus data...');
                    const responseData = await this.ajaxRequest(`${appUrl}/sicici/periode/delete/${id}`, 'DELETE');

                    Swal.close();
                    await successAlert();
                    realoadBrowser();
                } catch (error) {
                    Swal.close();
                    errorAlert("Gagal menghapus data Periode.");
                }
            }
        );
    }
}

export default PeriodeService;
