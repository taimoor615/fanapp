<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Welcome')</title>
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
    <style>
        .feature-icon{
            width: 50px;
            height: 50px;
        }
    </style>
  </head>
  <body>
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Fan App" class="img-fluid mb-4">
                    <h1 class="display-4 font-weight-bold mb-4">
                        Join Your Team's Ultimate Fan Experience
                    </h1>
                    <p class="lead mb-5">
                        Earn points, win rewards, stay connected with your favorite team, and be part of an amazing fan community.
                    </p>
                    <div class="d-flex flex-column flex-sm-row">
                        <a href="{{ route('register') }}" class="btn btn-gradient-primary btn-lg me-sm-3 mb-3 mb-sm-0">
                            <i class="mdi mdi-account-plus me-2"></i>Join Now
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-gradient-primary btn-lg me-sm-3 mb-3 mb-sm-0">
                            <i class="mdi mdi-login me-2"></i>Sign In
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center">
                        <img src="{{ asset('assets/images/welcome/hero-illustration.svg') }}" alt="Fan App" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 class="display-5 font-weight-bold">What You Can Do</h2>
                    <p class="lead text-muted">Experience the ultimate fan engagement platform</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-gradient-primary rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-star"></i>
                            </div>
                            <h5>Earn Points</h5>
                            <p class="text-muted">Attend games, share posts, and engage with your team to earn points.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-gradient-success rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-gift"></i>
                            </div>
                            <h5>Win Rewards</h5>
                            <p class="text-muted">Redeem your points for exclusive merchandise and experiences.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-gradient-warning rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-newspaper"></i>
                            </div>
                            <h5>Stay Updated</h5>
                            <p class="text-muted">Get the latest news, game updates, and exclusive content.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    </body>
</html>
