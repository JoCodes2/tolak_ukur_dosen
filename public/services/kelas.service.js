class KelasService {
    constructor() {
        this.table = $('#kelasTable');
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

    // Inisialisasi DataTable hanya sekali
    if (!$.fn.dataTable.isDataTable(this.table)) {
        this.table.DataTable({
            pageLength: 10,
            responsive: true,
            language: {
                emptyTable: `
                    <div class="py-5 text-center">
                        <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                             style="width:100px;height:100px;background:#e8ebff;">
                            <i class="fa-solid fa-school fa-3x" style="color:#696cff;"></i>
                        </div>

                        <h5 class="fw-bold" style="color:#566a7f;">
                            Belum Ada Data Kelas
                        </h5>

                        <div class="alert shadow-none mt-3"
                             style="background:#e8ebff;border-left:5px solid #0026ff;">
                            Silakan klik <strong>Tambah Kelas</strong> untuk mulai menambahkan data.
                        </div>
                    </div>`
            }
        });
    }

    const datatable = this.table.DataTable();
    datatable.clear();

    try {
        const response = await this.ajaxRequest(`${appUrl}/sicici/kelas`, 'GET');
        const kelasData = response.data ?? [];

        if (kelasData.length > 0) {

            kelasData.forEach((item, index) => {

                const actions = `
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-outline-info btn-sm btnEditKelas"
                                data-id="${item.id}" title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm btnHapusKelas"
                                data-id="${item.id}" title="Hapus">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>`;

                datatable.row.add([
                    index + 1,
                    item.nama_kelas ?? '-',
                    actions
                ]);
            });
        }

        datatable.draw();

    } catch (error) {
        console.error('Gagal memuat data kelas:', error);
    }
}
    async upsertData(formElement, checkingEdit) {
        const submitButton = $('#btnProsesKelas');
        const originalText = submitButton.html();

        try {
            const formData = new FormData(formElement);
            loadingAllert('Sedang memproses...', 'Harap tunggu sebentar');

            let responseData;
            if (checkingEdit()) {
                const id = $('#kelas_id').val();
                responseData = await this.ajaxRequest(`${appUrl}/sicici/kelas/update/${id}`, 'POST', formData);
            } else {
                responseData = await this.ajaxRequest(`${appUrl}/sicici/kelas/create`, 'POST', formData);
            }

            Swal.close();

            await successAlert();
            $('#modalInputKelas').modal('hide');
            realoadBrowser();

        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);

            const status = error.status || error.responseJSON?.code || error.responseJSON?.status;

            if (status === 422) {
                warningAlert('Periksa Inputan anda');
                const errors = error.responseJSON?.data ?? error.responseJSON?.errors;
                const validator = $('#formSimpanKelas').validate();
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
            const responseData = await this.ajaxRequest(`${appUrl}/sicici/kelas/get/${id}`, 'GET');
            const item = responseData.data;
            Swal.close();
            $('#kelas_id').val(item.id);
            $('#nama_kelas').val(item.nama_kelas);

            $('#modalInputKelas').modal('show');

            if ($('#formSimpanKelas').data('validator')) {
                $('#formSimpanKelas').validate().resetForm();
            }
            $('#formSimpanKelas .form-control').removeClass('is-invalid');

        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil detail Kelas.");
        }
    }

    async deleteData(id) {
        confirmAlert(
            "Data Kelas yang dihapus tidak dapat dikembalikan!",
            async () => {
                try {
                    loadingAllert('Menghapus data...');
                    const responseData = await this.ajaxRequest(`${appUrl}/sicici/kelas/delete/${id}`, 'DELETE');

                    Swal.close();
                    await successAlert();
                    realoadBrowser();
                } catch (error) {
                    Swal.close();
                    errorAlert("Gagal menghapus data ");
                }
            }
        );
    }
}

export default KelasService;
