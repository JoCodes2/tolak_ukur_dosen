class ProdiService {
    constructor() {
        this.table = $('#prodiTable');
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
                                <i class="fa-solid fa-graduation-cap fa-3x" style="color: #696cff;"></i>
                            </div>

                            <h5 class="fw-bold" style="color: #566a7f;">Belum Ada Data Program Studi</h5>

                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="alert shadow-none mb-0"
                                         style="background-color: #e8ebff; border: none; border-left: 5px solid #0026ff; border-radius: 8px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-circle-info fs-4 me-3" style="color: #0026ff;"></i>
                                            <div class="text-start" style="color: #697a8d; font-size: 0.9rem;">
                                                Sistem belum menemukan data Program Studi terdaftar.
                                                Silakan tekan tombol <strong>Tambah Program Studi</strong>
                                                untuk mulai mengisi data master.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`
                }
            });
        }

        let datatable = this.table.DataTable();
        datatable.clear();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/prodi/`, 'GET');
            const prodiData = response.data;

            if (prodiData && prodiData.length > 0) {
                prodiData.forEach((item, index) => {

                    let btnColorClass = '';
                    const kode = item.kode_prodi.toLowerCase();

                    if (kode === 'ti') {
                        btnColorClass = 'btn-danger';
                    } else if (kode === 'si') {
                        btnColorClass = 'btn-warning';
                    } else {
                        const altColors = ['btn-primary', 'btn-success', 'btn-info', 'btn-secondary', 'btn-dark'];
                        btnColorClass = altColors[index % altColors.length];
                    }

                    const btnKode = `
                        <button type="button" class="btn btn-sm ${btnColorClass} fw-bold text-uppercase px-3 shadow-sm" style="cursor: default;">
                            ${item.kode_prodi}
                        </button>`;

                    const actions = `
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-outline-info btn-sm btnEditProdi" data-id="${item.id}" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnHapusProdi" data-id="${item.id}" title="Hapus">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>`;

                    datatable.row.add([
                        index + 1,
                        btnKode,
                        item.nama_prodi,
                        actions
                    ]);
                });
                datatable.draw();
            }
        } catch (error) {
            console.error('Gagal memuat data:', error);
        }
    }

    async upsertData(formElement, checkingEdit) {
        const submitButton = $('#btnProsesProdi');
        const originalText = submitButton.html();

        try {
            const formData = new FormData(formElement);
            loadingAllert('Sedang memproses...', 'Harap tunggu sebentar');

            let responseData;
            if (checkingEdit()) {
                const id = $('#prodi_id').val();
                responseData = await this.ajaxRequest(`${appUrl}/sicici/prodi/update/${id}`, 'POST', formData);
            } else {
                responseData = await this.ajaxRequest(`${appUrl}/sicici/prodi/create`, 'POST', formData);
            }

            Swal.close();

            await successAlert();
            $('#modalInputProdi').modal('hide');
            realoadBrowser();

        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);

            const status = error.status || error.responseJSON?.code || error.responseJSON?.status;

            if (status === 422) {
                warningAlert('Periksa Inputan anda');
                const errors = error.responseJSON?.data ?? error.responseJSON?.errors;
                const validator = $('#formSimpanProdi').validate();
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
            const responseData = await this.ajaxRequest(`${appUrl}/sicici/prodi/get/${id}`, 'GET');
            const item = responseData.data;
            Swal.close();
            $('#id').val(item.id);
            $('#kode_prodi').val(item.kode_prodi);
            $('#nama_prodi').val(item.nama_prodi);

            $('#modalInputProdi').modal('show');

            if ($('#formSimpanProdi').data('validator')) {
                $('#formSimpanProdi').validate().resetForm();
            }
            $('#formSimpanProdi .form-control').removeClass('is-invalid');

        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil detail Program Studi.");
        }
    }

    async deleteData(id) {
        confirmAlert(
            "Data Program Studi yang dihapus tidak dapat dikembalikan!",
            async () => {
                try {
                    loadingAllert('Menghapus data...');
                    const responseData = await this.ajaxRequest(`${appUrl}/sicici/prodi/delete/${id}`, 'DELETE');

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

export default ProdiService;
