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
            <a class="navbar-brand">Library Management System</a>
            <div class="d-flex">
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('login') }}">User Login</a>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('admin.login') }}">Admin Login</a>
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