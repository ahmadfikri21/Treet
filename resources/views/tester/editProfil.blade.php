@extends('layouts.tester')

@section('content')
    <div class="sideContainer">
        @if(!Session::get('profile_pic'))
            <img src="{{ asset('img/userBundar.svg') }}" id="imageUser">
        @else
            <img src="{{ asset('img/userImages/'.Session::get('profile_pic')) }}" id="imageUser">
        @endif
        <div class="blueBody">
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
            <div id="editAndGantiPassword">
                <div id="editProfileBox">
                    <h3>Edit Profile</h3>
                    <form action="/tester/prosesEditProfil" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="formElement">
                            <label>Nama</label>
                            <input type="text" name="nama" class="@error('nama') redBorder @enderror" placeholder="Nama ..." value="{{ old('nama',$dataUser['nama']) }}">
                            @error('nama') <span class="errorMessage"> {{ $message }} </span> @enderror
                        </div>
                        <div class="formElement">
                            <label>Username</label>
                            <input type="text" name="username" class="@error('username') redBorder @enderror" placeholder="Username ..." value="{{ old('username',$dataUser['username']) }}">
                            @error('username') <span class="errorMessage"> {{ $message }} </span> @enderror
                        </div>
                        <div class="formElement">
                            <label>Email</label>
                            <input type="email" name="email" class="@error('email') redBorder @enderror" placeholder="Email ..." value="{{ old('email',$dataUser['email']) }}">
                        </div>
                        @error('email') <span class="errorMessage"> {{ $message }} </span> @enderror
                        <div class="formElement">
                            <label>Foto Profil</label>
                            <input type="file" name="profile_pic" value="{{ $dataUser['profile_pic'] }}">
                        </div>
                        <input type="hidden" name="role" value="Tester">
                        <input type="submit" class="btn btnDarkBlue" value="Edit Profil">
                    </form>
                </div>
                <div id="gantiPasswordBox">
                <h3>Ganti Password</h3>
                    <form action="/tester/gantiPassword" method="POST">
                        @csrf
                        <div class="formElement">
                            <label>Password Lama</label>
                            <input type="password" name="old_password" class="@error('old_password') redBorder @enderror" placeholder="Password Lama ...">
                            @error('old_password') <span class="errorMessage"> {{ $message }} </span> @enderror
                        </div>
                        <div class="formElement">
                            <label>Password Baru</label>
                            <input type="password" name="password" class="@error('password') redBorder @enderror" placeholder="Password Baru ...">
                            @error('new_password') <span class="errorMessage"> {{ $message }} </span> @enderror
                        </div>
                        <div class="formElement">
                            <label>Ketik Ulang Password</label>
                            <input type="password" name="password_confirmation" class="@error('password_confirmation') redBorder @enderror" placeholder="Konfirmasi Password baru ...">
                            @error('password_confirmation') <span class="errorMessage"> {{ $message }} </span> @enderror
                        </div>
                        <input type="hidden" name="role" value="Tester">
                        <input type="submit" class="btn btnDarkBlue" value="Ganti Password">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection