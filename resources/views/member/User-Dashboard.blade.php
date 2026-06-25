@extends('layouts.app')

@section('title', 'User Dashboard')
 
@section('content')
<div class="container py-4">
 
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h2 class="mb-0">User Dashboard</h2>
            <p class="text-muted mb-0">Welcome Bookworm, <strong>{{ session('member_name') }}</strong>.</p>
        </div>
        <span class="text-muted small">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
    </div>
 
    <hr class="mb-4">
 
    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
 
        {{-- Books Issued Card --}}
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="m-1 card-header bg-secondary text-white">
                    <b>BOOKS ISSUED</b>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text fw-light">
                        Books currently with you: <strong>{{ $issuedCount}}</strong>
                    </p>
                </div>
            </div>
        </div>
 
        {{-- Pending Requests Card --}}
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="m-1 card-header bg-warning text-dark">
                    <b>PENDING REQUESTS</b>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text fw-light">
                        Awaiting approval: <strong>{{ $pendingRequests->count() }}</strong>
                    </p>
                </div>
            </div>
        </div>
 
        {{-- Fines Card — only renders if member has outstanding fines --}}
        @if ($totalFines > 0)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card shadow-sm h-100 border-danger">
                <div class="m-1 card-header bg-danger text-white">
                    <b>OUTSTANDING FINES</b>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text fw-light">
                        Total penalty: <strong>₱{{ number_format($totalFines, 2) }}</strong>
                    </p>
                    <p class="text-danger small mb-0">Please settle your fines at the library.</p>
                </div>
            </div>
        </div>
        @endif
 
    </div>
 
    {{-- Pending Requests Table --}}
    <div class="card shadow-sm">
        <div class=" m-1 card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <b>PENDING REQUESTS</b>
            <span class="badge bg-dark">{{ $pendingRequests->count() }}</span>
        </div>
 
        <div class="card-body p-0">
            @if ($pendingRequests->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    You have no pending requests at the moment.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Book ID</th>
                                <th>Book Title</th>
                                <th>Request Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingRequests as $request)
                                <tr>
                                    <td>{{ $request->book_id }}</td>
                                    <td>{{ $request->book->title ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->request_date)->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
 
        @if ($pendingRequests->isNotEmpty())
            <div class="card-footer text-muted small">
                Showing {{ $pendingRequests->count() }} pending request(s).
            </div>
        @endif
    </div>
 
</div>
@endsection