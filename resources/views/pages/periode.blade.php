@extends('Layouts.Base')

@section('content')
    <div class="card">

        <x-base-header title="Manajemen Periode" icon="fa-solid fa-calendar">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" id="btnTambahPeriode">
                    <i class="fa fa-plus"></i> Tambah Periode
                </button>
            </div>
        </x-base-header>

        <x-base-body>
            <div class="alert alert-info border-0 small mb-4">
                <i class="fa-solid fa-circle-info me-1"></i>
                Halaman ini digunakan untuk mengelola data master Periode pada STMIK Adhi Guna.
            </div>

            @php
                $headers = ['No', 'Nama', 'Semester', 'Tahun Ajaran', 'Status', 'Aksi'];
            @endphp

            <x-base-table :headers="$headers" id="periodeTable">
                <tbody id="periodeBody">
                </tbody>
            </x-base-table>
        </x-base-body>
    </div>

    <x-base-modal id="modalInputPeriode" title="Form Periode" size="md">

        <x-base-form id="formSimpanPeriode">
            <input type="hidden" name="id" id="periode_id">

            {{-- Nama --}}
            <div class="col-12 mb-3">
                <label for="nama" class="form-label">
                    Nama <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nama" name="nama"
                    placeholder="Contoh: Semester Ganjil 2024">

                <small id="error-nama" class="error-msg text-danger"></small>
            </div>

            {{-- Nama --}}
            <div class="col-12 mb-3">
                <label for="semester" class="form-label">
                    Semester <span class="text-danger">*</span>
                </label>
                <input type="number" class="form-control" id="semester" name="semester">
                <small id="error-semester" class="error-msg text-danger"></small>
            </div>

            {{-- Angkatan --}}
            <div class="col-12 mb-3">
                <label for="tahun_ajaran" class="form-label">
                    Tahun Ajaran <span class="text-danger">*</span>
                </label>

                <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran"
                    placeholder="Contoh: 2025/2026" pattern="\d{4}/\d{4}" inputmode="numeric">

                <small id="error-tahun_ajaran" class="error-msg text-danger"></small>
            </div>

            <div class="col-12 mb-3">
                <label for="status" class="form-label">
                    Status <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="status" name="status">
                    <option value="">Pilih Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Tidak Aktif</option>
                </select>
                <small id="error-status" class="error-msg text-danger"></small>
            </div>
        </x-base-form>

        <x-slot name="footer">
            <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
            <x-base-button id="btnSimpanPeriode" variant="primary" text="Simpan Periode" icon="fa-solid fa-save" />
        </x-slot>
    </x-base-modal>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('controllers/periode.controller.js') }}"></script>
@endsection
