@extends('layouts.app')

@section('content')
<head>
    <title>New User Approval</title>
</head>
<body>
    <h2>New User Registration</h2>
    <p>A new user has registered and is awaiting approval.</p>
    <ul>
        <li><strong>Name:</strong> {{ $user->full_name }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Role:</strong> {{ $user->role }}</li>
    </ul>
</body>
</html>
