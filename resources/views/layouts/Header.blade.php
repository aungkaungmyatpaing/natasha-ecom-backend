<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('/images/default.jpg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('/images/default.jpg') }}" alt="" height="50">
                        </span>
                    </a>

                    <a href="" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('/images/default.jpg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('/images/default.jpg') }}" alt="" height="50">
                        </span>
                    </a>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ auth()->user()->name }}</span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">Admin</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                            class="ri-edit-fill text-muted fs-16 align-middle me-1"></i> <span
                            class="align-middle">Edit Profile</span></a>
                        <a class="dropdown-item" href="{{ route('editPassword') }}"><i
                                class="ri-key-fill text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Change Password</span></a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="return confirm('Are you sure you want to logout?')">
                            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle" data-key="t-logout">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
