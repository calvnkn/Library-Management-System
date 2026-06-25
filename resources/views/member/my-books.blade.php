@extends('layouts.app')

@section('title', 'My Books')

@section('content')
<h3>My Books</h3>

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

            <td>
                @php
                    $fine = ($r->fine_amount ?? 0) + ($r->lost_fine_amount ?? 0);

                    if (
                        $r->type === 'issue' &&
                        $r->status === 'approved' &&
                        $r->due_date &&
                        \Carbon\Carbon::parse($r->due_date)->startOfDay()->lt(now()->startOfDay())
                    ) {
                        $fine += \Carbon\Carbon::parse($r->due_date)
                            ->startOfDay()
                            ->diffInDays(now()->startOfDay()) * 5;
                    }
                @endphp

                ₱{{ number_format($fine, 2) }}
            </td>

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