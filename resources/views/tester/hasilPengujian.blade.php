@extends('layouts.tester')

<?php
    $popularPath = false;
    $countPopularPath = 0;

    if(count($detailHasil)){
        //untuk array key, parameter dikurang 1 karena array dimulai dari 0
        $arrayKey = $noTask-1;

        // memanggil fungsi findpopularpath dari helpers (fungsi me return array yang berisi path popular dan jumlah dari path tersebut)
        $popular = findPopularPath($detailHasil, $noTask);

        // menyimpan popular path dan jumlah yang berbentuk array ke variabel biasa
        $popularPath = $popular['path'][0];
        $countPopularPath = $popular['jumlah'][0];

    }
    
?>

@section('content')
    <div class="preloader">
        <img src="{{ asset('img/treeBackground.svg') }}">
        <h1>Mohon Tunggu...</h1>
        <h1>Pastikan File Export Berhasil Didownload</h1>
    </div>
    <div class="sideContainer">
        <div class="testerMainHeader iconAndLabel">
            <img src="{{ asset('img/iconHasilPengujian.svg') }}">
            <h1>Hasil Pengujian Tree Testing</h1>
        </div>
        <div class="testerSubHeader selectDanDetailHasil">
        @if(count($task) != 0 && !$detailHasil->isEmpty())
            <div class="formElement">
                <label>Hasil dari task</label>
                <div class="selectBox">
                    @php
                        $i = 1;
                    @endphp
                    <div class="selectField" id="selectTask">
                        <p>Task {{ $noTask." - ".$task[$arrayKey]->deskripsi }}</p>
                        <img src="{{ asset('img/dropdownArrow.svg') }}" class="dropdownArrow">
                    </div>
                    <ul class="optionList hide" id="optionListTask">
                        @foreach($task as $row)
                            <li class="options optionsTask" name="{{ $row->id_task }}" id="{{ $i }}">Task {{ $i." - ".$row->deskripsi }}</li>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="task" id="task" value="{{ $task[$arrayKey]->id_task }}">
                <input type="hidden" id="noTask" value="{{ $noTask }}">
            </div>
            <div>
                @php
                    $totalPartisipan = $akurasi[$arrayKey]['sukses'] + $akurasi[$arrayKey]['gagal'] + $akurasi[$arrayKey]['tidak_dijawab'];
                    
                @endphp
                @if($totalPartisipan < $minimalPartisipan)
                    <a href="/tester/export" class="btn confirmExport" data-pesan="Jumlah partisipan masih belum memenuhi minimal partisipan"><img src="{{ asset('img/iconExport.svg') }}">&nbsp;Export Hasil</a>
                @else
                    <a href="/tester/export" class="btn btnExportHasil" data-pesan="Jumlah partisipan masih belum memenuhi minimal partisipan"><img src="{{ asset('img/iconExport.svg') }}">&nbsp;Export Hasil</a>
                @endif
            </div>
        </div>
        <a class="btn btnRed btnClearHasil konfirmasi" href="/tester/clearHasil" data-pesan="Hasil pengujian akan dihapus secara permanen ! (Disarankan melakukan export hasil terlebih dahulu untuk menyimpan hasil yang telah didapatkan)">
            <img src="{{ asset('img/iconClear.svg') }}" alt="">
            &nbsp; Hapus Hasil Pengujian
        </a>
        @else
        <div class="formElement">
                <label>Hasil dari task</label>
                <div class="selectBox">
                    <div class="selectField">
                        <p>Belum Ada Hasil</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(count($detailHasil))
            <div class="blueBody bodyHasil">
                <h2>Akurasi</h2>
                <div class="chartAndTable">
                    <div>
                        <canvas id="chartAkurasi"></canvas>
                    </div>
                    <table class="table tableDetailHasil">
                        <thead>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </thead>
                        <tbody>
                        @if(count($akurasi) != 0  && !$detailHasil->isEmpty())    
                            @php
                                $pembagi = $akurasi[$arrayKey]['sukses'] + $akurasi[$arrayKey]['gagal'] + $akurasi[$arrayKey]['tidak_dijawab']
                            @endphp
                            <tr class="tableKeterangan sukses">
                                <td class="iconAndLabel"><img src="{{ asset('img/iconSukses.svg') }}"> Sukses</td>
                                <td>{{  round($akurasi[$arrayKey]['sukses'], 1) }}</td>
                                <td>{{  round($akurasi[$arrayKey]['sukses']/($pembagi)*100, 1) }}%</td>
                            </tr>
                            <tr class="tableKeterangan gagal">
                                <td class="iconAndLabel"><img src="{{ asset('img/iconGagal.svg') }}"> Gagal</td>
                                <td>{{  round($akurasi[$arrayKey]['gagal'], 1) }}</td>
                                <td>{{  round($akurasi[$arrayKey]['gagal']/($pembagi)*100, 1) }}%</td>
                            </tr>
                            <tr class="tableKeterangan tidakDijawab">
                                <td class="iconAndLabel"><img src="{{ asset('img/iconTidakDijawab.svg') }}"> Tidak Dijawab</td>
                                <td>{{  round($akurasi[$arrayKey]['tidak_dijawab'], 1) }}</td>
                                <td>{{  round($akurasi[$arrayKey]['tidak_dijawab']/($pembagi)*100, 1) }}%</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4" style="text-align: center; height: 100px; background: #E2F3F5;">Belum Ada Hasil</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="blueBody bodyHasil">
                <h2>Directness</h2>
                <div class="chartAndTable">
                    <div>
                        <canvas id="chartDirectness"></canvas>
                    </div>
                    <div>
                        <a class="btn btnDarkBlue" id="btnModalPathTaken"><img src="{{ asset('img/iconDetail.svg') }}">&nbsp;Path Taken</a>
                        <table class="table tableDetailHasil">
                            <thead>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </thead>
                            <tbody>
                            @if(count($directness) != 0)
                                @php
                                    $pembagi = $directness[$arrayKey]['sukses'] + $directness[$arrayKey]['gagal'];
                                @endphp
                                <tr class="tableKeterangan sukses">
                                    <td class="iconAndLabel"><img src="{{ asset('img/iconSukses.svg') }}"> Direct</td>
                                    <td>{{  round($directness[$arrayKey]['sukses'],1) }}</td>
                                    @if($directness[$arrayKey]['sukses'])
                                        <td>{{ round($directness[$arrayKey]['sukses']/($pembagi)*100, 1) }}%</td>
                                    @else
                                        <td>0%</td>
                                    @endif
                                </tr>
                                <tr class="tableKeterangan gagal">
                                    <td class="iconAndLabel"><img src="{{ asset('img/iconGagal.svg') }}"> Indirect</td>
                                    <td>{{  round($directness[$arrayKey]['gagal'], 1) }}</td>
                                    @if($directness[$arrayKey]['gagal'])
                                        <td>{{ round($directness[$arrayKey]['gagal']/($pembagi)*100, 1) }}%</td>
                                    @else
                                        <td>0%</td>
                                    @endif
                                </tr>
                            @else
                                <tr>
                                    <td colspan="4" style="text-align: center; height: 100px; background: #E2F3F5;">Belum Ada Hasil</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="blueBody bodyHasil">
                <h2>Time to Completion</h2>
                <div class="chartAndTable">
                    <div id="chartTime"></div>
                    <div id="keteranganTime">
                        <h3>Keterangan</h3>
                        <h3>Waktu Tercepat</h3>
                        <span id="tercepat"></span>
                        <h3>Waktu Terlambat</h3>
                        <span id="terlambat"></span>
                        <h3>Rata Rata</h3>
                        <span id="rataRata"></span>
                        <h3>Kuartil 1</h3>
                        <span id="kuartil1"></span>
                        <h3>Kuartil 2 (Median)</h3>
                        <span id="kuartil2"></span>
                        <h3>Kuartil 3</h3>
                        <span id="kuartil3"></span>
                    </div>
                </div>
            </div>
        @else
            <div class="blueBody emptyNotice">
                <img src="{{ asset('img/iconEmptyFolder.svg') }}">
                <h1>Masih belum ada hasil</h1>
            </div>
        @endif

    </div>

    <!-- Modal Detail Hasil -->
    <div id="modalDetailHasil" class="modal">
        <div class="modalContent">
            <div class="modalHeader">
                <div class="iconAndLabel">
                    <img src="{{ asset('img/iconDetail.svg') }}">
                    <span>Path Taken</span>
                </div>
                <span class="closeModal">x</span>
            </div>
            <div class="modalBody">
                <div class="keteranganPath">
                    <h3>Task {{ $noTask }}</h3>
                    <span>{{ (count($detailHasil)) ? $task[$arrayKey]->deskripsi : ""; }}</span>
                    <h3>Langkah jawaban : </h3>
                    @if(count($task) != 0 && !$detailHasil->isEmpty())
                        <span>Start -> {{ $task[$arrayKey]->direct_path }}</span>
                    @endif
                    <h3>Langkah paling populer : </h3>
                    @if($popularPath && $popularPath == "Tidak Dijawab")
                        <span>{{ $popularPath }} ({{ $countPopularPath }})</span>
                    @else
                        <span>Start -> {{ $popularPath }} ({{ $countPopularPath }})</span>
                    @endif
                </div>
                <table class="tableDetailHasil">
                    <thead>
                        <th>No</th>
                        <!-- <th>Task</th> -->
                        <th>Id Partisipan</th>
                        <th>Langkah yang dilalui</th>
                    </thead>
                    <tbody>
                        @php 
                            $i=1 
                        @endphp
                        @foreach($detailHasil as $row)
                            @if($row->nomorTask == $noTask)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $row->id_partisipan }}</td>
                                @if($row->direct_path == "Tidak Dijawab")
                                    <td>{{ $row->direct_path }}</td>
                                @else
                                    <td>Start -> {{ $row->direct_path }}</td>
                                @endif
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // continueEvent false agar e.preventDefault dijalankan
        var continueEvent = false;
    
        // jika button export hasil di tekan (dengan konfirmasi)
        $(".confirmExport").click(function(e){
            element = $(this);
            // jika true maka e.preventDefault tidak dijalankan (akan menjalankan href dari tag a)
            if(continueEvent){
                continueEvent = false;
                return;
            }

            // agar href tidak dijalankan
            e.preventDefault();

            pesan = $(this).attr('data-pesan');
            href = $(this).attr('href');

            // sweet alert
            Swal.fire({
                title: 'Informasi',
                text: pesan,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tetap Export',
                cancelButtonText: 'Tidak',
                }).then((result) => {
                if (result.isConfirmed) {
                    // fungsi yg dijalankan
                    $.ajax({
                        url : "/tester/changeStatusExport",
                        type: "get",
                        success : function(res){
                            // merubah continueEvent menjadi true agar tidak dilakukan prevent default
                            continueEvent = true;
                            // menampilkan preloader
                            $(".preloader").css('visibility', 'visible');
                            // menghilangkan preloader setelah 5 detik
                            setTimeout(() => $(".preloader").css('visibility', 'hidden') , 6000);
                            // menjalankan href
                            element[0].click();
                            
                        },
                        error : function(request, status, error){
                            console.log(request.responseText);
                        }
                    });
                }
            });
        });

        // jika button export hasil di tekan
        $(".btnExportHasil").click(function(e){
            element = $(this);
            // jika true maka e.preventDefault tidak dijalankan (akan menjalankan href dari tag a)
            if(continueEvent){
                continueEvent = false;
                return;
            }

            // agar href tidak dijalankan
            e.preventDefault();

            $.ajax({
                url : "/tester/changeStatusExport",
                type: "get",
                success : function(res){
                    // merubah continueEvent menjadi true agar tidak dilakukan prevent default
                    continueEvent = true;
                    // menampilkan preloader
                    $(".preloader").css('visibility', 'visible');
                    // menghilangkan preloader setelah 5 detik
                    setTimeout(() => $(".preloader").css('visibility', 'hidden') , 6000);
                    // menjalankan href
                    element[0].click();
                    
                },
                error : function(request, status, error){
                    console.log(request.responseText);
                }
            });
        });

        // menghitung jumlah task
        countTask = <?= count($task) ?>

        $(".optionsTask").click(function(){
            // memasukan no task kedalam input value noTask
            $("#noTask").attr("value", $(this).attr("id"))
            // inisilaisasi triger change agar dapat menggunakan on change function 
            $("#noTask").val($(this).attr("id")).trigger('change')
        })

        // fungsi ketika select diubah
        $("#noTask").change(function(){
            window.location.href = "/tester/hasilPengujian/"+$(this).attr('value')
        })

        // selectBox dari pilih task
        customSelectbox('#task','#selectTask','#optionListTask','.optionsTask');
        // modalDetailHasil
        openCloseModal("#btnModalPathTaken", "#modalDetailHasil");      
        
        // === Chart Akurasi ===
        dataAkurasi = [
            <?= (count($akurasi)) ? $akurasi[$arrayKey]['sukses'] : 0 ; ?>, 
            <?= (count($akurasi)) ? $akurasi[$arrayKey]['gagal'] : 0 ; ?>, 
            <?= (count($akurasi)) ? $akurasi[$arrayKey]['tidak_dijawab'] : 0 ; ?>
        ];

        const akurasi = {
            labels: [
                'Sukses',
                'Gagal',
                'Tidak Dijawab'
            ],
            datasets: [{
                label: 'Akurasi',
                data: dataAkurasi,
                backgroundColor: [
                    '#00BB07',
                    '#FF0000',
                    '#4F4F4F'
                ],
                hoverOffset: 4
            }]
        };

        const configAkurasi = {
            type: 'pie',
            data: akurasi,
        };

        const chartAkurasi = new Chart(
            document.getElementById('chartAkurasi'),
            configAkurasi
        );

        // === Chart Directness ===
        dataDirectness = [
            <?= (count($directness)) ? $directness[$arrayKey]['sukses'] : 0 ; ?>, 
            <?= (count($directness)) ? $directness[$arrayKey]['gagal'] : 0 ; ?>
        ];

        const directness = {
            labels: [
                'Direct',
                'Indirect'
            ],
            datasets: [{
                label: 'Directness',
                data: dataDirectness,
                backgroundColor: [
                    '#00BB07',
                    '#FF0000'
                ],
                hoverOffset: 4
            }]
        };

        const configDirectness = {
            type: 'pie',
            data: directness,
        };

        const chartDirectness = new Chart(
            document.getElementById('chartDirectness'),
            configDirectness
        );

        // === Chart Time to Completion ===
        timeToCompletion = <?= (count($timeToCompletion)) ? json_encode($timeToCompletion[$arrayKey]) : json_encode([]) ; ?> 
            
        // mengambil data boxplot berdasarkan array time to completion
        let boxplotData = getBoxPlotData(timeToCompletion);
        
        // mengisi value untuk keterangan
        $("#tercepat").text((boxplotData.minimum != "Infinity") ? boxplotData.minimum+" Detik" : "Tidak ada");
        $("#terlambat").text((boxplotData.maksimum != "-Infinity") ? boxplotData.maksimum+" Detik" : "Tidak ada")
        $("#rataRata").text((boxplotData.average) ? boxplotData.average.toFixed(1)+" Detik" : "Tidak ada")
        $("#kuartil1").text((boxplotData.q1) ? boxplotData.q1+" Detik" : "Tidak ada")
        $("#kuartil2").text((boxplotData.median) ? boxplotData.median+" Detik" : "Tidak ada")
        $("#kuartil3").text((boxplotData.q3) ? boxplotData.q3+" Detik" : "Tidak ada")

        var options = {
            chart: {
                type: 'boxPlot',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            series: [{
                data: [{
                    x: "Time to Completion",
                    y: [
                        boxplotData.minimum, // Min Whisker
                        boxplotData.q1, // Lower Quartile (25%)
                        boxplotData.median, // Median
                        boxplotData.q3, // Upper Quartile (75%)
                        boxplotData.maksimum, // Max Whisker
                    ]
                }]
            }],
            plotOptions: {
                bar: {
                    horizontal: true
                }
            },
            grid: {
                show: true,
                borderColor: '#20295c',
                opacity: '.8'
            }
        }

        var chartTime = new ApexCharts(document.querySelector("#chartTime"), options);

        chartTime.render();

    </script>
@endsection