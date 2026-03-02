
@extends('Layouts.Base')

@section('content')
<div class="card">

    <x-base-header title="Manajemen Program Studi" icon="fa-solid fa-graduation-cap ">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnTambahProdi">
                <i class="fa fa-plus"></i> Tambah Program Studi
            </button>
        </div>
    </x-base-header>

    <x-base-body>
        <div class="alert alert-info border-0 small mb-4">
            <i class="fa-solid fa-circle-info me-1"></i>
            Halaman ini digunakan untuk mengelola data master Program Studi pada STMIK Adhi Guna.
        </div>

        @php
            $headers = ['No', 'Kode Prodi', 'Nama Program Studi', 'Aksi'];
        @endphp

        <x-base-table :headers="$headers" id="prodiTable">
            <tbody id="prodiBody">
            </tbody>
        </x-base-table>
    </x-base-body>
</div>

<x-base-modal id="modalInputProdi" title="Form Program Studi" size="md">

    <x-base-form id="formSimpanProdi">
        <input type="hidden" name="id" id="id">

        <div class="col-12 mb-3">
            <label for="kode_prodi" class="form-label">Kode Program Studi <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="kode_prodi" name="kode_prodi"
                   placeholder="Contoh: TI" >
        </div>

        <div class="col-12 mb-3">
            <label for="nama_prodi" class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama_prodi" name="nama_prodi"
                   placeholder="Contoh: Teknik Informatika" >
        </div>
    </x-base-form>

    <x-slot name="footer">
        <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
        <x-base-button id="btnProsesProdi" variant="primary" text="Simpan & Proses" icon="fa-solid fa-save" />
    </x-slot>
</x-base-modal>
@endsection

@section('scripts')
<script type="module" src="{{ asset('controllers/prodi.controller.js') }}"></script>
@endsection
