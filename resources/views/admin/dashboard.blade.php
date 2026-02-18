@extends('Layouts.Base')

@section('content')
    <section class="maintenance d-flex align-items-center justify-content-center"
        style="min-height: 100vh; background: #f8f9fa;">
        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center text-center">
                <div class="col-md-8">
                    <div class="maintenance-img-wrapper mb-4">
                        <img src="{{ asset('assets/assets/home2.png') }}" class="img-fluid floating-anim"
                            style="max-width: 250px; filter: grayscale(20%);" alt="Maintenance Mode">
                    </div>

                    <span class="badge rounded-pill bg-light text-primary px-3 py-2 mb-3 shadow-sm border">
                        <i class="fas fa-tools me-2"></i> Pemeliharaan Sistem
                    </span>

                    <h1 class="font-kanit sky display-5 fw-bold mb-3">
                        Mohon Maaf, Kami Sedang Berbenah
                    </h1>

                    <p class="font-popins text-muted fs-5 mb-4">
                        Halo <strong>@auth {{ auth()->user()->name }}
                            @else
                            Jemaat @endauth
                        </strong>,
                        saat ini Sistem Informasi Gereja Kalvari Palu sedang dalam proses pembaruan teknis
                        untuk meningkatkan layanan informasi jemaat kami.
                    </p>

                    <div class="progress mb-4 mx-auto" style="height: 10px; max-width: 400px; border-radius: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-sky" role="progressbar"
                            style="width: 75%; background-color: #0ea5e9;"></div>
                    </div>

                    <div class="info-footer font-popins">
                        <p class="small text-secondary mb-0">Kami akan segera kembali. Silahkan cek secara berkala.</p>
                        <hr class="w-25 mx-auto">
                        <div class="social-links mt-3">
                            <span class="text-muted small">Hubungi kami:</span><br>
                            <a href="#" class="text-decoration-none mx-2 sky"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-decoration-none mx-2 sky"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .sky {
            color: #0ea5e9;
        }

        .bg-sky {
            background-color: #0ea5e9;
        }

        /* Animasi Mengapung */
        .floating-anim {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .maintenance {
            background: linear-gradient(135deg, #ffffff 0%, #e0f2fe 100%);
        }
    </style>
@endsection
