@extends('layouts.admin')
@section('title', 'Add Book')
@section('content')
<h3>Add Book</h3>
<form method="POST" action="{{ route('admin.books.store') }}">
    @csrf
    @include('admin.books._form')
    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection