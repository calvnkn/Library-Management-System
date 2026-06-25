@extends('layouts.auth')
@section('title', 'Admin Login')
@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body m-5">
                <div class="mb-4">
                    <h3 class="text-center">Admin Login</h3>
                </div>
                <form method="POST" action="{{ route('admin.login') }}">
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
                </form>
            </div>
        </div>
    </div>
</div>
@endsection