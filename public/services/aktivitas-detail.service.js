class AktivitasDetailService {
    constructor() {
        this.tablePeserta = $('#tablePeserta');
        this.tableMengajar = $('#tableMengajar');
    }

    ajaxRequest(url, method, data = null) {
        const isFormData = data instanceof FormData;

        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                method: method,
                data: data,
                processData: isFormData ? false : true,
                contentType: isFormData ? false : 'application/x-www-form-urlencoded',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
            $('#modalKolektifMahasiswa').attr('data-prodi-id', data.id_prodi);
        } catch (error) { console.error("Gagal load header"); }
    }

    async getPeserta(id) {
        if (!$.fn.dataTable.isDataTable(this.tablePeserta)) {
            this.tablePeserta.DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    emptyTable: this.getEmptyTemplate(
                        'fa-user-graduate',
                        'Belum Ada Peserta Terdaftar',
                        'Daftar mahasiswa untuk kelas ini masih kosong. Silakan tekan tombol <strong>Tambah Kolektif</strong> untuk memasukkan mahasiswa dari prodi yang bersangkutan ke dalam kelas ini.'
                    )
                }
            });
        }
        const dt = this.tablePeserta.DataTable();
        dt.clear();
        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/peserta/${id}`, 'GET');
            (response.data ?? []).forEach((item, index) => {
                dt.row.add([
                    index + 1,
                    item.mahasiswa?.nim ?? '-',
                    item.mahasiswa?.nama ?? '-',
                    item.mahasiswa?.angkatan ?? '-',
                    `<button class="btn btn-outline-danger btn-sm btnHapusPeserta" data-id="${item.id}"><i class="fa fa-trash"></i></button>`
                ]);
            });
            dt.draw();
        } catch (e) { console.error(e); }
    }

    async loadMahasiswaKolektif(idAktivitas) {
        const idProdi = $('#modalKolektifMahasiswa').data('prodi-id');
        const tablePilih = $('#tablePilihMahasiswa');

        try {
            loadingAllert('Memuat daftar mahasiswa...');
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/peserta/kolektif/tersedia?id_aktivitas=${idAktivitas}&id_prodi=${idProdi}`, 'GET');
            Swal.close();
            if ($.fn.DataTable.isDataTable(tablePilih)) {
                tablePilih.DataTable().destroy();
            }

            let html = '';
            (response.data ?? []).forEach(mhs => {
                html += `
            <tr>
                <td class="text-center">
                    <input type="checkbox" class="form-check-input check-mhs" value="${mhs.id}">
                </td>
                <td>${mhs.nim}</td>
                <td>${mhs.nama}</td>
            </tr>`;
            });
            $('#bodyPilihMahasiswa').html(html);

            tablePilih.DataTable({
                pageLength: 10,
                lengthMenu: [10, 20, 25, 50],
                responsive: true,
                autoWidth: false,
                language: {
                    search: "Cari Mahasiswa:",
                    lengthMenu: "_MENU_ data per halaman",
                    zeroRecords: "Mahasiswa tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ mahasiswa",
                    emptyTable: this.getEmptyTemplate(
                        'fa-user-slash',
                        'Tidak Ada Mahasiswa Tersedia',
                        'Sistem tidak menemukan mahasiswa dari prodi ini yang belum terdaftar'
                    ),
                    paginate: {
                        previous: "<i class='fa fa-chevron-left'></i>",
                        next: "<i class='fa fa-chevron-right'></i>"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: 0 }
                ]
            });
            $('#modalKolektifMahasiswa').modal('show');

            $('#checkAllMhs').prop('checked', false);

        } catch (e) {
            Swal.close();
            errorAlert("Gagal memuat data mahasiswa");
            console.error(e);
        }
    }
    async storePesertaKolektif(idAktivitas, selectedIds) {
        try {
            loadingAllert('Menyimpan mahasiswa...');

            const payload = {
                id_aktivitas: idAktivitas,
                mahasiswa_ids: selectedIds
            };

            const response = await this.ajaxRequest(
                `${appUrl}/sicici/aktivitas-perkuliahan/peserta/kolektif/store`,
                'POST',
                payload
            );
            Swal.close();
            await successAlert();

            $('#modalKolektifMahasiswa').modal('hide');
            realoadBrowser();
        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);
            errorAlert("Terjadi kesalahan sistem");
        }
    }

    async deletePeserta(id, aktivitasId) {
        confirmAlert(
            "Hapus mahasiswa dari kelas ini?",
            async () => {
                try {
                    loadingAllert('Menghapus data...');
                    await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/peserta/delete/${id}`, 'DELETE');

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

    async getPengajar(id) {
        if (!$.fn.dataTable.isDataTable(this.tableMengajar)) {
            this.tableMengajar.DataTable({
                pageLength: 10,
                responsive: true,
                drawCallback: function () {
                    $.fn.dataTable.ext.errMode = 'throw';
                },
                language: {
                    emptyTable: this.getEmptyTemplate(
                        'fa-book-open-reader',
                        'Belum Ada Penugasan Dosen',
                        'Sistem belum menemukan plotting mata kuliah atau dosen pengajar untuk aktivitas ini.'
                    )
                }
            });
        }

        const dt = this.tableMengajar.DataTable();
        dt.clear();

        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/penugasan/${id}`, 'GET');

            (response.data ?? []).forEach((item, index) => {
                dt.row.add([
                    index + 1,
                    item.mata_kuliah?.kode_mk ?? '-',
                    item.mata_kuliah?.nama_mk ?? '-',
                    `${item.mata_kuliah?.sks ?? 0} SKS`,
                    item.dosen?.nama ?? '-', // Diubah dari .name ke .nama sesuai skema DB Anda
                    `<button class="btn btn-outline-danger btn-sm btnHapusPengajar" data-id="${item.id}" data-aktivitas="${id}">
                        <i class="fa fa-trash"></i>
                    </button>`
                ]);
            });
            dt.draw();
        } catch (e) {
            console.error("Gagal memuat data pengajar:", e);
        }
    }

    async loadDropdownPenugasan() {
        try {
            const response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/penugasan/master-dropdown`, 'GET');
            const { mata_kuliah, dosen } = response.data;

            this.populateSelect('#id_mk', mata_kuliah, 'nama_mk', 'kode_mk');
            this.populateSelect('#id_dosen', dosen, 'nama');
        } catch (e) {
            console.error("Gagal load dropdown:", e);
        }
    }

    async storePenugasan(idAktivitas, form) {
        const submitButton = $('#btnSimpanPenugasan');
        const originalText = submitButton.html();

        try {
            loadingAllert('Menyimpan...');
            const data = {
                id_aktivitas: idAktivitas,
                id_mk: $('#id_mk').val(),
                id_dosen: $('#id_dosen').val(),
            };

            let response = await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/penugasan/store`, 'POST', data);
            console.log(response);

            Swal.close();
            successAlert("Penugasan berhasil disimpan");
            $('#modalPenugasan').modal('hide');
            form.reset();
            $('.select2-modal').val('').trigger('change');

            this.getPengajar(idAktivitas);
        } catch (error) {
            Swal.close();
            submitButton.attr('disabled', false).html(originalText);

            const status = error.status || error.responseJSON?.code;
            const message = error.responseJSON?.message;

            if (status === 422) {
                warningAlert('Periksa inputan anda');
                const errors = error.responseJSON?.data ?? error.responseJSON?.errors;
                const validator = $('#formPenugasan').validate();
                const errorList = {};
                $.each(errors, function (field, messages) {
                    errorList[field] = messages[0];
                });
                validator.showErrors(errorList);
            } else if (status === 400) {
                warningAlert(message || 'Permintaan tidak valid');
            } else if (status === 409) {
                warningAlert('Data sudah ada dalam sistem!');
            } else {
                errorAlert(message || "Terjadi kesalahan sistem");
            }
        }
    }

    async deletePengajar(id, aktivitasId) {
        confirmAlert("Hapus penugasan dosen?", async () => {
            try {
                loadingAllert('Menghapus...');
                await this.ajaxRequest(`${appUrl}/sicici/aktivitas-perkuliahan/penugasan/delete/${id}`, 'DELETE');
                Swal.close();
                successAlert("Penugasan berhasil dihapus");
                this.getPengajar(aktivitasId);
            } catch (e) {
                Swal.close();
                errorAlert("Gagal menghapus data");
            }
        });
    }

    populateSelect(selector, data, label, subLabel = null) {
        let html = '<option value="">-- Pilih --</option>';
        (data ?? []).forEach(i => {
            html += `<option value="${i.id}">${i[subLabel] ? `[${i[subLabel]}] ` : ''}${i[label]}</option>`;
        });
        $(selector).html(html);
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
}
export default AktivitasDetailService;
