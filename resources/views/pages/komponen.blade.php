@extends('Layouts.Base')

@section('content')
<div class="card">
    <x-base-header title="Komponen Penilaian Prodi" icon="fa-solid fa-list-check">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnTambahKomponen">
                <i class="fa fa-plus"></i> Tambah Komponen
            </button>
        </div>
    </x-base-header>

    <x-base-body>
        <div class="alert alert-primary mb-4 border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid #696cff !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-info me-3 fs-4"></i>
                <p class="mb-0 fw-medium" style="font-size: 0.9rem;">
                    Halaman ini digunakan untuk mengelola data Komponen Penilaian per Program Studi, Matakuliah, dan Periode.
                </p>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; background-color: #fcfcff;">
            <div class="card-body p-3">
                <div class="row align-items-center g-3">
                    @if(auth()->user()->role !== 'prodi')
                    <div class="col-md-5">
                        <label class="form-label fw-bold text-dark small mb-1">Filter Program Studi</label>
                        <select id="filter_id_prodi" class="form-control select2">
                            <option value="">Semua Program Studi</option>
                        </select>
                    </div>
                    @endif
                    <div class="{{ auth()->user()->role === 'prodi' ? 'col-md-10' : 'col-md-5' }}">
                        <label class="form-label fw-bold text-dark small mb-1">Filter Matakuliah</label>
                        <select id="filter_id_mk" class="form-control select2">
                            <option value="">Semua Matakuliah</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end pt-md-4 mt-md-0">
                        <button id="btnResetFilter" class="btn btn-outline-secondary w-100 border-0" style="background: #f1f3f4; color: #5f6368;">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Loading state --}}
        <div id="komponenLoading" class="py-5 text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted small">Memuat data...</p>
        </div>

        {{-- Empty state --}}
        <div id="komponenEmpty" class="d-none py-5 text-center">
            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                 style="width: 90px; height: 90px; background-color: #e8ebff;">
                <i class="fa-solid fa-list-check fa-3x" style="color: #696cff;"></i>
            </div>
            <h5 class="fw-bold" style="color: #566a7f;">Belum Ada Data Komponen Penilaian</h5>
            <p class="text-muted small mb-0">
                Silakan tekan tombol <strong>Tambah Komponen</strong> untuk mulai mengisi data.
            </p>
        </div>

        {{-- Tabel dengan Grouping --}}
        <div id="komponenTableContainer" class="d-none">
            <div class="table-responsive border rounded-3 overflow-hidden">
                <table class="table table-hover align-middle mb-0" id="komponenTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3" style="width: 50px;">#</th>
                            <th class="py-3">Komponen Penilaian</th>
                            <th class="py-3 text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="komponenTableBody">
                        {{-- Data akan dirender di sini oleh service --}}
                    </tbody>
                </table>
            </div>
        </div>
    </x-base-body>
</div>

<x-base-modal id="modalInputKomponen" title="Form Komponen Penilaian" size="lg">
    <x-base-form id="formSimpanKomponen">
        <input type="hidden" name="id" id="komponen_id">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_prodi" class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                <select class="form-select" id="id_prodi" name="id_prodi">
                    <option value="">-- Pilih Program Studi --</option>
                </select>
                <small id="error-id_prodi" class="error-msg text-danger"></small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="id_mk" class="form-label fw-semibold">Matakuliah <span class="text-danger">*</span></label>
                <select class="form-select" id="id_mk" name="id_mk">
                    <option value="">-- Pilih Matakuliah --</option>
                </select>
                <small id="error-id_mk" class="error-msg text-danger"></small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="id_periode" class="form-label fw-semibold">Periode Akademik <span class="text-danger">*</span></label>
                <select class="form-select" id="id_periode" name="id_periode">
                    <option value="">-- Pilih Periode --</option>
                </select>
                <small id="error-id_periode" class="error-msg text-danger"></small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="nama_komponen" class="form-label fw-semibold">Nama Komponen <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_komponen" name="nama_komponen"
                    placeholder="Contoh: Ujian Tengah Semester">
                <small id="error-nama_komponen" class="error-msg text-danger"></small>
            </div>
        </div>
    </x-base-form>

    <x-slot name="footer">
        <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
        <x-base-button id="btnProsesKomponen" variant="primary" text="Simpan & Proses" icon="fa-solid fa-save" />
    </x-slot>
</x-base-modal>
@endsection

@section('scripts')
<script type="module" src="{{ asset('controllers/komponen.controller.js') }}"></script>
@endsection
