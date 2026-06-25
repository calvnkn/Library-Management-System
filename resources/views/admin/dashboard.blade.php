@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h2 class="mb-0">Admin Dashboard</h2>
            <p class="text-muted mb-0">Welcome, <strong>{{ session('admin_name') }}</strong>.</p>
        </div>
        <span class="text-muted small">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
    </div>

    <hr class="mb-4">
    <div class="row g-3 mb-4">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class=" card-header bg-danger text-white m-1">
                    <b>USER PENALTIES</b>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text fw-light"> Reported Penalties and Fines </p>
                    <div class="container">
                        <a class="btn btn-danger " href="{{ route('admin.defaulters.index') }}">Defaulters</a>
                        <a class="btn btn-danger " href="{{ route('admin.fines.index') }}">Fines</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class=" card-header bg-secondary text-white m-1">
                    <b>PENDING REQUESTS</b>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text fw-light">User Requests </p>
                    <div class="container">
                        <a class="btn btn-secondary " href="{{ route('admin.requests.index') }}">Manage</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class=" card-header bg-secondary text-white m-1">
                    <b>ACTIVITY LOG</b>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text fw-light">Current Admin Activity Log</p>
                    <div class="container">
                        <a class="btn btn-secondary " href="{{ route('admin.activityLog.index') }}">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



@endsection