@extends('Layouts.Base')

@section('content')
<div class="card">
    <x-base-header title="Manajemen Pengguna" icon="fa-solid fa-users-gear">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnTambahUser">
                <i class="fa fa-plus"></i> Tambah Pengguna
            </button>
        </div>
    </x-base-header>

    <x-base-body>
        <div class="alert alert-info border-0 small mb-4" style="background-color: #e8ebff; border-left: 5px solid #0026ff;">
            <i class="fa-solid fa-circle-info me-1" style="color: #0026ff;"></i>
            Halaman ini digunakan untuk mengelola data Dosen, Admin, dan Kaprodi pada sistem SICICI.
        </div>

        @php
            $headers = ['No', 'NIDN', 'Nama Lengkap', 'Email', 'Role', 'Jabatan', 'Aksi'];
        @endphp

        <x-base-table :headers="$headers" id="userTable">
            <tbody id="userBody">
            </tbody>
        </x-base-table>
    </x-base-body>
</div>

<x-base-modal id="modalInputUser" title="Form Data Pengguna" size="lg">
    <x-base-form id="formSimpanUser">
        <input type="hidden" name="id" id="id_user">

        <div class="d-flex align-items-center mb-3">
            <span class="badge badge-center rounded-pill bg-primary me-2"><i class="fa-solid fa-user small"></i></span>
            <h6 class="mb-0 fw-bold text-primary">Biodata Pengguna</h6>
            <hr class="flex-grow-1 ms-3 opacity-25">
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap">
            </div>

            <div class="col-md-6 mb-3">
                <label for="nidn" class="form-label">NIDN/NUPTK</label>
                <input type="text" class="form-control" id="nidn" name="nidn" placeholder="Contoh: 0912345678">
            </div>

            <div class="col-md-6 mb-3">
                <label for="jabatan" class="form-label">Jabatan Struktural</label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Contoh: Lektor Kepala">
            </div>

            <div class="col-md-6 mb-3">
                <label for="role" class="form-label">Hak Akses (Role) <span class="text-danger">*</span></label>
                <select class="form-select" id="role" name="role">
                    <option value="">-- Pilih Role --</option>
                    <option value="prodi">Kaprodi (Program Studi)</option>
                    <option value="dosen">Dosen</option>
                </select>
            </div>

            <div class="col-md-12 mb-3 d-none" id="container_id_prodi">
                <label for="id_prodi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                <select class="form-select select2" id="id_prodi" name="id_prodi">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach ($prodi as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_prodi }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Tentukan prodi jika pengguna menjabat sebagai Kaprodi.</small>
            </div>
        </div>

        <div class="d-flex align-items-center mb-3">
            <span class="badge badge-center rounded-pill bg-primary me-2"><i class="fa-solid fa-key small"></i></span>
            <h6 class="mb-0 fw-bold text-primary">Informasi Akun</h6>
            <hr class="flex-grow-1 ms-3 opacity-25">
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="email" class="form-label">Email Login <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="contoh@stmikadhiguna.ac.id">
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" placeholder="********">
                <small class="text-muted" id="password_note" style="display:none;">Kosongkan jika tidak ingin mengubah password.</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="********">
            </div>
        </div>
    </x-base-form>

    <x-slot name="footer">
        <x-base-button variant="secondary" data-bs-dismiss="modal" text="Batal" />
        <x-base-button id="btnProsesUser" variant="primary" text="Simpan Pengguna" icon="fa-solid fa-save" />
    </x-slot>
</x-base-modal>
@endsection

@section('scripts')
<script type="module" src="{{ asset('controllers/user.controller.js') }}"></script>
@endsection
