@extends('layouts.admin')
@section('title', 'Edit Book')
@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body m-4">
                <div class="mb-3">
                    <a href="{{ route('admin.books.index') }}" class="btn btn-primary"> Back
                    </a>
                </div>
                <div class="mb-3">
                    <h3>Edit Book</h3>
                <div class="mb-3">
                <form method="POST" action="{{ route('admin.books.update', $book->id) }}">
                    @csrf
                    @method('PUT')
                    @include('admin.books._form')
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection