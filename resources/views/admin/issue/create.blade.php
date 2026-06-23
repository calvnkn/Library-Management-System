@extends('layouts.admin')
@section('title', 'Issue Book Directly')
@section('content')
<h3>Issue Book Directly to Member</h3>

<form method="POST" action="{{ route('admin.issue.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Member</label>
        <select name="member_id" class="form-select" required>
            <option value="">-- Select Member --</option>
            @foreach ($members as $member)
            <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
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
@endsection