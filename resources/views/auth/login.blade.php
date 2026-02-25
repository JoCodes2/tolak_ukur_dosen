<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Sistem Tolak Ukur Dosen</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script>
        let appUrl = '{{ env('APP_URL') }}';
    </script>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --bg-gradient: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        }

        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            height: 100vh;
            background: var(--bg-gradient);
            overflow: hidden;
        }

        .login-wrapper {
            display: flex;
            height: 100vh;
        }

        /* LEFT SIDE - ACADEMIC BRANDING */
        .branding-section {
            flex: 1.2;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(25px);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
        }

        .brand-logo {
            width: 90px;
            margin-bottom: 20px;
            filter: drop-shadow(0 5px 20px rgba(0, 0, 0, 0.3));
        }

        .brand-title {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            opacity: 0.85;
            font-weight: 300;
            max-width: 420px;
            margin: 15px auto 30px;
        }

        .brand-image {
            width: 300px;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            transition: 0.4s ease;
        }

        .brand-image:hover {
            transform: translateY(-8px) scale(1.03);
        }

        /* RIGHT SIDE */
        .login-section {
            flex: 1;
            background: #f4f6fb;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 45px;
            border-radius: 28px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }

        .login-header h2 {
            font-weight: 700;
            color: #1e293b;
        }

        .login-header p {
            color: #64748b;
            margin-bottom: 30px;
        }

        .input-group-text {
            background-color: #f1f5f9;
            border-right: none;
            border-radius: 14px 0 0 14px;
            color: #64748b;
        }

        .modern-input {
            border-radius: 0 14px 14px 0 !important;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-left: none;
            background-color: #f8fafc;
            transition: 0.3s;
        }

        .modern-input:focus {
            background-color: #fff;
            border-color: #1e3c72;
            box-shadow: none;
        }

        .btn-login-modern {
            width: 100%;
            background: var(--primary-gradient);
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 600;
            color: white;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-login-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 60, 114, 0.4);
        }

        .forgot-link {
            font-size: 0.85rem;
            text-decoration: none;
            color: #1e3c72;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            body {
                overflow: auto;
            }

            .login-wrapper {
                flex-direction: column;
            }

            .branding-section {
                display: none;
            }

            .login-section {
                height: 100vh;
            }

            .login-card {
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">

        <!-- BRANDING -->
        <div class="branding-section">
            <div class="text-center">
                <img src="{{ asset('assets/img/logo-kampus.png') }}" class="brand-logo">
                <h1 class="brand-title">Sistem Tolak Ukur Dosen</h1>
                <p class="brand-subtitle">
                    Platform Monitoring & Evaluasi Kinerja Dosen
                    untuk Mendukung Mutu Akademik dan Akreditasi Institusi.
                </p>
                <img src="{{ asset('assets/img/ilustrasi-akademik.jpg') }}" class="brand-image">
            </div>
        </div>

        <!-- LOGIN -->
        <div class="login-section">
            <div class="login-card">
                <div class="login-header">
                    <h2>Selamat Datang 👨‍🏫</h2>
                    <p>Silakan login untuk mengakses sistem evaluasi kinerja dosen.</p>
                </div>

                <form id="formLogin">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Institusi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control modern-input"
                                placeholder="nama@kampus.ac.id">
                        </div>
                        <small id="error-email" class="text-danger"></small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="passwordInput" class="form-control modern-input"
                                placeholder="••••••••">
                        </div>
                        <small id="error-password" class="text-danger"></small>
                    </div>

                    <button type="submit" class="btn btn-login-modern" id="btnLogin">
                        <span id="btnText">MASUK KE SISTEM</span>
                        <div id="btnLoader" class="spinner-border spinner-border-sm d-none"></div>
                    </button>
                </form>

                <div class="text-center mt-5">
                    <p class="small text-muted">
                        &copy; {{ date('Y') }} Sistem Penjaminan Mutu Akademik
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        function loadingAllert(title = 'Memproses...') {
            $('#btnText').addClass('d-none');
            $('#btnLoader').removeClass('d-none');
            $('#btnLogin').prop('disabled', true);

            Swal.fire({
                title: title,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }

        function resetBtnLogin() {
            $('#btnText').removeClass('d-none');
            $('#btnLoader').addClass('d-none');
            $('#btnLogin').prop('disabled', false);
        }

        function successAlert(title = 'Login Berhasil') {
            resetBtnLogin();
            return Swal.fire({
                icon: 'success',
                title: title,
                timer: 1500,
                showConfirmButton: false
            });
        }

        function errorAlert(text = 'Email atau Password salah') {
            resetBtnLogin();
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: text
            });
        }
    </script>

    <script type="module" src="{{ asset('controllers/login.controller.js') }}"></script>

</body>

</html>
