@extends('Layouts.Base')

@section('content')
    <div class="card">
        <x-base-header title="Aktivitas Perkuliahan" icon="fa-solid fa-calendar-check">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" id="btnTambahAktivitas">
                    <i class="fa fa-plus"></i> Tambah Aktivitas
                </button>
            </div>
        </x-base-header>

        <x-base-body>
            <div class="alert alert-info border-0 small mb-4" style="background-color: #e8ebff; border-left: 5px solid #0026ff;">
                <i class="fa-solid fa-circle-info me-1" style="color: #0026ff;"></i>
                Halaman ini digunakan untuk membuka wadah perkuliahan. Anda dapat mengelola dosen pengajar dan peserta mahasiswa setelah aktivitas dibuat.
            </div>

            @php
                $headers = ['No', 'Periode', 'Program Studi', 'Kelas', 'Total Dosen', 'Total Mhs', 'Aksi'];
            @endphp

            <x-base-table :headers="$headers" id="aktivitasTable">
                <tbody id="aktivitasBody">
                </tbody>
            </x-base-table>
        </x-base-body>
    </div>

    <x-base-modal id="modalInputAktivitas" title="Form Aktivitas Perkuliahan" size="md">
        <x-base-form id="formSimpanAktivitas">
            <input type="hidden" name="id" id="id_aktivitas">

            <div class="col-12 mb-3">
                <label for="id_periode" class="form-label">Periode Akademik <span class="text-danger">*</span></label>
                <select class="form-select select2" id="id_periode" name="id_periode">
                    <option value="">-- Pilih Periode --</option>
                    </select>
            </div>

            <div class="col-12 mb-3">
                <label for="id_prodi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                <select class="form-select select2" id="id_prodi" name="id_prodi">
                    <option value="">-- Pilih Program Studi --</option>
                    </select>
            </div>

            <div class="col-12 mb-3">
                <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                <select class="form-select select2" id="id_kelas" name="id_kelas">
                    <option value="">-- Pilih Kelas --</option>
                    </select>
            </div>
        </x-base-form>

        <x-slot name="footer">
            <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
            <x-base-button id="btnProsesAktivitas" variant="primary" text="Buka Perkuliahan" icon="fa-solid fa-door-open" />
        </x-slot>
    </x-base-modal>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('controllers/aktivitas.controller.js') }}"></script>
@endsection
