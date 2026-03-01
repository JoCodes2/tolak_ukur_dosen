@extends('Layouts.Base')

@section('content')
<div class="row">
    {{-- Header Selamat Datang --}}
    <div class="col-lg-12 mb-4 order-0">
        <div class="card shadow-none border-0" style="background: linear-gradient(135deg, #e8ebff 0%, #ffffff 100%); border-left: 5px solid #696cff !important;">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold">Monitoring SICICI STMIK Adhi Guna 🎓</h5>
                        <p class="mb-4">
                            Selamat Datang, <span class="fw-bold">{{ auth()->user()->nama }}</span>. <br>
                            Anda berada di dashboard sistem monitoring penilaian mahasiswa. Pantau distribusi nilai dan aktivitas pengajaran secara real-time.
                        </p>
                        <a href="{{ url('/penilaian-mahasiswa') }}" class="btn btn-sm btn-primary shadow-sm">Mulai Penilaian</a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="Dashboard Illustration" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card card-hover shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="badge bg-label-primary p-2"><i class="bx bx-user fs-3"></i></span>
                    </div>
                    <span class="fw-semibold d-block text-muted">Mahasiswa</span>
                </div>
                <h3 class="card-title mb-1 fw-bold">{{ number_format($total_mahasiswa) }}</h3>
                <small class="text-muted">Total Terdaftar</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card card-hover shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="badge bg-label-info p-2"><i class="bx bx-id-card fs-3"></i></span>
                    </div>
                    <span class="fw-semibold d-block text-muted">Dosen</span>
                </div>
                <h3 class="card-title mb-1 fw-bold">{{ number_format($total_dosen) }}</h3>
                <small class="text-muted">Tenaga Pengajar</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card card-hover shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="badge bg-label-warning p-2"><i class="bx bx-buildings fs-3"></i></span>
                    </div>
                    <span class="fw-semibold d-block text-muted">Program Studi</span>
                </div>
                <h3 class="card-title mb-1 fw-bold">{{ number_format($total_prodi) }}</h3>
                <small class="text-muted">Fakultas & Prodi</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card card-hover shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="badge bg-label-success p-2"><i class="bx bx-book fs-3"></i></span>
                    </div>
                    <span class="fw-semibold d-block text-muted">Mata Kuliah</span>
                </div>
                <h3 class="card-title mb-1 fw-bold">{{ number_format($total_matakuliah) }}</h3>
                <small class="text-muted">Total Kurikulum</small>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="col-12 col-lg-8 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 fw-bold">Distribusi Mahasiswa per Program Studi</h5>
            </div>
            <div class="card-body">
                <canvas id="prodiChart" style="min-height: 315px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Aktivitas Terkini --}}
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header border-bottom mb-3">
                <h5 class="card-title m-0 fw-bold">Log Aktivitas Penilaian</h5>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($recent_activities as $activity)
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="badge bg-label-primary p-2">
                                <i class="bx bx-check-circle"></i>
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                {{-- PERBAIKAN DISINI: Menggunakan $activity->periode->nama --}}
                                <small class="text-muted d-block mb-1">
                                    {{ $activity->periode->nama ?? 'Periode N/A' }}
                                    {{ $activity->periode ? '- '.$activity->periode->tahun_ajaran : '' }}
                                </small>
                                <h6 class="mb-0 fw-semibold">{{ $activity->prodi->nama_prodi ?? 'Prodi N/A' }}</h6>
                            </div>
                            <div class="user-progress">
                                <small class="fw-bold text-primary">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-muted py-5">
                        <i class="bx bx-history fs-1 d-block mb-2"></i>
                        Belum ada aktivitas penilaian.
                    </li>
                    @endforelse
                </ul>
                @if(count($recent_activities) > 0)
                <div class="text-center mt-3 pt-2">
                    <a href="{{ url('/aktivitas-perkuliahan') }}" class="btn btn-sm btn-outline-primary w-100">Lihat Semua Log</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .card-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    .bg-label-primary { background-color: #e7e7ff !important; color: #696cff !important; }
    .bg-label-info { background-color: #d7f5fc !important; color: #03c3ec !important; }
    .bg-label-warning { background-color: #fff2e2 !important; color: #ffab00 !important; }
    .bg-label-success { background-color: #e8fad2 !important; color: #71dd37 !important; }
</style>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('prodiChart').getContext('2d');
        const prodiData = @json($prodi_distribution);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: prodiData.map(item => item.nama_prodi),
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: prodiData.map(item => item.count),
                    backgroundColor: 'rgba(105, 108, 255, 0.7)',
                    borderColor: 'rgba(105, 108, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
