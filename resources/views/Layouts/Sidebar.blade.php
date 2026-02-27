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
                        <small class="text-muted fw-semibold d-block" style="font-size: 0.65rem; line-height: 1.2;">
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

    <hr class="my-0 mx-4" style="border-top: 1px solid #e1e1e1;">

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner pt-3">
        <li class="menu-item {{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}">
            <a href="/" class="menu-link">
                <i class="menu-icon fa-solid fa-house"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>

        <li class="menu-item {{ request()->is('prodi*') ? 'active' : '' }}">
            <a href="/prodi" class="menu-link">
                <i class="menu-icon fa-solid fa-graduation-cap"></i>
                <div data-i18n="Program Studi">Program Studi</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('kelas*') ? 'active' : '' }}">
            <a href="/kelas" class="menu-link">
                <i class="menu-icon fa-solid fa-chalkboard-user"></i>
                <div data-i18n="Kelas">Kelas</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('mahasiswa*') ? 'active' : '' }}">
            <a href="/mahasiswa" class="menu-link">
                <i class="menu-icon fa-solid fa-users"></i>
                <div data-i18n="Mahasiswa">Mahasiswa</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('matakuliah*') ? 'active' : '' }}">
            <a href="/matakuliah" class="menu-link">
                <i class="menu-icon fa-solid fa-book"></i>
                <div data-i18n="Matakuliah">Matakuliah</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('periode*') ? 'active' : '' }}">
            <a href="/periode" class="menu-link">
                <i class="menu-icon fa-solid fa-calendar"></i>
                <div data-i18n="Periode">Periode</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Aktivitas & Monitoring</span>
        </li>

        <li class="menu-item {{ request()->is('aktivitas-perkuliahan*') ? 'active' : '' }}">
            <a href="/aktivitas-perkuliahan" class="menu-link">
                <i class="menu-icon fa-solid fa-calendar-check"></i>
                <div data-i18n="Aktivitas">Aktivitas Perkuliahan</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('komponen*') ? 'active' : '' }}">
            <a href="/komponen" class="menu-link">
                <i class="menu-icon fa-solid fa-book"></i>
                <div data-i18n="Aktivitas">Komponen MK</div>
            </a>
        </li>


        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Akun & Management Pegawai</span>
        </li>

        <li class="menu-item {{ request()->is('user*') ? 'active' : '' }}">
            <a href="/user" class="menu-link">
                <i class="menu-icon fa-solid fa-user-gear"></i>
                <div data-i18n="User">Manajemen User</div>
            </a>
        </li>
    </ul>
</aside>
