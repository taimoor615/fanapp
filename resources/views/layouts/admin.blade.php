<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
    <div class="container-scroller">
        <!-- Top Navigation -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
                </a>
            </div>

            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>

                <div class="search-field d-none d-md-block">
                    <form class="d-flex align-items-center h-100" action="#">
                        <div class="input-group">
                            <div class="input-group-prepend bg-transparent">
                                <i class="input-group-text border-0 mdi mdi-magnify"></i>
                            </div>
                            <input type="text" class="form-control bg-transparent border-0" placeholder="Search...">
                        </div>
                    </form>
                </div>

                <ul class="navbar-nav navbar-nav-right">
                    <!-- Profile Dropdown -->
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-img">
                                <img src="{{ auth()->user()->avatar ?? asset('assets/images/faces/face1.jpg') }}" alt="image">
                                <span class="availability-status online"></span>
                            </div>
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ auth()->guard('admin')->user()->name }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="mdi mdi-logout me-2 text-primary"></i> Signout
                                </button>
                            </form>
                        </div>
                    </li>

                    <!-- Notifications -->
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                            <i class="mdi mdi-bell-outline"></i>
                            <span class="count-symbol bg-danger"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <h6 class="p-3 mb-0">Notifications</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="mdi mdi-calendar"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">New user registered</h6>
                                    <p class="text-gray ellipsis mb-0">Check new fan registrations</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                        </div>
                    </li> --}}

                    <li class="nav-item nav-logout d-none d-lg-block">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">
                                <i class="mdi mdi-power"></i>
                            </button>
                        </form>
                    </li>
                </ul>

                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <!-- Profile Section -->
                    <li class="nav-item nav-profile">
                        <a href="#" class="nav-link">
                            <div class="nav-profile-image">
                                <img src="{{ auth()->guard('admin')->user()->avatar ?? asset('assets/images/faces/face1.jpg') }}" alt="profile" />
                                <span class="login-status online"></span>
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                <span class="font-weight-bold mb-2">{{ auth()->guard('admin')->user()->name }}</span>
                                <span class="text-secondary text-small">{{ auth()->guard('admin')->user()->name }}</span>
                            </div>
                            <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                        </a>
                    </li>

                    <!-- Dashboard -->
                    <li class="nav-item {{ Route::currentRouteName() === 'admin.dashboard' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>

                    <!-- Teams Management -->
                    <li class="nav-item {{ str_starts_with(Route::currentRouteName(), 'admin.teams') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.teams.index') }}">
                            <span class="menu-title">Teams</span>
                            <i class="mdi mdi-account-group menu-icon"></i>
                        </a>
                    </li>

                    <!-- Users Management -->
                    @php
                        $isUserRoute = request()->routeIs('admin.users.index') || request()->routeIs('admin.users.create') || request()->routeIs('admin.users.edit');
                    @endphp
                    <li class="nav-item {{ $isUserRoute ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <span class="menu-title">Users</span>
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                        </a>
                    </li>

                    <!-- News Management -->
                    <li class="nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#news-menu" aria-expanded="false" aria-controls="news-menu">
                            <span class="menu-title">News & Posts</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-newspaper menu-icon"></i>
                        </a>
                        <div class="collapse" id="news-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.news.index') }}">All Posts</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.news.create') }}">Add New Post</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Games Management -->
                    <li class="nav-item {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#games-menu" aria-expanded="false" aria-controls="games-menu">
                            <span class="menu-title">Games</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-soccer menu-icon"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.games.*') ? 'show' : '' }}" id="games-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.games.index') }}">All Games</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.games.create') }}">Add Game</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Rewards Management -->
                    <li class="nav-item {{ request()->routeIs('admin.rewards.*') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#rewards-menu" aria-expanded="false" aria-controls="rewards-menu">
                            <span class="menu-title">Rewards</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-gift menu-icon"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.rewards.*') ? 'show' : '' }}" id="rewards-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.rewards.index') }}">Rewards Catalog</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.rewards.redemptions') }}">Redemptions</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Fan Photos -->
                    <li class="nav-item {{ request()->routeIs('admin.fan-photos.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.fan-photos.index') }}">
                            <span class="menu-title">Fan Photos</span>
                            <i class="mdi mdi-camera menu-icon"></i>
                        </a>
                    </li>

                    <!-- Trivia -->
                    <li class="nav-item {{ request()->routeIs('admin.trivia.*') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#trivia-menu" aria-expanded="false" aria-controls="trivia-menu">
                            <span class="menu-title">Trivia</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-help-circle menu-icon"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.trivia.*') ? 'show' : '' }}" id="trivia-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.trivia.index') }}">Questions</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.trivia.create') }}">Add Question</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>

                <!-- Footer -->
                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">
                            Copyright © {{ date('Y') }} Fan App. All rights reserved.
                        </span>
                        <span class="float-none float-sm-end mt-1 mt-sm-0 text-end">
                            Made with <i class="mdi mdi-heart text-danger"></i>
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('assets/vendors/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('assets/js/misc.js')}}"></script>
    <script src="{{asset('assets/js/settings.js')}}"></script>
    <script src="{{asset('assets/js/todolist.js')}}"></script>
    <script src="{{asset('assets/js/jquery.cookie.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
    <!-- End custom js for this page -->
    <!-- Game Index JS -->
    @stack('scripts')
  </body>
</html>
