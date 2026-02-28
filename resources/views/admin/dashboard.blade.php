@extends('Layouts.Base')

@section('content')
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->nama }}! 🎉</h5>
                        <p class="mb-4">
                            Anda memiliki akses penuh untuk mengelola data akademik dan penilaian dosen di sistem <span class="fw-bold">SICICI</span>.
                        </p>
                        <a href="{{ url('/aktivitas-perkuliahan') }}" class="btn btn-sm btn-outline-primary">Lihat Aktivitas</a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <a href="{{ url('/mahasiswa') }}" class="text-decoration-none">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="badge bg-label-primary p-2"><i class="bx bx-user fs-3"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1 text-muted">Total Mahasiswa</span>
                    <h3 class="card-title mb-2">{{ number_format($total_mahasiswa) }}</h3>
                    <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> MHS</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <a href="{{ url('/user') }}" class="text-decoration-none">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="badge bg-label-info p-2"><i class="bx bx-id-card fs-3"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1 text-muted">Total Dosen</span>
                    <h3 class="card-title mb-2">{{ number_format($total_dosen) }}</h3>
                    <small class="text-info fw-semibold">DSN</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <a href="{{ url('/prodi') }}" class="text-decoration-none">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="badge bg-label-warning p-2"><i class="bx bx-buildings fs-3"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1 text-muted">Program Studi</span>
                    <h3 class="card-title mb-2">{{ number_format($total_prodi) }}</h3>
                    <small class="text-muted fw-semibold">PRODI</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <a href="{{ url('/matakuliah') }}" class="text-decoration-none">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="badge bg-label-success p-2"><i class="bx bx-book fs-3"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1 text-muted">Mata Kuliah</span>
                    <h3 class="card-title mb-2">{{ number_format($total_matakuliah) }}</h3>
                    <small class="text-success fw-semibold">MK</small>
                </div>
            </div>
        </a>
    </div>

    <!-- Charts -->
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Distribusi Mahasiswa per Prodi</h5>
                    <small class="text-muted">Visualisasi data mahasiswa saat ini</small>
                </div>
            </div>
            <div class="card-body">
                <canvas id="prodiChart" style="min-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-md-6 col-lg-4 order-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Aktivitas Terkini</h5>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($recent_activities as $activity)
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="badge bg-label-primary p-2"><i class="bx bx-calendar-event"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <small class="text-muted d-block mb-1">{{ $activity->periode->nama_periode ?? 'Periode N/A' }}</small>
                                <h6 class="mb-0">{{ $activity->prodi->nama_prodi ?? 'Prodi N/A' }}</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                                <span class="text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-muted py-5">
                        <i class="bx bx-info-circle fs-2 d-block mb-2"></i>
                        Belum ada aktivitas
                    </li>
                    @endforelse
                </ul>
                @if(count($recent_activities) > 0)
                <div class="text-center mt-3">
                    <a href="{{ url('/aktivitas-perkuliahan') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .card-hover {
        transition: all 0.3s ease-in-out;
        border: 1px solid transparent;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        border-color: #696cff;
    }
    .card-title {
        color: #566a7f;
    }
</style>

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
                    backgroundColor: 'rgba(105, 108, 255, 0.5)',
                    borderColor: 'rgba(105, 108, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection
@endsection
