@extends('layouts.admin')
@section('title', 'View Issued Books')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>View Issued Books</h3>
    <a href="{{ route('admin.issue.create') }}" class="btn btn-primary">Issue Book Directly</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Total Copies</th>
            <th>Available</th>
            <th>Issued</th>
            <th>In Queue</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($books as $book)
        <tr>
            <td>{{ $book->title }}</td>
            <td>{{ $book->total_copies }}</td>
            <td>{{ $book->available_copies }}</td>
            <td>{{ $book->issued_count }}</td>
            <td>{{ $book->queue_count }}</td>
            <td>
                <span class="badge {{ $book->reservation_status === 'Available' ? 'bg-success' : ($book->reservation_status === 'Fully Issued' ? 'bg-secondary' : 'bg-warning') }}">
                    {{ $book->reservation_status }}
                </span>
            </td>
            <td><a href="{{ route('admin.issuedBooks.show', $book->id) }}" class="btn btn-sm btn-secondary">View Details</a></td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">No books found.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection