<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Library System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('books.index') }}">Library System</a>
        <div class="d-flex">
            @php
                $unreadNotifCount = 0;
                if (session('member_id')) {
                    $unreadNotifCount = \Illuminate\Support\Facades\DB::table('member_notifications')
                        ->where('member_id', session('member_id'))
                        ->where('read', false)
                        ->count();
                }
            @endphp

            <a href="{{ route('member.myBooks') }}" class="btn btn-outline-light btn-sm position-relative me-2">
                <i class="bi bi-bell"></i>
                @if ($unreadNotifCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $unreadNotifCount }}
                    </span>
                @endif
            </a>
            <a class="btn btn-outline-light btn-sm me-2" href="{{ route('books.index') }}">Catalog</a>
            <a class="btn btn-outline-light btn-sm me-2" href="{{ route('member.myBooks') }}">My Books</a>
            <a class="btn btn-outline-light btn-sm me-2" href="{{ route('profile.edit') }}">My Profile</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>