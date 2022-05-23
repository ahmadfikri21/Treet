<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@sgratzl/chartjs-chart-boxplot@3.6.0/build/index.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/titleIcon.svg') }}" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <title>{{ $title }}</title>
</head>
<body>
<div id="sidebar" >
        <div id="sidebarTopIcons">
            <a href="/tester/editProfil"><img src="{{ asset('img/sidebarGear.svg') }}"></a>
            <img src="{{ asset('img/sidebarHamburger.svg') }}" id="sidebarHamburger">
        </div>
        <div id="sidebarUserInfo">
            @if(!Session::get('profile_pic'))
                <img src="{{ asset('img/userBundar.svg') }}" alt="">
            @else
                <img src="{{ asset('img/userImages/'.Session::get('profile_pic')) }}" alt="">
            @endif
            <h4>{{ Session::get('nama') }}</h4>
            <h5>{{ Session::get('email') }}</h5>
        </div>
        <div id="sidebarLinks">
            <a href="/tester/konfigurasiTree">
                <div class="iconAndLabel {{ (request()->is('tester/konfigurasiTree*')) ? 'sidebarLinkActive' : '' ; }}">
                    <img src="{{ asset('img/sidebarKonfigurasiPengujian.svg') }}">
                    <h3>Konfigurasi Pengujian</h3>
                </div>
            </a>
            <a href="/tester/hasilPengujian">
                <div class="iconAndLabel {{ (request()->is('tester/hasilPengujian*')) ? 'sidebarLinkActive' : '' ; }}">
                    <img src="{{ asset('img/sidebarHasilPengujian.svg') }}">
                    &nbsp;
                    <h3>Hasil Pengujian</h3>
                </div>
            </a>
            <a href="/logout">
                <div class="iconAndLabel">
                    <img src="{{ asset('img/sidebarLogout.svg') }}">
                    <h3>Logout</h3>
                </div>
            </a>
        </div>
    </div>
    @yield('content')
    <script>
        $("#sidebarHamburger").click(function(){
            $("#sidebar").toggleClass("sidebarClosed");
            $(".sideContainer").toggleClass('sideContainerClosed')
        });
    </script>
    @include('sweetalert::alert')
</body>
<html>