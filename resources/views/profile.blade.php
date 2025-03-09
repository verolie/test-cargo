<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <p>Email: {{ auth()->user()->email }}</p>
    <a href="{{ route('logout') }}">Logout</a>
</body>
</html>
