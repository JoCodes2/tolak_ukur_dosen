        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo py-3">
                <a href="/home" class="app-brand-link d-flex align-items-center text-decoration-none">
                    <span
                        class="app-brand-logo demo bg-white shadow-sm p-2 rounded-3 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('assets/assets/img/stmikadhigunaicon.svg') }}" alt="Logo" class="img-fluid"
                            style="width: 38px; height: 38px; object-fit: contain;">
                    </span>

                    <span class="app-brand-text ms-3">
                        <div class="lh-1">
                            <span class="fw-bold text-uppercase tracking-wider"
                                style="color: #0026ff; font-size: 1.2rem; letter-spacing: 1px;">
                                SICICI
                            </span>
                            <div class="mt-1">
                                <small class="text-muted fw-semibold d-block"
                                    style="font-size: 0.65rem; line-height: 1.2;">
                                    STMIK ADHI GUNA
                                </small>
                            </div>
                        </div>
                    </span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 30px; height: 30px;">
                        <i class="bx bx-chevron-left bx-sm text-white"></i>
                    </div>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="/" class="menu-link">
                        <i class="menu-icon fa-solid fa-house"></i>
                        <div data-i18n="Analytics">Dashboard</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('user') ? 'active' : '' }}">
                    <a href="/user" class="menu-link">
                        <i class="menu-icon fa-solid fa-user"></i>
                        <div data-i18n="Analytics">User</div>
                    </a>
                </li>
            </ul>

            <style>
                /* Menghilangkan shadow teks yang berat dan menggantinya dengan clean look */
                .app-brand-text {
                    transition: all 0.3s ease;
                }

                .app-brand-link:hover .app-brand-logo {
                    transform: scale(1.05);
                    transition: 0.3s;
                }

                .tracking-wider {
                    letter-spacing: 0.05em;
                }
            </style>
        </aside>
        <!-- / Menu -->
