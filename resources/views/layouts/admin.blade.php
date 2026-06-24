<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin — Library System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>
            <div class="d-flex">
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.books.index') }}">Manage Books</a>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.requests.index') }}">Requests</a>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.defaulters.index') }}">Defaulters</a>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.fines.index') }}">Fines</a>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.activityLog.index') }}">Activity Log</a>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.users.index') }}">Manage Users</a>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.issue.create') }}">Issue Book</a>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.profile.edit') }}">My Profile</a>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
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