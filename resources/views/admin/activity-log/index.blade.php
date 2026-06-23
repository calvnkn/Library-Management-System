@extends('layouts.admin')
@section('title', 'Activity Log')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Activity Log</h3>
    <form method="GET" class="d-flex gap-2">
        <select name="type" class="form-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            <option value="issue" @selected($type=='issue')>Issued</option>
            <option value="return" @selected($type=='return')>Returned</option>
            <option value="reserve" @selected($type=='reserve')>Reserved</option>
        </select>
    </form>
</div>

<table class="table table-bordered">
    <thead>
        <tr><th>Member</th><th>Book</th><th>Type</th><th>Status</th><th>Date</th></tr>
    </thead>
    <tbody>
        @forelse ($logs as $log)
        <tr>
            <td>{{ $log->member_name }}</td>
            <td>{{ $log->title }}</td>
            <td>{{ ucfirst($log->type) }}</td>
            <td>{{ ucfirst($log->status) }}</td>
            <td>{{ $log->updated_at }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No activity recorded yet.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection