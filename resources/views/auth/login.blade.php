@extends('layouts.auth')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <h3>Member Login</h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="{{ route('register') }}" class="btn btn-link">Register</a>
        </form>
        <hr>
        <a href="{{ route('admin.login') }}">Admin login</a>
    </div>
</div>
@endsection