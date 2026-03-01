class PenilaianMahasiswaDetailService {
    constructor() {
        this.idMengajarDetail = idMengajarDetail;
        this.komponen = [];
        this.isFinal = false;
    }

    ajaxRequest(url, method, data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                method,
                data: method === 'POST' ? JSON.stringify(data) : data,
                processData: method === 'GET' ? true : false,
                contentType: method === 'GET' ? 'application/x-www-form-urlencoded' : 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => resolve(response),
                error: (xhr) => reject(xhr)
            });
        });
    }

    calculateGrade(score) {
        let grade = 'E';
        let color = 'text-danger';

        if (score >= 85) { grade = 'A'; color = 'text-success'; }
        else if (score >= 80) { grade = 'A-'; color = 'text-success'; }
        else if (score >= 75) { grade = 'B+'; color = 'text-info'; }
        else if (score >= 70) { grade = 'B'; color = 'text-info'; }
        else if (score >= 65) { grade = 'B-'; color = 'text-info'; }
        else if (score >= 60) { grade = 'C+'; color = 'text-warning'; }
        else if (score >= 55) { grade = 'C'; color = 'text-warning'; }
        else if (score >= 50) { grade = 'D'; color = 'text-danger'; }
        else { grade = 'E'; color = 'text-danger'; }

        return { grade, color };
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

    async getDetailData() {
        try {
            loadingAllert('Memuat data mahasiswa...');
            const response = await this.ajaxRequest(`${appUrl}/sicici/penilaian-mahasiswa/detail-kelas/${this.idMengajarDetail}`, 'GET');
            const data = response.data;

            this.komponen = data.komponen;
            this.isFinal = data.is_final;

            this.renderIdentitas(data.identitas);
            this.renderHeader(data.komponen);
            this.renderBody(data.identitas.aktivitas.peserta_detail, data.komponen, data.nilai);

            if (this.isFinal) {
                this.handleFinalStatus();
            }

            Swal.close();
        } catch (error) {
            Swal.close();
            errorAlert("Gagal mengambil detail penilaian.");
        }
    }

    renderIdentitas(identitas) {
        $('#info-mk').text(`${identitas.mata_kuliah.nama_mk} (${identitas.mata_kuliah.kode_mk})`);
        $('#info-kelas-prodi').text(`${identitas.aktivitas.kelas.nama_kelas} / ${identitas.aktivitas.prodi.nama_prodi}`);
        $('#info-dosen').text(identitas.dosen?.nama || 'Belum Ditentukan');
        $('#info-periode').text(identitas.aktivitas.periode.nama);
    }

    renderHeader(komponen) {
        $('.col-dinamis').remove();

        if (komponen && komponen.length > 0) {
            $('#col-komponen-header').attr('colspan', komponen.length).addClass('col-dinamis').show();
            $('.hasil-akhir-header').show();
            $('#sub-header-komponen').show();

            // Cek jika ada setidaknya satu bobot yang masih 0
            const hasInvalidBobot = komponen.some(k => parseFloat(k.bobot) <= 0);
            if (hasInvalidBobot) {
                $('#btnSimpanNilai, #btnFinalisasiNilai').hide();
                // Tampilkan info tambahan di UI jika perlu
            } else {
                $('#btnSimpanNilai, #btnFinalisasiNilai').show();
            }

            komponen.forEach(k => {
                $('#sub-header-komponen').prepend(`
                    <th class="text-center col-dinamis" width="120">
                        ${k.komponen.nama_komponen}<br>
                        <span class="badge ${parseFloat(k.bobot) > 0 ? 'bg-label-info' : 'bg-label-danger'}">
                            ${k.bobot}%
                        </span>
                    </th>
                `);
            });
        } else {
            $('#col-komponen-header').hide();
            $('.hasil-akhir-header').hide();
            $('#sub-header-komponen').hide();
            $('#btnSimpanNilai, #btnFinalisasiNilai').hide();
        }
    }

    renderBody(peserta, komponen, nilaiExisting) {
        const tbody = $('#body-penilaian');
        tbody.empty();

        if (!komponen || komponen.length === 0) {
            const msg = "Komponen penilaian belum ditentukan. Silakan atur pada menu <b>Kontrak Perkuliahan</b>.";
            tbody.append(`<tr><td colspan="100">${this.getEmptyTemplate('fa-file-circle-exclamation', 'Komponen Kosong', msg)}</td></tr>`);
            return;
        }

        // Cek total bobot (Opsional: jika total != 100%, beri peringatan)
        const totalBobot = komponen.reduce((acc, curr) => acc + parseFloat(curr.bobot), 0);

        peserta.forEach((p, index) => {
            let inputFields = '';
            let totalNilai = 0;

            komponen.forEach(k => {
                const n = nilaiExisting.find(val => val.id_peserta === p.id && val.id_bobot === k.id);
                const score = n ? parseFloat(n.nilai) : 0;
                const bobot = parseFloat(k.bobot);

                totalNilai += (score * bobot / 100);

                // LOGIKA DISABLE: Disable jika sudah final ATAU bobot komponen ini <= 0
                const isDisabled = this.isFinal || bobot <= 0;

                inputFields += `
                    <td class="text-center">
                        <input type="number"
                               class="form-control form-control-sm input-nilai text-center ${bobot <= 0 ? 'bg-light-danger' : ''}"
                               data-id-peserta="${p.id}"
                               data-id-bobot="${k.id}"
                               data-bobot="${bobot}"
                               value="${score}"
                               min="0" max="100"
                               ${isDisabled ? 'disabled' : ''}
                               title="${bobot <= 0 ? 'Bobot belum ditentukan' : ''}">
                    </td>`;
            });

            const res = this.calculateGrade(totalNilai);

            tbody.append(`
                <tr data-peserta-id="${p.id}">
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">${p.mahasiswa.nim}</td>
                    <td class="fw-semibold">${p.mahasiswa.nama}</td>
                    ${inputFields}
                    <td class="text-center fw-bold bg-light text-primary total-angka">
                        ${totalNilai.toFixed(2)}
                    </td>
                    <td class="text-center fw-bold bg-light total-huruf ${res.color}">
                        ${res.grade}
                    </td>
                </tr>
            `);
        });

        // Tampilkan pesan jika total bobot < 100
        if (totalBobot < 100) {
            tbody.prepend(`
                <tr>
                    <td colspan="100" class="bg-label-warning text-center fw-bold py-2">
                        <i class="fa fa-warning me-2"></i> Total bobot saat ini baru ${totalBobot}%. Harap lengkapi kontrak perkuliahan hingga 100%.
                    </td>
                </tr>
            `);
        }

        this.initAutoCalculate();
    }

    initAutoCalculate() {
        const self = this;
        $(document).off('input', '.input-nilai').on('input', '.input-nilai', function () {
            const tr = $(this).closest('tr');
            let total = 0;

            tr.find('.input-nilai').each(function () {
                const val = parseFloat($(this).val()) || 0;
                const bobot = parseFloat($(this).data('bobot')) || 0;
                total += (val * bobot / 100);
            });

            const res = self.calculateGrade(total);
            tr.find('.total-angka').text(total.toFixed(2));
            tr.find('.total-huruf').text(res.grade)
                .attr('class', `text-center fw-bold bg-light total-huruf ${res.color}`);
        });
    }

    async saveDraft() {
        const scores = [];
        $('.input-nilai:not(:disabled)').each(function () {
            scores.push({
                id_peserta: $(this).data('id-peserta'),
                id_bobot: $(this).data('id-bobot'),
                nilai: $(this).val() || 0
            });
        });

        if (scores.length === 0) {
            return warningAlert("Tidak ada nilai yang dapat disimpan (Bobot komponen belum diatur).");
        }

        try {
            loadingAllert('Menyimpan nilai...');
            await this.ajaxRequest(`${appUrl}/sicici/penilaian-mahasiswa/simpan`, 'POST', { scores });
            Swal.close();
            successAlert("Draft nilai berhasil disimpan.");
        } catch (error) {
            Swal.close();
            errorAlert(error.responseJSON?.message ?? "Gagal menyimpan nilai.");
        }
    }

    async finalisasi() {
        confirmAlert("Setelah finalisasi, nilai tidak dapat diubah kembali. Lanjutkan?", async () => {
            try {
                loadingAllert('Memproses finalisasi...');

                let res = await this.ajaxRequest(`${appUrl}/sicici/penilaian-mahasiswa/finalisasi/${this.idMengajarDetail}`, 'POST');
                Swal.close();
                await successAlert("Nilai berhasil difinalisasi!");
                location.reload();
            } catch (error) {
                Swal.close();
                errorAlert("Gagal melakukan finalisasi.");
            }
        });
    }

    handleFinalStatus() {
        $('#status-final-alert').removeClass('d-none');
        $('#btnSimpanNilai, #btnFinalisasiNilai').hide();
        $('.input-nilai').prop('disabled', true);
    }
}

export default PenilaianMahasiswaDetailService;
