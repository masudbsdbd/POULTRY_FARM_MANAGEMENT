<!-- Topbar Start -->
<div class="navbar-custom">
    <div class="topbar">
        <div class="topbar-menu d-flex align-items-center gap-1">

            <!-- LOGO -->
            <div class="logo-box">
                <a href="" class="logo-light">
                    <img src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="logo" class="logo-lg">
                    <img src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="small logo" class="logo-sm">
                </a>
                <a href="" class="logo-dark">
                    <img src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="dark logo" class="logo-lg">
                    <img src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="small logo" class="logo-sm">
                </a>
            </div>
            @if (request()->routeIs('pos.index'))

            @else
            <button class="button-toggle-menu">
                <i class="mdi mdi-menu"></i>
            </button>
            
            @endif

            <div class="dropdown d-none d-xl-block">
            @if (request()->routeIs('pos.index'))
            @else
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    Create New
                    <i class="mdi mdi-chevron-down ms-1"></i>
                </a>
                @endif
                <div class="dropdown-menu ">
                    <!-- item-->
                    <!-- <a href="javascript:void(0);" class="dropdown-item">
                        <i class="fe-briefcase me-1"></i>
                        <span>New Projects</span>
                    </a> -->

                    <!-- item-->
                    @can('user-create')
                    <a href="{{ route('users.create') }}" class="dropdown-item">
                        <i class="fe-user me-1"></i>
                        <span>Create Users</span>
                    </a>
                    @endcan

                    <!-- item-->
                    <!-- <a href="javascript:void(0);" class="dropdown-item">
                        <i class="fe-bar-chart-line- me-1"></i>
                        <span>Revenue Report</span>
                    </a> -->

                    <!-- item-->
                    <a href="{{ route('setting.general') }}" class="dropdown-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- <div class="dropdown-divider"></div> -->

                    <!-- item-->
                    <!-- <a href="javascript:void(0);" class="dropdown-item">
                        <i class="fe-headphones me-1"></i>
                        <span>Help & Support</span>
                    </a> -->

                </div>
            </div>
        </div>

        {{-- Right part of topnav --}}
        <ul class="topbar-menu d-flex align-items-center">

            <!-- Fullscreen Button -->
            <li class="d-none d-md-inline-block">
                <a class="nav-link waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize font-22"></i>
                </a>
            </li>

            <!-- Light/Dark Mode Toggle Button -->
            <li class="d-none d-sm-inline-block">
                <div class="nav-link waves-effect waves-light" id="light-dark-mode">
                    <i class="ri-moon-line font-22"></i>
                </div>
            </li>

            {{-- <li class="d-none d-sm-inline-block">
                <div class="nav-link waves-effect waves-light" id="light-dark-mode">
                    @if (request()->routeIs('pos.index'))
                        <a href="{{ route('dashboard') }}" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-view-dashboard me-1"></i><strong>Dashboard</strong></a>
                    @else
                        <a href="{{ route('pos.index') }}" class="btn btn-primary waves-effect waves-light"><strong>POS</strong></a>
                    @endif
                </div>
            </li> --}}

            <!-- User Dropdown -->
            <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('uploads/profile/' . gs()->user_image) }}" alt="user-image" class="rounded-circle">
                    <span class="ms-1 d-none d-md-inline-block">
                    {{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown " data-popper-placement="bottom-end"
                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 72px);">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    {{-- <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock"></i>
                        <span>Lock Screen</span>
                    </a>--}}

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    {{-- <form method="POST" action="{{ route('logout') }}" style="margin-block-end: 0px;">
                    @csrf --}}
                    <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>
                    {{-- </form> --}}

                </div>
            </li>

        </ul>

    </div>

    <div class="container-fluid">

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>

        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- end Topbar -->
