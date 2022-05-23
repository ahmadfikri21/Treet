@extends('layouts.tester')

@section('content')
    <div class="sideContainer">
        <div class="blueBody informasiPengujianBody">
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
            <div class="iconAndLabel">
                <img src="{{ asset('img/iconKonfigurasiTask.svg') }}">
                <h2>Formulir Informasi Pengujian</h2>
            </div>
            <h6>Sebelum anda dapat melakukan pengujian Tree Testing, anda harus mengisi formulir informasi pengujian dibawah ini.</h6>
            <form action="/tester/storeInformasiPengujian" method="POST" id="formInformasiPengujian">
                @csrf
                <div class="formElement">
                    <label>Nama Website / Aplikasi <span class="textRed">*</span></label>
                    <input type="text" name="nama_website" id="nama_website" class="@error('nama_website') redBorder @enderror" placeholder="Nama Website / Aplikasi ..." value="{{ @old('nama_website') }}">
                    @error('nama_website') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Scope Pengujian <span class="textRed">*</span></label>
                    <input type="text" name="scope_pengujian" id="scope_pengujian" class="@error('scope_pengujian') redBorder @enderror" placeholder="Scope Pengujian ..." value="{{ @old('scope_pengujian') }}">
                    @error('scope_pengujian') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Profil Partisipan Pengujian<span class="textRed">*</span></label>
                    <input type="text" name="profil_partisipan" id="profil_partisipan" class="@error('profil_partisipan') redBorder @enderror" placeholder="Contoh : Mahasiswa & Pelajar" value="{{ @old('profil_partisipan') }}">
                    @error('profil_partisipan') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Jumlah Minimal Partisipan<span class="textRed">*</span></label>
                    <input type="number" name="minimal_partisipan" id="minimal_partisipan" class="@error('minimal_partisipan') redBorder @enderror" min="0" placeholder="Contoh : 10" value="{{ @old('minimal_partisipan') }}">
                    @error('minimal_partisipan') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Tanggal Mulai Pengujian</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="@error('tanggal_mulai') redBorder @enderror" value="{{ @old('tanggal_mulai') }}">
                    @error('tanggal_mulai') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <div class="formElement">
                    <label>Tanggal Berakhir Pengujian</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="@error('tanggal_akhir') redBorder @enderror" value="{{ @old('tanggal_akhir') }}">
                    @error('tanggal_akhir') <span class="errorMessage">{{ $message }}</span> @enderror
                </div>
                <input type="submit" class="btn btnDarkBlue" value="Submit">
            </form>
        </div>
    </div>

    <script>
        // menetapkan tanggal hari ini sebagai min dari element input tanggal_mulai
        $("#tanggal_mulai").attr("min",getStringDate());

        // set tanggal_akhir tidak boleh kurang dari tanggal awal
        $("#tanggal_mulai").change(function(){
            // mengambil tanggal mulai dari value tanggal mulai
            tanggalMulai = $(this).val();
            // membuat object date berdasarkan tanggal mulai
            date = new Date(tanggalMulai);
            // menambahkan 1 hari dari tanggal mulai
            date.setDate(date.getDate() + 1);

            // merubah object date dari object menjadi string
            minTanggal = getStringDate(date);

            // menetapkan tanggal mulai+1 sebagai element input dari tanggal_akhir 
            $("#tanggal_akhir").attr("min", minTanggal);
            $("#tanggal_akhir").prop('disabled', false)
        });

        $("#tanggal_akhir").click(function(){
            // mengambil tanggal mulai
            tanggalMulai = $("#tanggal_mulai").val();
            // membuat object date berdasarkan tanggal mulai
            date = new Date(tanggalMulai);
            // menambahkan 1 hari dari tanggal mulai
            date.setDate(date.getDate() + 1);

            // merubah object date dari object menjadi string
            minTanggal = getStringDate(date);

            // menetapkan tanggal mulai+1 sebagai element input dari tanggal_akhir 
            $("#tanggal_akhir").attr("min", minTanggal);
        })

        if($("#tanggal_mulai").val() == ""){
            $("#tanggal_akhir").prop('disabled', true)
        }else{
            $("#tanggal_akhir").prop('disabled', false)
        }
        console.log()
    </script>
@endsection