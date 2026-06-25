@extends('layouts.admin')
@section('title', 'User Fines')
@section('content')
<div class="mb-3">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary"> Dashboard
    </a>
</div>
<h3>User Fines</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Member</th>
            <th>Book</th>
            <th>Return Date</th>
            <th>Fine Amount</th>
            <th>Status</th>
            <th></th>
        </tr>
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
        <tr>
            <td colspan="6" class="text-center">No fines on record.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<h5 class="mt-4">Lost Book Fines</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Member</th>
            <th>Book</th>
            <th>Fine</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lostFines as $lf)
        <tr>
            <td>{{ $lf->member_name }}</td>
            <td>{{ $lf->title }}</td>
            <td>₱{{ number_format($lf->lost_fine_amount, 2) }}</td>
            <td>
                <span class="badge bg-{{ $lf->fine_status === 'resolved' ? 'success' : 'danger' }}">
                    {{ ucfirst($lf->fine_status) }}
                </span>
            </td>
            <td>
                @if ($lf->fine_status !== 'resolved')
                <form action="{{ route('admin.fines.resolve', $lf->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-success">Mark Resolved</button>
                </form>
                @else
                    —
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection