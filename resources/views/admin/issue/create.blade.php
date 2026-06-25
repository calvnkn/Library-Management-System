@extends('layouts.admin')
@section('title', 'Issue Book Directly')
@section('content')
<div class="card shadow-sm">
    <div class="card-body m-4">
        <div class="mb-3"> 
            <a href="{{ route('admin.issuedBooks.index') }}" class="btn btn-primary"> Back
            </a>
        </div>
        <div class="mb-4">
            <h3>Issue Book Directly to Member</h3>
        </div>


        <form method="POST" action="{{ route('admin.issue.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Member</label>
                <select name="member_id" class="form-select" required>
                    <option value="">-- Select Member --</option>
                    @foreach ($members as $member)
                    <option value="{{ $member->id }}">{{ trim("{$member->first_name} {$member->middle_name} {$member->last_name}") }} ({{ $member->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Book</label>
                <select name="book_id" class="form-select" required>
                    <option value="">-- Select Book --</option>
                    @foreach ($books as $book)
                    <option value="{{ $book->id }}">{{ $book->title }} ({{ $book->available_copies }} available)</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Issue Book</button>
        </form>
    </div>
</div>

@endsection