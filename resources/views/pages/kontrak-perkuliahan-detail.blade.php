@extends('Layouts.Base')

@section('content')
    <div class="card mb-4">
        <x-base-header title="Detail Informasi Mata Kuliah" icon="fa-solid fa-circle-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="/kontrak-perkuliahan">Kontrak</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </x-base-header>
        <x-base-body>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 150px;">Mata Kuliah</td>
                            <td class="fw-bold">: <span id="detail-nama-mk">...</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kode / SKS</td>
                            <td>: <span id="detail-kode-mk">...</span> / <span id="detail-sks">...</span> SKS</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dosen Pengampu</td>
                            <td>: <span id="detail-dosen">...</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 border-start-md">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 150px;">Program Studi</td>
                            <td>: <span id="detail-prodi">...</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Periode / Kelas</td>
                            <td>: <span id="detail-periode">...</span> / <span id="detail-kelas">...</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </x-base-body>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <x-base-header title="Komponen Penilaian" icon="fa-solid fa-sliders">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnSyncKomponen">
                        <i class="fa-solid fa-sync me-1"></i> Sinkronisasi
                    </button>
                </x-base-header>

                <x-base-body>
                    <div class="alert alert-warning border-0 small mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Pastikan total bobot berjumlah <strong>100%</strong> agar tombol simpan aktif. Data ini digunakan untuk<strong>Perhitungan Penilaian Mahasiswa</strong>.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">Nama Komponen</th>
                                    <th class="text-center py-3" style="width: 180px;">Bobot (%)</th>
                                </tr>
                            </thead>
                            <tbody id="bodyBobot">
                                </tbody>
                            <tfoot class="table-light fw-bold fs-6">
                                <tr>
                                    <td class="text-end py-3">Total Persentase :</td>
                                    <td class="text-center py-3">
                                        <span id="totalBobot">0</span>%
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="/kontrak-perkuliahan" class="btn btn-label-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-primary" id="btnSimpanBobot" disabled>
                            <i class="fa-solid fa-save me-1"></i> Simpan Kontrak
                        </button>
                    </div>
                </x-base-body>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <x-base-header title="Petunjuk" icon="fa-solid fa-lightbulb" />
                <x-base-body>
                    <ol class="small text-muted ps-3">
                        <li class="mb-2">Klik <strong>Sinkronisasi</strong> jika daftar komponen belum muncul.</li>
                        <li class="mb-2">Masukkan nilai bobot (0-100) pada kolom yang tersedia.</li>
                        <li class="mb-2">Sistem akan otomatis menghitung total di bagian bawah tabel.</li>
                        <li>Klik <strong>Simpan Kontrak</strong> setelah total mencapai 100%.</li>
                    </ol>
                </x-base-body>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('controllers/kontrak-perkuliahan.controller.js') }}"></script>
@endsection
