@extends('layouts.main')

@section('content')
    <div class="landingContent">
        <div class="welcomeMessage">
            <h1>Selamat Datang <br> di Treet</h1>
            <h6>Treet merupakan aplikasi web open source yang dapat digunakan untuk melakukan pengujian alur navigasi website menggunakan metode tree testing. Untuk menggunakan Treet,  Silahkan klik tombol dibawah ini untuk melakukan register atau login terlebih dahulu.</h6>
            <div class="callToAction">
                <a href="/register">Register</a>
                <a href="/login">Login</a>
            </div>
        </div>
        <img src="{{ asset('img/treeBackground.svg') }}" class="treeIllustration">
    </div>
@endsection