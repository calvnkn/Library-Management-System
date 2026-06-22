@extends('layouts.admin')
@section('title', 'Defaulter List')
@section('content')
<h3>Defaulter List</h3>
<p class="text-muted">Members currently holding a book past its due date.</p>

<table class="table table-bordered">
    <thead>
        <tr><th>Member</th><th>Email</th><th>Book</th><th>Due Date</th><th>Days Overdue</th><th>Projected Fine</th></tr>
    </thead>
    <tbody>
        @forelse ($defaulters as $d)
        <tr>
            <td>{{ $d->member_name }}</td>
            <td>{{ $d->member_email }}</td>
            <td>{{ $d->title }}</td>
            <td>{{ $d->due_date }}</td>
            <td>{{ $d->days_overdue }}</td>
            <td>₱{{ $d->projected_fine }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">No defaulters right now.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection