@extends('layouts.admin')
@section('title', 'Manage Users')
@section('content')
<h3>Manage Users</h3>

<form method="GET" class="mb-3">
    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Search name or email">
</form>

<table class="table table-bordered">
    <thead>
        <tr><th>Name</th><th>Email</th><th>Contact Number</th><th></th></tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
        <tr>
            <td>{{ $member->first_name }} {{ $member->last_name }}</td>
            <td>{{ $member->email }}</td>
            <td>{{ $member->contact_number }}</td>
            <td><a href="{{ route('admin.users.show', $member->id) }}" class="btn btn-sm btn-secondary">View</a></td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center">No members found.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection