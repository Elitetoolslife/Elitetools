<!DOCTYPE html>
<html lang="en">
<head>
	<title>User Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{SERVER_BASE_URL}/static/img/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/staticvendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/css/util.css">
	<link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/css/main.css">
  <link rel="stylesheet" type="text/css" href="{SERVER_BASE_URL}/static/css/style.css">
<!--===============================================================================================-->
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
	
<!--===============================================================================================-->
	<script src="{SERVER_BASE_URL}/static/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="{SERVER_BASE_URL}/static/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="{SERVER_BASE_URL}/static/vendor/bootstrap/js/popper.js"></script>
	<script src="{SERVER_BASE_URL}/static/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="{SERVER_BASE_URL}/static/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="{SERVER_BASE_URL}/static/vendor/daterangepicker/moment.min.js"></script>
	<script src="{SERVER_BASE_URL}/staticvendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="{SERVER_BASE_URL}/static/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="{SERVER_BASE_URL}/static/js/main.js"></script>

</body>
</html>