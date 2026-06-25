@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')


<div class="row g-2 mt-4">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm" style="width: 18rem;">
            <div class=" card-header bg-secondary text-white m-1">
                <b>MANAGE BOOKS</b>
            </div>
            <div class="card-body m-4">
                <p class="card-text fw-light">No. of Books Available: </p>
                <p class="card-text fw-light">No. of Reserved Books: </p>
                <p class="card-text fw-light">No. of Books Returned: </p>
                <a class="btn btn-primary " href="">Manage Books</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-1">
        <div class="row g-1 mt-1.5">
            <h3>Welcome, {{ session('admin_name') }}</h3>
            <p>Use the navbar to manage books or review pending requests.</p>
        </div>
        <div class="row g-1 mt-3.5">
            <div class="card shadow-sm">
                <div class=" card-header bg-secondary text-white m-1">
                    <b>ACTIVITY LOG</b>
                </div>
                <div class="card-body m-2">
                    <p class="card-text fw-light">Admin's Last Activity was:  </p>
                    <a class="btn btn-primary" href="">View Logs</a>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="row g-3 mt-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class=" card-header bg-danger text-white m-1">
                <b>USER PENALTIES</b>
            </div>
            <div class="card-body m-2">
                <p class="card-text fw-light">No. Users w/ Penalties </p>
                <a class="btn btn-danger " href="">View Defaulters</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class=" card-header bg-secondary text-white m-1">
                <b>MANAGE USERS</b>
            </div>
            <div class="card-body m-2">
                <p class="card-text fw-light">No. of Total Users: </p>
                <a class="btn btn-primary " href="">View Books</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class=" card-header bg-secondary text-white m-1">
                <b>MANAGE REQUESTS</b>
            </div>
            <div class="card-body m-2">
                <p class="card-text fw-light">No. of Current Requests: </p>
                <a class="btn btn-primary " href="">View Requests</a>
            </div>
        </div>
    </div>
</div>


@endsection