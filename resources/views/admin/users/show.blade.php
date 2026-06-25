@extends('layouts.admin')
@section('title', 'Member Profile')
@section('content')
<h3>{{ $member->first_name }} {{ $member->last_name }}</h3>
<p><strong>Email:</strong> {{ $member->email }}</p>
<p><strong>Address:</strong> {{ $member->address }}</p>
<p><strong>Contact Number:</strong> {{ $member->contact_number }}</p>
<p><strong>Unpaid Fines Total:</strong> ₱{{ $unpaidFines }}</p>

<h5 class="mt-4">Request History</h5>
<table class="table table-bordered">
    <thead>
        <tr><th>Book</th><th>Type</th><th>Status</th><th>Due Date</th><th>Return Date</th><th>Fine</th></tr>
    </thead>
    <tbody>
        @forelse ($requests as $r)
        <tr>
            <td>{{ $r->title }}</td>
            <td>{{ ucfirst($r->type) }}</td>
            <td>{{ ucfirst($r->status) }}</td>
            <td>{{ $r->due_date }}</td>
            <td>{{ $r->return_date }}</td>
            <td>{{ $r->fine_amount }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">No requests yet.</td></tr>
        @endforelse
    </tbody>
</table>

<a href="{{ route('admin.users.index') }}" class="btn btn-link">Back to Manage Users</a>
@endsection