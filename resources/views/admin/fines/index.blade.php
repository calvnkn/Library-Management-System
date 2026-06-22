@extends('layouts.admin')
@section('title', 'User Fines')
@section('content')
<h3>User Fines</h3>

<table class="table table-bordered">
    <thead>
        <tr><th>Member</th><th>Book</th><th>Return Date</th><th>Fine Amount</th><th>Status</th><th></th></tr>
    </thead>
    <tbody>
        @forelse ($fines as $f)
        <tr>
            <td>{{ $f->member_name }}</td>
            <td>{{ $f->title }}</td>
            <td>{{ $f->return_date }}</td>
            <td>₱{{ $f->fine_amount }}</td>
            <td>
                <span class="badge {{ $f->fine_status === 'resolved' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($f->fine_status) }}
                </span>
            </td>
            <td>
                @if ($f->fine_status !== 'resolved')
                <form method="POST" action="{{ route('admin.fines.resolve', $f->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-success">Resolve</button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">No fines on record.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection