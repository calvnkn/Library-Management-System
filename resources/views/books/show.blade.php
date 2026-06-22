@extends('layouts.app')
@section('title', $book->title)
@section('content')
<h3>{{ $book->title }}</h3>
<p><strong>Author:</strong> {{ $book->author }}</p>
<p><strong>ISBN:</strong> {{ $book->isbn }}</p>
<p><strong>Publication:</strong> {{ $book->publication }}</p>
<p><strong>Category:</strong> {{ $book->category }}</p>
<p><strong>Availability:</strong> {{ $book->available_copies }} / {{ $book->total_copies }}</p>

@if ($book->available_copies > 0)
    <form method="POST" action="{{ route('books.issue', $book->id) }}">
        @csrf
        <button type="submit" class="btn btn-primary">Borrow this Book</button>
    </form>
@else
    <form method="POST" action="{{ route('books.reserve', $book->id) }}">
        @csrf
        <button type="submit" class="btn btn-warning">Reserve this Book</button>
    </form>
    <small class="text-muted">No copies available right now. Don't worry, you'll be queued.</small>
@endif

<a href="{{ route('books.index') }}" class="btn btn-link">Back to Catalog</a>
@endsection