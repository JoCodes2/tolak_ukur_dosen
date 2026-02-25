class UserService {
    constructor() {
        this.table = $('#userTable');
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
                            <i class="fa-solid fa-users-gear fa-3x" style="color: #696cff;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #566a7f;">Belum Ada Data Pengguna</h5>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="alert shadow-none mb-0"
                                     style="background-color: #e8ebff; border: none; border-left: 5px solid #0026ff; border-radius: 8px;">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-circle-info fs-4 me-3" style="color: #0026ff;"></i>
                                        <div class="text-start" style="color: #697a8d; font-size: 0.9rem;">
                                            Silakan tekan tombol <strong>Tambah Pengguna</strong> untuk mengelola Dosen atau Kaprodi.
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
            const response = await this.ajaxRequest(`${appUrl}/sicici/user/`, 'GET');
            const userData = response.data;


            if (userData && userData.length > 0) {
                let displayIndex = 1;

                userData.forEach((item) => {
                    if (item.role !== 'admin') {

                        // Logika Penentuan Badge dan Label Teks
                        let roleBadge = '';
                        let roleText = '';

                        if (item.role === 'prodi') {
                            roleBadge = 'bg-label-info';
                            roleText = 'Kaprodi';
                        } else if (item.role === 'dosen') {
                            roleBadge = 'bg-label-warning';
                            roleText = 'Tenaga Pengajar';
                        }

                        const actions = `
            <div class="d-flex justify-content-center gap-2">
                <button class="btn btn-outline-info btn-sm btnEditUser" data-id="${item.id}" title="Edit">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm btnHapusUser" data-id="${item.id}" title="Hapus">
                    <i class="fa fa-trash"></i>
                </button>
            </div>`;

                        datatable.row.add([
                            displayIndex++,
                            item.nidn || '-',
                            `<div><strong>${item.nama}</strong>${item.prodi ? `<br><small class="text-primary">${item.prodi.nama_prodi}</small>` : ''}</div>`,
                            item.email,
                            `<span class="badge ${roleBadge}">${roleText}</span>`, // Menggunakan roleText yang baru
                            item.jabatan || '-',
                            actions
                        ]);
                    }
                });
                datatable.draw();
            }
        } catch (error) {
            console.error('Gagal memuat data:', error);
        }
    }
    async upsertData(formElement, checkingEdit) {
        const submitButton = $('#btnProsesUser');
        const originalText = submitButton.html();

        try {
            const formData = new FormData(formElement);
            loadingAllert('Sedang memproses...', 'Harap tunggu sebentar');

            let responseData;
            if (checkingEdit()) {
                const id = $('#id_user').val();
                responseData = await this.ajaxRequest(`${appUrl}/sicici/user/update/${id}`, 'POST', formData);
            } else {
                responseData = await this.ajaxRequest(`${appUrl}/sicici/user/create`, 'POST', formData);
            }

            Swal.close();
            await successAlert("Data pengguna berhasil diproses");
            $('#modalInputUser').modal('hide');
            realoadBrowser();

        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);
            const status = error.status || error.responseJSON?.code;

            if (status === 422) {
                warningAlert('Periksa kembali inputan anda');
                const errors = error.responseJSON?.data ?? error.responseJSON?.errors;
                const validator = $('#formSimpanUser').validate();
                const errorList = {};
                $.each(errors, function (field, messages) {
                    errorList[field] = messages[0];
                });
                validator.showErrors(errorList);
            } else {
                errorAlert(error.responseJSON?.message || "Terjadi kesalahan sistem");
            }
        }
    }

    async getDataById(id) {
        try {
            loadingAllert('Mengambil data...');
            const responseData = await this.ajaxRequest(`${appUrl}/sicici/user/get/${id}`, 'GET');
            const item = responseData.data;
            Swal.close();

            // Reset form & set data
            $('#id_user').val(item.id);
            $('#nama').val(item.nama);
            $('#email').val(item.email);
            $('#nidn').val(item.nidn);
            $('#jabatan').val(item.jabatan);
            $('#role').val(item.role).trigger('change');

            // Logika khusus role prodi
            if (item.role === 'prodi') {
                $('#id_prodi').val(item.id_prodi).trigger('change');
            }

            // Tampilkan note password dan hilangkan required jika edit
            $('#password_note').show();
            $('#password, #password_confirmation').val('');

            $('#modalInputUser').modal('show');

        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil detail Pengguna.");
        }
    }

    async deleteData(id) {
        confirmAlert(
            "Akun pengguna yang dihapus tidak dapat diakses kembali!",
            async () => {
                try {
                    loadingAllert('Menghapus akun...');
                    await this.ajaxRequest(`${appUrl}/sicici/user/delete/${id}`, 'DELETE');
                    Swal.close();
                    await successAlert("Akun telah dihapus");
                    realoadBrowser();
                } catch (error) {
                    Swal.close();
                    errorAlert("Gagal menghapus data.");
                }
            }
        );
    }

    async loadProdiDropdown() {
        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/prodi/`, 'GET');
            let options = '<option value="">-- Pilih Program Studi --</option>';
            response.data.forEach(prodi => {
                options += `<option value="${prodi.id}">${prodi.nama_prodi}</option>`;
            });
            $('#id_prodi').html(options);
        } catch (error) {
            console.error('Gagal memuat daftar prodi');
        }
    }
}

export default UserService;
