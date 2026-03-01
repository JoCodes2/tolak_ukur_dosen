@extends('Layouts.Base')

@section('content')
    <div class="card">
        <x-base-header title="Penilaian Mahasiswa" icon="fa-solid fa-graduation-cap">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefresh">
                    <i class="fa fa-sync"></i> Refresh Data
                </button>
            </div>
        </x-base-header>

        <x-base-body>
            <div class="alert alert-custom border-0 small mb-4" style="background-color: #f0f2ff; border-left: 5px solid #696cff;">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-info me-3 fs-4 text-primary"></i>
                    <div>
                        <p class="mb-0 fw-bold text-primary">Petunjuk Penilaian:</p>
                        <span class="text-muted">Klik ikon <strong>Detail Penilaian</strong> pada kolom aksi untuk menginput nilai mahasiswa berdasarkan komponen evaluasi yang telah Anda sinkronkan di Kontrak Perkuliahan.</span>
                    </div>
                </div>
            </div>

            @php
                $headers = ['No', 'Periode', 'Program Studi', 'Kelas', 'Mata Kuliah', 'Aksi'];
            @endphp

            <x-base-table :headers="$headers" id="penilaianTable">
                <tbody id="penilaianBody">
                </tbody>
            </x-base-table>
        </x-base-body>
    </div>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('controllers/penilaian-mahasiswa.controller.js') }}"></script>
@endsection
