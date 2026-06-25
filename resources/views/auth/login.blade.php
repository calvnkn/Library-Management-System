@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body m-5">
                <div class="mb-4">
                    <h3 class="text-center">Member Login</h3>
                </div>
                <form class="form-signin" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input autofocus required placeholder="Email address" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input autofocus required placeholder="Password" type="password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a role="button" href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection