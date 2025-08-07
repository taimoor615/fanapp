<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'User Dashboard')</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}">
    <!-- endinject -->

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

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
                <a class="navbar-brand brand-logo" href="{{ route('user.dashboard') }}">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('user.dashboard') }}">
                    <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
                </a>
            </div>

            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>

                <!-- Points Display -->
                <div class="search-field d-none d-md-block">
                    <div class="d-flex align-items-center h-100">
                        <div class="points-display bg-gradient-primary px-3 py-2 rounded text-white">
                            <i class="mdi mdi-star me-1"></i>
                            <span>{{ auth()->user()->total_points }} Points</span>
                        </div>
                    </div>
                </div>

                <ul class="navbar-nav navbar-nav-right">
                    <!-- Profile Dropdown -->
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-img">
                                <img src="{{ auth()->user()->avatar ? asset("storage/avatars/" . auth()->user()->avatar) : asset("assets/images/avatars/default-user.png") }}" alt="image">
                                <span class="availability-status online"></span>
                            </div>
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ auth()->user()->full_name }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ route('user.profile') }}">
                                <i class="mdi mdi-account me-2 text-success"></i> Profile
                            </a>
                            {{-- <a class="dropdown-item" href="{{ route('user.settings') }}">
                                <i class="mdi mdi-cog me-2 text-primary"></i> Settings
                            </a> --}}
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('user.logout') }}">
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
                            <span class="count-symbol bg-warning"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <h6 class="p-3 mb-0">Notifications</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="mdi mdi-gift"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Points Earned!</h6>
                                    <p class="text-gray ellipsis mb-0">You earned 50 points for sharing a post</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                        </div>
                    </li> --}}
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
                        <a href="{{ route('user.profile') }}" class="nav-link">
                            <div class="nav-profile-image">
                                <img src="{{ auth()->user()->avatar ? asset("storage/avatars/" . auth()->user()->avatar) : asset("assets/images/avatars/default-user.png") }}" alt="image">
                                <span class="login-status online"></span>
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                <span class="font-weight-bold mb-2">{{ auth()->user()->full_name }}</span>
                                <span class="text-secondary text-small">Level {{ auth()->user()->current_level }} Fan</span>
                            </div>
                            <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                        </a>
                    </li>

                    @php
                        $currentRoute = request()->route() ? request()->route()->getName() : '';
                        $currentUrl = request()->url();
                    @endphp

                    <!-- Dashboard -->
                    <li class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.dashboard') }}">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>

                    <!-- News Feed -->
                    <li class="nav-item {{ request()->is('user/news') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.news.index') }}">
                            <span class="menu-title">News Feed</span>
                            <i class="mdi mdi-newspaper menu-icon"></i>
                        </a>
                    </li>

                    <!-- Games -->
                    <li class="nav-item {{ request()->is('user/games/index')  ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.games.index') }}">
                            <span class="menu-title">Games</span>
                            <i class="mdi mdi-soccer menu-icon"></i>
                        </a>
                    </li>

                    <!-- Rewards -->
                    <li class="nav-item {{ request()->is('user/rewards/index') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#rewards-menu" aria-expanded="false" aria-controls="rewards-menu">
                            <span class="menu-title">Rewards</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-gift menu-icon"></i>
                        </a>
                        <div class="collapse {{ request()->is('user/rewards/*') ? 'show' : '' }}" id="rewards-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.rewards.index') }}">Earn Points</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.rewards.catalog') }}">Redeem Rewards</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.rewards.history') }}">My Redemptions</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Fan Cam -->
                    <li class="nav-item {{ request()->routeIs('user.fan-photos.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.fan-photos.index') }}">
                            <span class="menu-title">Fan Cam</span>
                            <i class="mdi mdi-camera menu-icon"></i>
                        </a>
                    </li>

                    <!-- Trivia Games -->
                    <li class="nav-item {{ request()->routeIs('user.trivia.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.trivia.index') }}">
                            <span class="menu-title">Trivia</span>
                            <i class="mdi mdi-help-circle menu-icon"></i>
                        </a>
                    </li>

                    <!-- Leaderboard -->
                    <li class="nav-item {{ request()->routeIs('user.leaderboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.leaderboard') }}">
                            <span class="menu-title">Leaderboard</span>
                            <i class="mdi mdi-trophy menu-icon"></i>
                        </a>
                    </li>

                    <!-- Merchandise -->
                    <li class="nav-item {{ request()->routeIs('user.store.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.store.index') }}">
                            <span class="menu-title">Team Store</span>
                            <i class="mdi mdi-shopping menu-icon"></i>
                        </a>
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
                            Copyright © {{ date('Y') }} {{ auth()->user()->team->name ?? 'Fan App' }}. All rights reserved.
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
    <!-- jQuery (Required by Owl Carousel) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    @stack('owlcarousel')
  </body>
</html>
