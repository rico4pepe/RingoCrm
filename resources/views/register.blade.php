@extends('layouts/layout')

@section('title', 'Register')

@section('content')

@section('content')
<div class="d-flex flex-column flex-center min-h-100px">
    <div class="login-form w-400px">
        <!-- Logo -->
        <div class="login-logo text-center mb-4">
            Ringo CRM
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <!-- Username Field -->
            <div class="mb-4">
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                       id="username" name="name" value="{{ old('name') }}"
                       placeholder="Enter your username" required autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>


            <div class="mb-4">
                <label for="role" class="form-label">Role</label>
                <select  class="form-control form-control-lg @error('role') is-invalid @enderror"
                       id="role" name="role" value="{{ old('role') }}">
                          <option value="">Select a role</option>
                       <option value="Admin">Admin</option>
                       <option value="others">Others</option>
                </select>

                @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}"
                       placeholder="Enter your email" required>
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

            <!-- Confirm Password Field -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control form-control-lg"
                       id="password_confirmation" name="password_confirmation"
                       placeholder="Confirm your password" required>
            </div>

            <!-- Registration Button -->
            <button type="submit" class="btn btn-lg btn-block login-btn">Register</button>
        </form>

        <!-- Login Link -->
        <div class="text-center mt-4">
            <p class="text-muted">Already have an account? <a href="{{ route('login') }}" class="text-primary">Sign in</a></p>
        </div>
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
