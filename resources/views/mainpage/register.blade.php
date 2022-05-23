@extends('layouts.main')

@section('content')
    <div class="loginBody">
        <div class="bodyLeft registerBody">
            @if(Session::get('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if(Session::get('failed'))
                <div class="alert alert-fail">
                    {{ Session::get('failed') }}
                </div>
            @endif
            <h3>Register</h3>
            <h6>Belum Punya Akun? silahkan registrasi dengan mengisi form dibawah</h6>
            <form action="/register" method="post" enctype="multipart/form-data">
                @csrf
                <div class="formElement">
                    <label>Register Sebagai <span class="textRed">*</span></label>
                    <div class="selectBox">
                        <div class="selectField @error('role') redBorder @enderror" id="selectPartisipan">
                            <p>Pilih Jenis Akun</p>
                            <img src="{{ asset('img/dropdownArrow.svg') }}" class="dropdownArrow">
                        </div>
                        <ul class="optionList hide" id="optionListPartisipan">
                            <li class="options optionsPartisipan" name="Tester">Tester</li>
                            <li class="options optionsPartisipan" name="Partisipan">Partisipan</li>
                        </ul>
                    </div>
                    <input type="hidden" name="role" id="role" value="">
                    @error('role') <span class="errorMessage"> {{ $message }} </span> @enderror
                </div>
                <div class="formElement">
                    <label>Nama <span class="textRed">*</span></label>
                    <input type="text" name="nama" class="@error('nama') redBorder @enderror" value="{{ old('nama') }}" placeholder="Nama ....">
                    @error('nama') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Username <span class="textRed">*</span></label>
                    <input type="text" name="username" class="@error('username') redBorder @enderror" value="{{ old('username') }}" placeholder="Username ....">
                    @error('username') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Email <span class="textRed">*</span></label>
                    <input type="email" name="email" class="@error('email') redBorder @enderror" value="{{ old('email') }}" placeholder="Email ....">
                    @error('email') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Password <span class="textRed">*</span></label>
                    <input type="password" name="password" class="@error('password') redBorder @enderror" placeholder="Password ....">
                    @error('password') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Ketik Ulang Password <span class="textRed">*</span></label>
                    <input type="password" name="password_confirmation" class="@error('password_confirmation') redBorder @enderror" placeholder="Ketik ulang password ....">
                    @error('password_confirmation') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Foto Profil</label>
                    <input type="file" name="profile_pic">
                    @error('profile_pic') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <input type="submit" value="Register" class="btnDarkBlue">
            </form>
        </div>
        <div class="bodyRight infoAkun">
            <div class="iconAndLabel">
                <img src="{{ asset('img/registerInformation.svg') }}" style="width: 51px; height: 51px;">
                <h1>Jenis Akun</h1>
            </div>
            <h6>Informasi Terkait jenis akun yang ada pada aplikasi ini</h6>
            <div class="iconAndLabel">
                <img src="{{ asset('img/registerIconTester.svg') }}" style="width: 48px; height: 45px;">
                <h1 style="margin-top: 10px ;">Tester</h1>
            </div>
            <p>Register sebagai tester jika anda ingin <br> mengadakan pengujian tree testing. <br> Dengan akun ini anda dapat merancang <br> konfigurasi tree testing dan <br> menganalisis hasil pengujian tree <br> testing.</p>
            <div class="iconAndLabel" style="margin-top: 60px;">
                <img src="{{ asset('img/registerIconPartisipan.svg') }}" style="width: 33px; height: 47px;">
                <h1 style="margin-top: 15px;">Partisipan</h1>
            </div>
            <p>Register sebagai partisipan jika anda <br> ingin berpartisipasi pada suatu <br> pengujian tree testing yang telah disiapkan oleh seorang tester.</p>
        </div>
    </div>

    <script>
        // fungsi selectbox partisipan
        customSelectbox('#role','#selectPartisipan','#optionListPartisipan','.optionsPartisipan');
    </script>
@endsection