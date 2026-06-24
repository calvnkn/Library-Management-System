@extends('layouts.admin')
@section('title', $book->title)
@section('content')
<h3>{{ $book->title }}</h3>
<p><strong>Author:</strong> {{ $book->author }}</p>
<p><strong>Copies:</strong> {{ $book->available_copies }} available / {{ $book->total_copies }} total</p>

<h5 class="mt-4">Currently Issued To</h5>
<table class="table table-bordered">
    <thead>
        <tr><th>Member</th><th>Email</th><th>Issue Date</th><th>Due Date</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse ($holders as $h)
        <tr>
            <td>{{ $h->member_name }}</td>
            <td>{{ $h->member_email }}</td>
            <td>{{ $h->issue_date }}</td>
            <td>{{ $h->due_date }}</td>
            <td>
                @if ($h->is_overdue)
                    <span class="badge bg-danger">Overdue</span>
                @else
                    <span class="badge bg-success">On Time</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No copies currently issued.</td></tr>
        @endforelse
    </tbody>
</table>

<h5 class="mt-4">Reservation Queue</h5>
<table class="table table-bordered">
    <thead>
        <tr><th>#</th><th>Member</th><th>Requested On</th></tr>
    </thead>
    <tbody>
        @forelse ($queue as $index => $q)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $q->member_name }}</td>
            <td>{{ $q->request_date }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-center">No one in queue.</td></tr>
        @endforelse
    </tbody>
</table>

<a href="{{ route('admin.issuedBooks.index') }}" class="btn btn-link mt-3">Back to Issued Books</a>
@endsection