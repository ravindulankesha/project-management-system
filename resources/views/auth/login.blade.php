<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <h1>Login</h1>

    <form action="{{ url('/login') }}" method="POST">
        @csrf

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit">Login</button>
        </div>
    </form>

    <div>
        <p>Don't have an account? <a href="{{ url('/register') }}">Register here</a></p>
    </div>

</body>
</html>
