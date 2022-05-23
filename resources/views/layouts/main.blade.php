<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/titleIcon.svg') }}" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <title>{{ $title }}</title>
</head>
<body>
    <div class="container">
        <nav class="mainNav">
            <div class="navLogo">
                <a href="/">Treet</a>
            </div>
            <ul>
                <li><a href="/" class="{{ (request()->is('/')) ? 'navLinkActive' : '' ; }}">Home</a></li>
                <li><a href="/login" class="{{ (request()->url() == url('/login')) ? 'navLinkActive' : '' ; }}">Login</a></li>
            </ul>
        </nav>
        
        @yield('content')
    
    </div>
</body>
<html>