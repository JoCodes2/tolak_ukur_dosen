class MahasiswaService {
    constructor() {
        this.table = $('#mahasiswaTable');
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

                        <h5 class="fw-bold text-muted">Belum Ada Data Mahasiswa</h5>

                        <p class="text-muted small mb-0">
                            Silakan tekan tombol <strong>Tambah Mahasiswa</strong>
                            untuk mulai mengisi data master.
                        </p>
                    </div>`
                }
            });
        }

        const datatable = this.table.DataTable();
        datatable.clear();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/mahasiswa/`, 'GET');
            const mahasiswaData = response?.data ?? [];

            if (!mahasiswaData.length) {
                datatable.draw();
                return;
            }

            mahasiswaData.forEach((item, index) => {

                const actions = `
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-info btn-sm btnEditMahasiswa"
                            data-id="${item.id}" title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm btnHapusMahasiswa"
                            data-id="${item.id}" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>`;

                datatable.row.add([
                    index + 1,
                    item.nim ?? '-',
                    item.nama ?? '-',
                    item.angkatan ?? '-',
                    item.prodi?.nama_prodi ?? '-',
                    actions
                ]);
            });

            datatable.draw();

        } catch (error) {
            console.error('Gagal memuat data mahasiswa:', error);
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
                const id = $('#mahasiswa_id').val();
                responseData = await this.ajaxRequest(`${appUrl}/sicici/mahasiswa/update/${id}`, 'POST', formData);
            } else {
                responseData = await this.ajaxRequest(`${appUrl}/sicici/mahasiswa/create`, 'POST', formData);
            }

            Swal.close();

            await successAlert();
            $('#modalInputMahasiswa').modal('hide');
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
            const responseData = await this.ajaxRequest(`${appUrl}/sicici/mahasiswa/get/${id}`, 'GET');
            const item = responseData.data;
            Swal.close();
            console.log(responseData);

            $('#mahasiswa_id').val(item.id);
            $('#nim').val(item.nim);
            $('#nama').val(item.nama);
            $('#angkatan').val(item.angkatan);
            $('#id_prodi').val(item.id_prodi);

            $('#modalInputMahasiswa').modal('show');

            if ($('#formSimpanMahasiswa').data('validator')) {
                $('#formSimpanMahasiswa').validate().resetForm();
            }
            $('#formSimpanMahasiswa .form-control').removeClass('is-invalid');

        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil detail Mahasiswa.");
        }
    }

    async deleteData(id) {
        confirmAlert(
            "Data Mahasiswa yang dihapus tidak dapat dikembalikan!",
            async () => {
                try {
                    loadingAllert('Menghapus data...');
                    const responseData = await this.ajaxRequest(`${appUrl}/sicici/mahasiswa/delete/${id}`, 'DELETE');

                    Swal.close();
                    await successAlert();
                    realoadBrowser();
                } catch (error) {
                    Swal.close();
                    errorAlert("Gagal menghapus data Mahasiswa.");
                }
            }
        );
    }
}

export default MahasiswaService;
