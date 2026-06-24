@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<h3>My Profile</h3>

<form method="POST" action="{{ route('profile.update') }}" class="mb-5">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $member->name) }}">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $member->email) }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control" value="{{ old('address', $member->address) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Contact Number</label>
        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $member->contact_number) }}">
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

<hr>

<h4>Change Password</h4>
<form method="POST" action="{{ route('profile.password') }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
        @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="new_password_confirmation" class="form-control">
    </div>
    <button type="submit" class="btn btn-warning">Change Password</button>
</form>
@endsection