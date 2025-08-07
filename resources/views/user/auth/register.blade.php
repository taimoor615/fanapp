@extends('layouts.app')

@section('title', 'Register - Fan App')

@section('content')
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="auth-form-light text-left p-5">
            <div class="brand-logo">
                <img src="{{asset('assets/images/logo.png')}}">
            </div>
            <h4>New here?</h4>
            <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
            <form class="pt-3" method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="team_id" value="3">
                <div class="form-group">
                <input type="text" name="first_name" class="form-control form-control-lg" id="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
                </div>
                <div class="form-group">
                <input type="text" name="last_name" class="form-control form-control-lg" id="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
                </div>
                <div class="form-group">
                <input type="email" name="email" class="form-control form-control-lg" id="email" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Password" required>
                </div>
                <div class="mb-4">
                <div class="form-check">
                    <label class="form-check-label text-muted">
                    <input type="checkbox" name="agreecondition" class="form-check-input"> I agree to all Terms & Conditions </label>
                </div>
                </div>
                <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
                </div>
                <div class="text-center mt-4 font-weight-light"> Already have an account? <a href="{{route('login')}}" class="text-primary">Login</a>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
</div>
@endsection
