<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/titleIcon.svg') }}" />
    <script src="{{ asset('js/script.js') }}"></script>
    <title>{{ $title }}</title>
</head>
<body>
    <div id="progressSoal"></div>
    <div class="container">
        <h1 class="headerSoal">Percobaan Pengujian Tree</h1>
        <h3 id="timer" class="hide">0</h3>
        <div class="boxHalamanSoal">
            <div class="soal">
                <h2></h2>
                <p></p>
            </div>
            <div class="navigationTree">
            </div>
            <div>
                <a id="lewatiPertanyaan">Lewati Pertanyaan <img src="{{ asset('img/skipIcon.svg') }}" alt=""></a>
            </div>
        </div>
    </div>
    <script>
        // menyimpan parent node dari object php eloquent kedalam variabel javascript
        parentNode = <?= $parentNode; ?>
        
        // menyimpan task dari object php eloquent kedalam variabel javascript
        task = <?= $task; ?>

        // mengambil session dari local storage yg bernama jawaban
        sessionJawaban = JSON.parse(localStorage.getItem('jawaban'));

        fungsiPengujian(parentNode, task, true);

        console.log(sessionJawaban)
    </script>
</body>
<html>