@extends('Layouts.Base')

@section('content')
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="/aktivitas-perkuliahan">Aktivitas Perkuliahan</a></li>
                            <li class="breadcrumb-item active">Detail Manajemen Kelas</li>
                        </ol>
                    </nav>
                    <h4 class="fw-bold mb-0" id="detail-nama-kelas">
                        <span class="spinner-border spinner-border-sm text-primary me-2"></span> Memuat Data Kelas...
                    </h4>
                    <div class="d-flex gap-2 mt-2">
                        <span class="badge bg-label-primary" id="detail-periode"><i class="fa fa-calendar me-1"></i> --</span>
                        <span class="badge bg-label-info" id="detail-prodi"><i class="fa fa-graduation-cap me-1"></i> --</span>
                    </div>
                </div>
                <a href="/aktivitas-perkuliahan" class="btn btn-label-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="nav-align-top mb-4">
        <ul class="nav nav-pills mb-3" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-peserta">
                    <i class="fa-solid fa-users-viewfinder me-1"></i> Pelajar (Mahasiswa)
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dosen">
                    <i class="fa-solid fa-chalkboard-user me-1"></i> Penugasan Dosen
                </button>
            </li>
        </ul>

        <div class="tab-content p-0 bg-transparent shadow-none">
            <div class="tab-pane fade show active" id="tab-peserta" role="tabpanel">
                <div class="card border-0 shadow-sm py-3">
                    <x-base-header title="Daftar Mahasiswa Terdaftar" icon="fa-solid fa-user-graduate">
                        <button class="btn btn-primary btn-sm" id="btnTambahMahasiswaKolektif">
                            <i class="fa-solid fa-plus-circle me-1"></i> Tambah Kolektif
                        </button>
                    </x-base-header>
                    <x-base-body>
                        @php $h_mhs = ['No', 'NIM', 'Nama Mahasiswa', 'Angkatan', 'Aksi']; @endphp
                        <x-base-table :headers="$h_mhs" id="tablePeserta">
                            <tbody id="bodyPeserta">
                            </tbody>
                        </x-base-table>
                    </x-base-body>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-dosen" role="tabpanel">
                <div class="card border-0 shadow-sm py-3">
                    <x-base-header title="Penugasan Dosen & Mata Kuliah" icon="fa-solid fa-book-open-reader">
                        <button class="btn btn-primary btn-sm" id="btnTambahPenugasan">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Penugasan
                        </button>
                    </x-base-header>
                    <x-base-body>
                        @php $h_dosen = ['No', 'Kode MK', 'Mata Kuliah', 'SKS', 'Dosen Pengajar', 'Aksi']; @endphp
                        <x-base-table :headers="$h_dosen" id="tableMengajar">
                            <tbody id="bodyMengajar">
                            </tbody>
                        </x-base-table>
                    </x-base-body>
                </div>
            </div>
        </div>
    </div>

    <x-base-modal id="modalKolektifMahasiswa" title="Tambah Peserta Dalam Kelas Ini" size="xl">
        <form id="formKolektifMahasiswa">
            @csrf
            <div class="alert alert-info border-0 shadow-none d-flex align-items-center mb-3" style="background-color: #e8fadf;">
                <i class="fa-solid fa-circle-info me-2 text-info"></i>
                <div class="text-dark small">
                    Mahasiswa yang tampil adalah yang berada pada prodi <strong id="detail-prodi-modal">...</strong> dan belum masuk di kelas ini.
                </div>
            </div>

            <div class="table-responsive border rounded">
                <table class="table table-hover mb-0" id="tablePilihMahasiswa">
                    <thead class="table-light">
                        <tr>
                            <th width="40"><input type="checkbox" id="checkAllMhs" class="form-check-input"></th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody id="bodyPilihMahasiswa">
                        </tbody>
                </table>
            </div>
        </form>

        <x-slot name="footer">
            <x-base-button variant="secondary" data-bs-dismiss="modal" text="Tutup" />
            <x-base-button id="btnSimpanKolektif" variant="primary" text="Tambahkan Mahasiswa" icon="fa-solid fa-save" />
        </x-slot>
    </x-base-modal>

    <x-base-modal id="modalPenugasan" title="Plotting Dosen Pengajar" size="md">
        <x-base-form id="formPenugasan">
            <div class="mb-3">
                <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                <select name="id_mk" id="id_mk" class="form-select form-control select2-modal"></select>
            </div>
            <div class="mb-3">
                <label class="form-label">Dosen Pengajar <span class="text-danger">*</span></label>
                <select name="id_dosen" id="id_dosen" class="form-select form-control select2-modal"></select>
            </div>
        </x-base-form>
        <x-slot name="footer">
            <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
            <x-base-button id="btnSimpanPenugasan" variant="primary" text="Simpan" icon="fa-solid fa-check-double" />
        </x-slot>
    </x-base-modal>
@endsection

@section('scripts')
    <script>
        const aktivitasId = "{{ $id_aktivitas }}";
    </script>
    <script type="module" src="{{ asset('controllers/aktivitas-detail.controller.js') }}"></script>
@endsection
