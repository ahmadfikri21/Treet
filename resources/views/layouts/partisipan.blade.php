<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/titleIcon.svg') }}" />
    <title>{{ $title }}</title>
</head>
<body>
    <div class="container">
        <nav class="mainNav partisipanNav">
            <div class="navLogo">
                <a href="/partisipan/home">Treet</a>
            </div>
            <ul>
                <li><a href="/partisipan/home" class="{{ (request()->is('partisipan/home*')) ? 'navLinkActive' : '' ; }}">Home</a></li>
                <li><a href="/partisipan/ikutiPengujian" class="{{ (request()->is('partisipan/ikutiPengujian*')) ? 'navLinkActive' : '' ; }}">Pengujian</a></li>
                <li>
                    <div id="navDropdownPartisipan">
                        @if(!Session::get('profile_pic'))
                            <div id="imgDropdown">
                                <img src="{{ asset('img/userBundar.svg') }}">
                            </div>
                        @else
                            <div id="imgDropdown">
                                <img src="{{ asset('img/userImages/'.Session::get('profile_pic')) }}">
                            </div>
                        @endif
                        <div class="navDropdownContent">
                            <div class="iconAndLabel">
                                <img src="{{ asset('img/userNonBundar.svg') }}">
                                <a href="/partisipan/editProfile">Edit profile</a>
                            </div>
                            <div class="iconAndLabel">
                                <img src="{{ asset('img/logoutIcon.svg') }}">
                                <a href="/logout">Logout</a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
        
        @yield('content')
    
        <script>
            // kedua fungsi dibawah adalah fungsi untuk hover pada img user
            $("#imgDropdown").hover(function(){
                $(".navDropdownContent").css("display","block")
            },function(){
                $(".navDropdownContent").css("display","none")
            })

            $(".navDropdownContent").hover(function(){
                $(".navDropdownContent").css("display","block")
            }, function(){
                $(".navDropdownContent").css("display","none")
            })
        </script>
    </div>
</body>
<html>