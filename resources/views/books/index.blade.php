@extends('layouts.app')
@section('title', 'Catalog')
@section('content')
<h3>Book Catalog</h3>


<form method="GET" action="{{ route('books.index') }}" class="row g-2 mb-4">
    <div class="col-auto">
        <select name="search_by" class="form-select">
            <option value="title" @selected($searchBy=='title' )>Title</option>
            <option value="author" @selected($searchBy=='author' )>Author</option>
            <option value="id" @selected($searchBy=='id' )>Book ID</option>
        </select>
    </div>
    <div class="col-auto">
        <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Search...">
    </div>
    <div class="col-auto">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Available</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($books as $book)
        <tr>
            <td>{{ $book->title }}</td>
            <td>{{ $book->author }}</td>
            <td>{{ $book->category }}</td>
            <td>{{ $book->available_copies }} / {{ $book->total_copies }}</td>
            <td><a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-secondary">View</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection