@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')


<div class="row g-2 mt-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm" style="width: 18rem;">
            <div class=" card-header bg-secondary text-white m-1">
                <b>TODAY'S STATUS</b>
            </div>
            <div class="card-body m-2">
                <p class="card-text fw-light">No. of Total Users: </p>
                <p class="card-text fw-light">No. of Books Available: </p>
                <p class="card-text fw-light">No. of Reserved Books: </p>
                <p class="card-text fw-light">No. of Books Returned: </p>
                <p class="card-text fw-light">No. of Late Returns: </p>
                <a class="btn btn-primary " href="">Manage Books</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <h3>Welcome, {{ session('admin_name') }}</h3>
        <p>Use the navbar to manage books or review pending requests.</p>

    </div>
</div>


@endsection