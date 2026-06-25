@extends('layouts.admin')
@section('title', $book->title)
@section('content')
<h3>{{ $book->title }}</h3>
<p><strong>Author:</strong> {{ $book->author }}</p>
<p><strong>Copies:</strong> {{ $book->available_copies }} available / {{ $book->total_copies }} total</p>
<p><strong>Replacement Price:</strong> ₱{{ $book->replacement_price ?? 0 }}</p>

<h5 class="mt-4">Currently Issued To</h5>
<table class="table table-bordered">
    <thead>
        <tr><th>Member</th><th>Email</th><th>Issue Date</th><th>Due Date</th><th>Status</th><th></th></tr>
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
            <td>
                <form method="POST" action="{{ route('admin.issuedBooks.markLost', $h->id) }}" onsubmit="return confirm('Mark this copy as lost? This will apply a fine and remove one copy permanently.')">
                    @csrf
                    <button class="btn btn-sm btn-danger" type="submit">Mark as Lost</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">No copies currently issued.</td></tr>
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

<h5 class="mt-4">Lost Copies History</h5>
<table class="table table-bordered">
    <thead>
        <tr><th>Member</th><th>Email</th><th>Reported On</th><th>Fine Amount</th><th>Fine Status</th></tr>
    </thead>
    <tbody>
        @forelse ($lostRecords as $l)
        <tr>
            <td>{{ $l->member_name }}</td>
            <td>{{ $l->member_email }}</td>
            <td>{{ $l->updated_at }}</td>
            <td>₱{{ $l->lost_fine_amount }}</td>
            <td>
                <span class="badge {{ $l->fine_status === 'resolved' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($l->fine_status) }}
                </span>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No lost copies recorded for this book.</td></tr>
        @endforelse
    </tbody>
</table>

<a href="{{ route('admin.issuedBooks.index') }}" class="btn btn-link mt-3">Back to Issued Books</a>
@endsection