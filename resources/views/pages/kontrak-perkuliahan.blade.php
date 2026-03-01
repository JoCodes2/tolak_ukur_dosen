@extends('Layouts.Base')

@section('content')
    <div class="card">
        <x-base-header title="Kontrak Perkuliahan" icon="fa-solid fa-file-signature">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefresh">
                    <i class="fa fa-sync"></i> Refresh
                </button>
            </div>
        </x-base-header>

        <x-base-body>
            <div class="alert alert-info border-0 small mb-4">
                <i class="fa-solid fa-circle-info me-1"></i>
                Berikut adalah daftar mata kuliah yang Anda ampu. Silakan klik ikon <strong><i class="fa fa-eye"></i> Lihat Detail</strong> untuk mengatur <strong>Bobot Penilaian</strong> (Tugas, UTS, UAS, dll) sebagai bagian dari aktivitas <strong>beri rating</strong> untuk perhitungan CF.
            </div>

            @php
                $headers = ['No', 'Periode', 'Prodi', 'Kelas', 'Mata Kuliah', 'SKS', 'Aksi'];
            @endphp

            <x-base-table :headers="$headers" id="kontrakTable">
                <tbody id="kontrakBody">
                </tbody>
            </x-base-table>
        </x-base-body>
    </div>
@endsection

@section('scripts')
    <script>
        const userRole = "{{ auth()->user()->role }}";
        const userId = "{{ auth()->user()->id }}";
    </script>
    <script type="module" src="{{ asset('controllers/kontrak-perkuliahan.controller.js') }}"></script>
@endsection
