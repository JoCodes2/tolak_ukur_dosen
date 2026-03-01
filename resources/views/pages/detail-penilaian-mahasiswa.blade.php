@extends('Layouts.Base')

@section('content')
<style>
    /* Custom Styling untuk mempercantik UI */
    .table-penilaian thead th {
        vertical-align: middle;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    .input-nilai {
        border-radius: 6px;
        border: 1px solid #dce0e4;
        padding: 0.4rem;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }

    .input-nilai:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.2);
        outline: none;
    }

    .input-nilai:disabled {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #6c757d;
    }

    .total-column {
        background-color: rgba(79, 172, 254, 0.05) !important;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .info-label {
        color: #8898aa;
        font-size: 0.75rem;
        text-transform: uppercase;
        margin-bottom: 2px;
        display: block;
    }

    /* Badge khusus untuk nilai huruf agar lebih pop-out */
    .grade-badge {
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 4px;
    }
</style>

<div class="card mb-4">
    <x-base-header title="Detail Penilaian Kelas" icon="fa-solid fa-file-signature">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill" id="btn-penilaian-mahasiswa">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </button>
            <button type="button" class="btn btn-success btn-sm px-3 rounded-pill shadow-sm" id="btnSimpanNilai">
                <i class="fa fa-save me-1"></i> Simpan Draft
            </button>
            <button type="button" class="btn btn-danger btn-sm px-3 rounded-pill shadow-sm" id="btnFinalisasiNilai">
                <i class="fa fa-lock me-1"></i> Finalisasi
            </button>
        </div>
    </x-base-header>

    <x-base-body>
        <div class="row g-3">
            <div class="col-md-3">
                <span class="info-label">Mata Kuliah</span>
                <span id="info-mk" class="fw-bold text-dark">-</span>
            </div>
            <div class="col-md-3 border-start">
                <span class="info-label">Kelas / Prodi</span>
                <span id="info-kelas-prodi" class="fw-semibold text-muted">-</span>
            </div>
            <div class="col-md-3 border-start">
                <span class="info-label">Dosen Pengampu</span>
                <span id="info-dosen" class="fw-bold">-</span>
            </div>
            <div class="col-md-3 border-start">
                <span class="info-label">Periode</span>
                <div>
                    <span id="info-periode" class="badge bg-label-info rounded-pill">-</span>
                </div>
            </div>
        </div>
    </x-base-body>
</div>

<div class="card shadow-sm">
    <x-base-body>
        <div id="status-final-alert" class="d-none mb-4">
            <div class="alert alert-soft-warning border-0 d-flex align-items-center shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                <div>
                    <strong>Data Terkunci!</strong> Nilai telah difinalisasi dan tidak dapat diubah kembali.
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle table-penilaian" id="tablePenilaian">
                <thead class="bg-light text-center border-top">
                    <tr id="header-komponen">
                        <th width="50" rowspan="2" class="border-bottom">No</th>
                        <th width="120" rowspan="2" class="border-bottom">NIM</th>
                        <th rowspan="2" class="border-bottom text-start">Nama Mahasiswa</th>
                        <th id="col-komponen-header" colspan="1" class="py-3">KOMPONEN PENILAIAN</th>
                        <th colspan="2" class="bg-primary text-white border-bottom-0">HASIL AKHIR</th>
                    </tr>
                    <tr id="sub-header-komponen">
                        <th width="100" class="bg-primary text-white border-top-0">ANGKA</th>
                        <th width="80" class="bg-primary text-white border-top-0">HURUF</th>
                    </tr>
                </thead>
                <tbody id="body-penilaian" class="border-top-0">
                    </tbody>
            </table>
        </div>
    </x-base-body>
</div>
@endsection

@section('scripts')
    <script>
        const idMengajarDetail = "{{ $id }}";
    </script>
    <script type="module" src="{{ asset('controllers/penilaian-detail.controller.js') }}"></script>
@endsection
