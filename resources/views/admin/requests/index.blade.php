@extends('layouts.admin')
@section('title', 'Pending Requests')
@section('content')
<div class="mb-3">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary"> Dashboard
    </a>
</div>
<h3>Pending Requests</h3>
<table class="table table-bordered">
    <thead>
        <tr><th>Member</th><th>Book</th><th>Type</th><th>Requested</th><th></th></tr>
    </thead>
    <tbody>
        @foreach ($requests as $r)
        <tr>
            <td>{{ $r->member_name }}</td>
            <td>{{ $r->title }}</td>
            <td>{{ ucfirst($r->type) }}</td>
            <td>{{ $r->request_date }}</td>
            <td>
                <form method="POST" action="{{ route('admin.requests.approve', $r->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-success">Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.requests.reject', $r->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-danger">Reject</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection