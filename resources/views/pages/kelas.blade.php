@extends('Layouts.Base')

@section('content')
    <div class="card">

        <x-base-header title="Manajemen Kelas" icon="fa-solid fa-graduation-cap ">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" id="btnTambahKelas">
                    <i class="fa fa-plus"></i> Tambah Kelas
                </button>
            </div>
        </x-base-header>

        <x-base-body>
            <div class="alert alert-info border-0 small mb-4">
                <i class="fa-solid fa-circle-info me-1"></i>
                Halaman ini digunakan untuk mengelola data master Kelas pada STMIK Adhi Guna.
            </div>

            @php
                $headers = ['No', 'Nama Kelas', 'Aksi'];
            @endphp

            <x-base-table :headers="$headers" id="kelasTable">
                <tbody id="kelasBody">
                </tbody>
            </x-base-table>
        </x-base-body>
    </div>

    <x-base-modal id="modalInputKelas" title="Form Kelas" size="md">

        <x-base-form id="formSimpanKelas">
            <input type="hidden" name="id" id="kelas_id">
            
            <div class="col-12 mb-3">
                <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" placeholder="Contoh: TI/SI-1">
            </div>
        </x-base-form>

        <x-slot name="footer">
            <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
            <x-base-button id="btnProsesKelas" variant="primary" text="Simpan & Proses" icon="fa-solid fa-save" />
        </x-slot>
    </x-base-modal>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('controllers/kelas.controller.js') }}"></script>
@endsection
