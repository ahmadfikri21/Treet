@php
    $jumlahTask = count($hasil['akurasi']);
    
    date_default_timezone_set("Asia/Jakarta");
@endphp
<!-- informasi Pengujian -->
<table>
    <tr></tr>
    <tr>
        <!-- td agar ada space disebelah kiri -->
        <td></td>
        <td colspan="11" align="center"><h2><strong>Hasil Pengujian Tree</strong></h2></td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td colspan="5"><h2><strong>Diexport pada : {{ getTodayTimestamp() }} WIB</strong></h2></td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td colspan="3"><strong>Nama Tester</strong></td>
        <td>: {{ Session::get('nama') }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3"><strong>Nama Website</strong></td>
        <td>: {{ $informasiPengujian['nama_website'] }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3"><strong>Scope Pengujian</strong></td>
        <td>: {{ $informasiPengujian['scope_pengujian'] }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3"><strong>Profil Partisipan Pengujian</strong></td>
        <td>: {{ $informasiPengujian['profil_partisipan'] }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3"><strong>Jumlah Minimal Partisipan</strong></td>
        <td>: {{ $informasiPengujian['minimal_partisipan'] }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3"><strong>Tanggal Mulai Pengujian</strong></td>
        <td>: {{ $informasiPengujian['mulai_pengujian'] }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3"><strong>Tanggal Akhir Pengujian</strong></td>
        <td>: {{ $informasiPengujian['akhir_pengujian'] }}</td>
    </tr>
</table>

@for ($i = 0; $i < $jumlahTask ; $i++)
    @php
        $akurasi = $hasil['akurasi'][$i];
        $directness = $hasil['directness'][$i];
        $time = $hasil['time'][$i];
        $noTask = $hasil['akurasi'][$i]['nomor_task'];
        
        $popular = findPopularPath($detailHasil, $noTask);

        $popularPath = $popular['path'][0];
        $countPopularPath = $popular['jumlah'][0];

        $pembagiAkurasi = $akurasi['sukses'] + $akurasi['gagal'] + $akurasi['tidak_dijawab'];
        if($akurasi['sukses']){
            $pembagiDirectness = $akurasi['sukses'];
        }else{
            $pembagiDirectness = 1;
        }
    @endphp
    <td></td>
    <td><h2>Task {{ $noTask }}</h2></td>
    <tr>
        <th></th>
        <th colspan="3"><strong>Deskripsi Task</strong></th>
        <th colspan="14" rowspan="2" style="word-wrap: break-word;" valign="top">: {{ $hasil['akurasi'][$i]['deskripsi'] }}</th>t
    </tr>
    <tr></tr>
    <tr>
        <th></th>
        <th colspan="3"><strong>Langkah jawaban </strong></th>
        <th>: Start -> {{ $task[$i]->direct_path }}</th>
    </tr>
    <tr>
        <th></th>
        <th colspan="3"><strong>Langkah paling populer </strong></th>
        <th>: Start -> {{ $popularPath }} ({{ $countPopularPath }})</th>
    </tr>
    <table>
        <thead>
            <tr></tr>
            <tr></tr>
            <tr>
                <th></th>
                <th colspan='5' align="center" style="border: 1px solid #000000;"><strong>Akurasi</strong></th>
                <th></th>
                <th colspan='5' align="center" style="border: 1px solid #000000;"><strong>Directness</strong></th>
                <th></th>
                <th colspan='4' align="center" style="border: 1px solid #000000;"><strong>Time to Completion</strong></th>
            </tr>
            <tr>
                <!-- Header Akurasi -->
                <th></th>
                <th colspan="2" align="center" style="border: 1px solid #000000;"><strong>Keterangan</strong></th>
                <th align="center" style="border: 1px solid #000000;"><strong>Jumlah</strong></th>
                <th colspan="2" align="center" style="border: 1px solid #000000;"><strong>Persentase</strong></th>
                <!-- Header Directness -->
                <th></th>
                <th colspan="2" align="center" style="border: 1px solid #000000;"><strong>Keterangan</strong></th>
                <th align="center" style="border: 1px solid #000000;"><strong>Jumlah</strong></th>
                <th colspan="2" align="center" style="border: 1px solid #000000;"><strong>Persentase</strong></th>
                <th></th>
                <th colspan="2" align="center" style="border: 1px solid #000000;"><strong>Waktu Tercepat</strong></th>
                <th colspan="2" align="center" style="border: 1px solid #000000;">{{ $time['minimum'] }}</th>
                
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td></td>
                    <td colspan="2" style="border: 1px solid #000000; color: #007A37; font-weight:bold;">Sukses</td>
                    <td align="center" style="border: 1px solid #000000; color: #007A37; font-weight:bold;">{{  round($akurasi['sukses'], 1) }}</td>
                    <td align="center" colspan="2" style="border: 1px solid #000000; color: #007A37; font-weight:bold;">{{  round($akurasi['sukses']/($pembagiAkurasi)*100, 1) }}%</td>
                    <td></td>
                    <td colspan="2" style="border: 1px solid #000000; color: #007A37; font-weight:bold;">Direct</td>
                    <td align="center" style="border: 1px solid #000000; color: #007A37; font-weight:bold;">{{  round($directness['sukses'], 1) }}</td>
                    <td align="center" colspan="2" style="border: 1px solid #000000; color: #007A37; font-weight:bold;">{{  round($directness['sukses']/($pembagiDirectness)*100, 1) }}%</td>
                    <td></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;"><strong>Waktu Terlambat</strong></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;">{{ $time['maksimum'] }}</td>

                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" style="border: 1px solid #000000; color: #FF0000; font-weight: bold;">Gagal</td>
                    <td align="center" style="border: 1px solid #000000; color: #FF0000; font-weight: bold;">{{  round($akurasi['gagal'], 1) }}</td>
                    <td align="center" colspan="2" style="border: 1px solid #000000; color: #FF0000; font-weight: bold;">{{  round($akurasi['gagal']/($pembagiAkurasi)*100, 1) }}%</td>
                    <td></td>
                    <td colspan="2" style="border: 1px solid #000000; color: #FF0000; font-weight: bold;">Indirect</td>
                    <td align="center" style="border: 1px solid #000000; color: #FF0000; font-weight: bold;">{{  round($directness['gagal'], 1) }}</td>
                    <td align="center" colspan="2" style="border: 1px solid #000000; color: #FF0000; font-weight: bold;">{{  round($directness['gagal']/($pembagiDirectness)*100, 1) }}%</td>
                    <td></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;"><strong>Rata-rata</strong></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;">{{ $time['average'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" style="border: 1px solid #000000;">Tidak Dijawab</td>
                    <td align="center" style="border: 1px solid #000000;">{{  round($akurasi['tidak_dijawab'], 1) }}</td>
                    <td align="center" colspan="2" style="border: 1px solid #000000;">{{  round($akurasi['tidak_dijawab']/($pembagiAkurasi)*100, 1) }}%</td>
                    <td></td>
                    <td colspan="2"><strong>Total</strong></td>
                    <td align="center"><strong>{{ $directness['sukses'] + $directness['gagal'] }}</strong></td>
                    <td colspan="2" align="center"><strong>{{ ($directness['sukses'] + $directness['gagal']== 0) ? "0%" : "100%" }}</strong></td>
                    <td></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;"><strong>Kuartil 1</strong></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;">{{ $time['q1'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2"><strong>Total</strong></td>
                    <td align="center"><strong>{{ $akurasi['sukses'] + $akurasi['gagal'] + $akurasi['tidak_dijawab'] }}</strong></td>
                    <td colspan="2" align="center"><strong>100%</strong></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;"><strong>Kuartil 2 (Median)</strong></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;">{{ $time['median'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;"><strong>Kuartil 3</strong></td>
                    <td colspan="2" align="center" style="border: 1px solid #000000;">{{ $time['q3'] }}</td>
                </tr>
                <tr></tr>
                <tr>
                    <td></td>
                    <td align="center" style="border-bottom: 1px solid #000000;"><strong>No</strong></td>
                    <td colspan="2" align="center" style="border-bottom: 1px solid #000000;"><strong>Id Partisipan</strong></td>
                    <td colspan="13" align="center" style="border-bottom: 1px solid #000000;"><strong>Langkah yang Dilalui</strong></td>
                </tr>
                @php 
                    $j=1 
                @endphp
                @foreach($detailHasil->where('nomorTask', $noTask) as $row)
                    <tr>
                        <td></td>
                        @if($j % 2 == 0)
                            <td align="center" style='background-color: #CCCCFF;'>{{ $j++ }}</td>
                            <td colspan="2" align="center" style='background-color: #CCCCFF;'>{{ $row->id_partisipan }}</td>
                            @if($row->direct_path == "Tidak Dijawab")
                                <td colspan="13" style='background-color: #CCCCFF;'>{{ $row->direct_path }}</td>
                            @else
                                <td colspan="13" style='background-color: #CCCCFF;'>Start -> {{ $row->direct_path }}</td>
                            @endif
                        @else
                            <td align="center">{{ $j++ }}</td>
                            <td colspan="2" align="center">{{ $row->id_partisipan }}</td>
                            @if($row->direct_path == "Tidak Dijawab")
                                <td colspan="13" >{{ $row->direct_path }}</td>
                            @else
                                <td colspan="13">Start -> {{ $row->direct_path }}</td>
                            @endif

                        @endif
                    </tr>
                @endforeach
        </tbody>
    </table>
@endfor


