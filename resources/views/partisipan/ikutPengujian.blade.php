@extends('layouts.partisipan')

@section('content')
<div class="blueBody bodyIkutPengujian">
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
        <h2>Informasi Pengujian Tree Testing</h2>
        <p>Pada saat pengujian anda akan diminta untuk menjawab serangkaian pertanyaan dengan cara menelusuri pohon navigasi berbentuk dropdown list yang telah disiapkan oleh penguji / tester. untuk menjawab pertanyaan, anda dapat memilih suatu node pada dropdown list kemudian menekan tombol “Pilih Jawaban”. Ketika anda menekan tombol “Pilih Jawaban”, jawaban anda otomatis akan disimpan kemudian anda akan diarahkan ke pertanyaan selanjutnya. Jika anda tidak dapat menjawab pertanyaan, anda dapat menekan tombol “Lewati Pertanyaan” untuk melewati pertanyaan dan lanjut ke pertanyaan selanjutnya. Pengujian akan selesai jika anda telah melalui setiap pertanyaan yang diberikan.</p>
        <p>Sebelum memulai pengujian tree testing, anda dapat melakukan percobaan dengan menekan tombol “Percobaan Pengujian” dibawah ini. Jika anda sudah siap untuk melakukan pengujian, anda dapat memasukkan kode pengujian yang telah dikirim oleh penguji / tester kedalam kotak dibawah ini. hal yang perlu diingat, pengujian tree testing hanya dapat dilakukan satu kali.</p>
        <h2>Mulai Pengujian Tree Testing</h2>
        <form action="/partisipan/mulaiPengujian" autocomplete="off" method="GET">
            <input type="text" name="kodePengujian" class="@error('kodePengujian') redBorder @enderror" placeholder="Kode Pengujian">
            <br>
            <small>*Kode pengujian anda dapatkan dari tester/penguji yang menjadikan anda sebagai partisipan</small>
            @error('kodePengujian') <br><span class="errorMessage">{{ $message }}</span> @enderror
            <div id="ikutPengujianBtns">
                <a href="/partisipan/percobaanPengujian">Percobaan Pengujian</a>
                <input type="submit" value="Mulai Pengujian">
            </div>
        </form>
    </div>
@endsection