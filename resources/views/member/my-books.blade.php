@extends('layouts.app')
@section('title', 'My Books')
@section('content')
<h3>My Books</h3>

@if (isset($notifications) && $notifications->count())
<div class="mb-4">
    <h5>Notifications</h5>
    <div class="list-group">
        @foreach ($notifications as $notif)
        <div class="list-group-item list-group-item-action {{ $notif->read ? '' : 'list-group-item-primary' }}">
            <div class="d-flex w-100 justify-content-between">
                <strong>{{ $notif->title }}</strong>
                <small class="text-muted">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</small>
            </div>
            <p class="mb-0 small">{{ $notif->message }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Status</th>
            <th>Queue #</th>
            <th>Issue Date</th>
            <th>Due Date</th>
            <th>Return Date</th>
            <th>Fine</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $r)
        <tr>
            <td>{{ $r->title }}</td>
            <td>{{ ucfirst($r->type) }}</td>
            <td>{{ ucfirst($r->status) }}</td>
            <td>{{ $r->queue_position ?? '—' }}</td>
            <td>{{ $r->issue_date }}</td>
            <td>{{ $r->due_date }}</td>
            <td>{{ $r->return_date }}</td>
            <td>{{ $r->fine_amount }}</td>
            <td>
                @if ($r->type === 'issue' && $r->status === 'approved')
                    @if ($r->can_renew)
                    <form method="POST" action="{{ route('member.renew', $r->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-info" type="submit">Renew</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('member.requestReturn', $r->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-warning" type="submit">Request Return</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection