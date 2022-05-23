@extends('layouts.partisipan')

@section('content')
    <div class="landingContent">
        <div class="welcomeMessage welcomePartisipan">
            <h1>Hi {{ $userData["nama"] }} !</h1>
            <h6>Selamat datang di Treet, sebagai partisipan anda dapat mengikuti pengujian tree testing dengan menekan tombol “Ikuti Pengujian” dibawah ini.</h6>
            <a href="/partisipan/ikutiPengujian" class="ctaPartisipan btnDarkBlue">Ikuti Pengujian</a>
        </div>
        <img src="{{ asset('img/treeBackground.svg') }}" class="treeIllustration">
    </div>
@endsection