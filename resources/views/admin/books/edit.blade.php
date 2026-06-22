@extends('layouts.admin')
@section('title', 'Edit Book')
@section('content')
<h3>Edit Book</h3>
<form method="POST" action="{{ route('admin.books.update', $book->id) }}">
    @csrf
    @method('PUT')
    @include('admin.books._form')
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection