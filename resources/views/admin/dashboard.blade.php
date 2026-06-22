@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<h3>Welcome, {{ session('admin_name') }}</h3>
<p>Use the navbar to manage books or review pending requests.</p>
@endsection