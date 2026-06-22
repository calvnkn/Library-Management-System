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
    <p class="text-danger">No copies available right now. (Reservation coming in a later round.)</p>
@endif

<a href="{{ route('books.index') }}" class="btn btn-link">Back to Catalog</a>
@endsection