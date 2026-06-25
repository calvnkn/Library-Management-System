@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')

<div class="row g-1 mt-2">
            <h2>Welcome, {{ session('admin_name') }}!</h2>
</div>
<div class="row g-4 mt-3">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class=" card-header bg-danger text-white m-1">
                <b>USER PENALTIES</b>
            </div>
            <div class="card-body m-3">
                <p class="card-text fw-light">No. Users w/ Penalties: </p>
                <p class="card-text fw-light">Late Returns: </p>
                <p class="card-text fw-light">Lost Books: </p>
                <a class="btn btn-danger " href="{{ route('admin.defaulters.index') }}">Defaulters</a>
                <a class="btn btn-danger " href="{{ route('admin.fines.index') }}">Fines</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class=" card-header bg-secondary text-white m-1">
                <b>PENDING REQUESTS</b>
            </div>
            <div class="card-body m-3">
                <p class="card-text fw-light">No. of Total Requests: </p>
                <a class="btn btn-primary " href="{{ route('admin.requests.index') }}">Manage</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class=" card-header bg-secondary text-white m-1">
                <b>ACTIVITY LOG</b>
            </div>
            <div class="card-body m-3">
                <p class="card-text fw-light">Current Admin Activity Log</p>
                <a class="btn btn-primary " href="{{ route('admin.activityLog.index') }}">Manage</a>
            </div>
        </div>
    </div>
</div>






@endsection