@extends('layouts/layout') <!-- or whatever layout you're using -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verify Your Email Address</div>

                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Thanks for signing up!</h4>
                        <p>A verification link has been sent to your email address.</p>
                    </div>

                    <p>Before proceeding, please check your email for a verification link.</p>
                    <p>If you did not receive the email, click the button below to request another.</p>

                    <form method="POST" action="" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if(isset($openInNewTab) && $openInNewTab)
    @push('scripts')
    <script>
        // Open the verification notice page in a new tab if coming from registration
        window.onload = function() {
            window.open('{{ $verificationUrl }}', '_blank');
        };
    </script>
    @endpush
@endif
