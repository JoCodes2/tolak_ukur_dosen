@extends('Layouts.Base')

@section('content')
    <div class="card">

        <x-base-header title="Manajemen Mahasiswa" icon="fa-solid fa-graduation-cap ">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" id="btnTambahMahasiswa">
                    <i class="fa fa-plus"></i> Tambah Mahasiswa
                </button>
            </div>
        </x-base-header>

        <x-base-body>
            <div class="alert alert-info border-0 small mb-4">
                <i class="fa-solid fa-circle-info me-1"></i>
                Halaman ini digunakan untuk mengelola data master Mahasiswa pada STMIK Adhi Guna.
            </div>

            @php
                $headers = ['No', 'Nama', 'NIM', 'Angkatan', 'Aksi'];
            @endphp

            <x-base-table :headers="$headers" id="mahasiswaTable">
                <tbody id="mahasiswaBody">
                </tbody>
            </x-base-table>
        </x-base-body>
    </div>

    <x-base-modal id="modalInputMahasiswa" title="Form Mahasiswa" size="md">

        <x-base-form id="formSimpanMahasiswa">
            <input type="hidden" name="id" id="mahasiswa_id">

            {{-- NIM --}}
            <div class="col-12 mb-3">
                <label for="nim" class="form-label">
                    NIM <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nim" name="nim" placeholder="Contoh: 732102457">
                <small id="error-nim" class="error-msg text-danger"></small>
            </div>

            {{-- Nama --}}
            <div class="col-12 mb-3">
                <label for="nama" class="form-label">
                    Nama <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nama" name="nama"
                    placeholder="Contoh: Mutia Manissss">
                <small id="error-nama" class="error-msg text-danger"></small>
            </div>

            {{-- Angkatan --}}
            <div class="col-12 mb-3">
                <label for="angkatan" class="form-label">
                    Angkatan <span class="text-danger">*</span>
                </label>
                <input type="number" class="form-control" id="angkatan" name="angkatan" placeholder="Contoh: 2022"
                    min="2000" max="{{ date('Y') }}">
                <small id="error-angkatan" class="error-msg text-danger"></small>
            </div>
        </x-base-form>

        <x-slot name="footer">
            <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
            <x-base-button id="btnProsesMahasiswa" variant="primary" text="Simpan & Proses" icon="fa-solid fa-save" />
        </x-slot>
    </x-base-modal>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('controllers/mahasiswa.controller.js') }}"></script>
@endsection
