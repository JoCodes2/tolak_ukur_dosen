@extends('Layouts.Base')

@section('content')
    <div class="card">

        <x-base-header title="Manajemen Matakuliah" icon="fa-solid fa-book ">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" id="btnTambahMatakuliah">
                    <i class="fa fa-plus"></i> Tambah Matakuliah
                </button>
            </div>
        </x-base-header>

        <x-base-body>
            <div class="alert alert-info border-0 small mb-4">
                <i class="fa-solid fa-circle-info me-1"></i>
                Halaman ini digunakan untuk mengelola data master Matakuliah pada STMIK Adhi Guna.
            </div>

            @php
                $headers = ['No', 'Kode Matakuliah', 'Nama Matakuliah', 'SKS', 'Aksi'];
            @endphp

            <x-base-table :headers="$headers" id="matakuliahTable">
                <tbody id="matakuliahBody">
                </tbody>
            </x-base-table>
        </x-base-body>
    </div>

    <x-base-modal id="modalInputMatakuliah" title="Form Matakuliah" size="md">

        <x-base-form id="formSimpanMatakuliah">
            <input type="hidden" name="id" id="matakuliah_id">

            {{-- Kode Matakuliah --}}
            <div class="col-12 mb-3">
                <label for="kode_mk" class="form-label">
                    Kode Matakuliah <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="kode_mk" name="kode_mk" placeholder="Contoh: MK001">
                <small id="error-kode_mk" class="error-msg text-danger"></small>
            </div>

            {{-- Nama --}}
            <div class="col-12 mb-3">
                <label for="nama_mk" class="form-label">
                    Nama Matakuliah <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nama_mk" name="nama_mk"
                    placeholder="Contoh: Mutia Manissss">
                <small id="error-nama_mk" class="error-msg text-danger"></small>
            </div>

            {{-- SKS --}}
            <div class="col-12 mb-3">
                <label for="sks" class="form-label">
                    SKS <span class="text-danger">*</span>
                </label>
                <input type="number" class="form-control" id="sks" name="sks" placeholder="Contoh: 3"
                    min="1" max="10">
                <small id="error-sks" class="error-msg text-danger"></small>
            </div>
        </x-base-form>

        <x-slot name="footer">
            <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
            <x-base-button id="btnProsesMatakuliah" variant="primary" text="Simpan & Proses" icon="fa-solid fa-save" />
        </x-slot>
    </x-base-modal>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('controllers/matakuliah.controller.js') }}"></script>
@endsection
