@extends('layouts/layout')

@section('title', 'index')

@section('content')

<div class="d-flex flex-column flex-center min-h-100px">
    <div class="login-form w-400px">
        <!-- Logo -->
        <div class="login-logo text-center mb-4">
            <img src="{{ asset('assets/media/logos/farmhub-logo.png') }}" alt="FarmHub Logo">
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}"
                       placeholder="Enter your email" required autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                       id="password" name="password" placeholder="Enter your password" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Remember Me Checkbox -->
            <div class="d-flex justify-content-between mb-4">
                <label class="form-check-label" for="remember">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember"
                           {{ old('remember') ? 'checked' : '' }}> Remember me
                </label>
                <a href="" class="text-primary">Forgot Password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-lg btn-block login-btn">Login</button>
        </form>

        <!-- Register Link -->
        {{-- <div class="text-center mt-4">
            <p class="text-muted">Don't have an account? <a href="{{ route('register.form') }}" class="text-primary">Sign up</a></p>
        </div> --}}
    </div>
</div>

@endsection

@push('styles')
 <!-- Additional Styles for Customization -->
 <style>
    .login-page {
        background-image: url('assets/media/bg-login.jpg');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }

    .login-form {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .login-logo img {
        width: 150px;
        margin-bottom: 20px;
    }

    .login-btn {
        background-color: #4CAF50;
        border-color: #4CAF50;
    }
</style>

@endpush
