@extends('layouts.admin')
@section('title', 'Manage Books')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Manage Books</h3>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Add Book</a>
</div>

<form method="GET" class="mb-3">
    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Search title/author/isbn">
</form>

<table class="table table-bordered">
    <thead>
        <tr><th>Title</th><th>Author</th><th>ISBN</th><th>Category</th><th>Copies</th><th></th></tr>
    </thead>
    <tbody>
        @foreach ($books as $book)
        <tr>
            <td>{{ $book->title }}</td>
            <td>{{ $book->author }}</td>
            <td>{{ $book->isbn }}</td>
            <td>{{ $book->category }}</td>
            <td>{{ $book->available_copies }} / {{ $book->total_copies }}</td>
            <td>
                <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                <form method="POST" action="{{ route('admin.books.destroy', $book->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection