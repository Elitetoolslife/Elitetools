<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <form action="{{ route('signup') }}" method="POST">
        @csrf
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}">
            @error('username')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        <button type="submit">Signup</button>
    </form>

    <div id="dropDownSelect1"></div>

    <!-- Remove inline JS files, now using Vite -->
</body>

</html>