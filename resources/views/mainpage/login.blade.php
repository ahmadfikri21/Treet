@extends('layouts.main')

@section('content')
    <div class="loginBody">
        <div class="bodyLeft">
            @if(Session::get('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if(Session::get('failed'))
                <div class="alert alert-failed">
                    {{ Session::get('failed') }}
                </div>
            @endif
            <h3>Login</h3>
            <h6>Silahkan login menggunakan akun anda</h6>
            <form action="/login" method="post">
                @csrf
                <div class="formElement">
                    <label>Username</label>
                    <input type="text" name="username" class="@error('username') redBorder @enderror" placeholder="Username ...." value="{{ old('username') }}">
                    @error('username') <span class="errorMessage"> {{ $message }} </span> @enderror
                </div>
                <div class="formElement">
                    <label>Password</label>
                    <input type="password" name="password" class="@error('password') redBorder @enderror" placeholder="Password ...." value="{{ old('password') }}">
                    @error('password') <span class="errorMessage"> {{ $message }} </span> @enderror
                </div>
                <a href="/register">Belum punya akun ?</a>
                <input type="submit" value="Login" class="btnDarkBlue btnLogin">
            </form>
        </div>
        <div class="bodyRight">
            <img src="{{ asset('img/ilustrasiLogin.svg') }}">
        </div>
    </div>
@endsection