<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class tester extends Model
{
    protected $table = 'tester';
    public $timestamps = false;
    
    use HasFactory;

    public static function changeStatusPengujian($kode_Pengujian, $status){
        $tanggal = DB::table('tester')
            ->where('kode_pengujian', '=', $kode_Pengujian)
            ->update(['status_pengujian' => $status]);
    }

    // mengambil key waktu dari time to completion
    public static function splitWaktu($time){
        $waktu = [];
        $j = 0;
        foreach($time as $row){
            array_push($waktu, []);
            foreach($row as $row2){
                array_push($waktu[$j], $row2[1]);
            }
            $j++;
        }

        return $waktu;
        
    }

    // melakukan perhitungan boxplot data (q1, q2, q3, average, min ,max)
    public static function getBoxPlotData($array){
        // exclude nilai null pada array
        $array = array_diff($array, array(null));
        // mengurutkan array
        sort($array);
        // menghitung panjang array
        $count = count($array);

        if(count($array)){
            // panjang array dikurang 1 agar menjadi key
            $key = $count-1;
            // jumlah array dibagi 2 lalu dibulatkan
            $tengah = ceil($key/2);
            
            // === Menghitung Q1, Q2, Q3 ===
            $median = 0;
            $q1 = 0;
            $q3 = 0;
            $iqr = 0;
    
            // Rumus Median
            if($count % 2 == 1){
                // rumus jika jumlah array ganjil
                // membagi array untuk menghitung q1 dan q3
                $arrayQ1 = array_slice($array, 0, $tengah);
                $arrayQ3 = array_slice($array, $tengah+1);
    
                $median = $array[$tengah];
            }else{
                // membagi array untuk menghitung q1 dan q3
                $arrayQ1 = array_slice($array , 0, $tengah);
                $arrayQ3 = array_slice($array, $tengah);
                // rumus jika jumlah array genap
                $median = ($array[$tengah-1] + $array[$tengah]) / 2;
            }
    
            if($count != 1){
                // rumus q1
                if(count($arrayQ1) % 2 == 1){
                    $tengah = ceil(count($arrayQ1)/2);
                    // rumus jika jumlah array ganjil
                    $q1 = $arrayQ1[$tengah-1];         
                }else{
                    $tengah = ceil(count($arrayQ1)/2);
                    // rumus jika jumlah array genap
                    $q1 = ($arrayQ1[$tengah-1] + $arrayQ1[$tengah]) / 2;
                }
        
                // rumus q3
                if(count($arrayQ3) % 2 == 1){
                    $tengah = ceil(count($arrayQ3)/2);
                    // rumus jika jumlah array ganjil
                    $q3 = $arrayQ3[$tengah-1]; 
                }else{
                    $tengah = ceil(count($arrayQ3)/2);
                    // rumus jika jumlah array genap
                    $q3 = ($arrayQ3[$tengah-1] + $arrayQ3[$tengah]) / 2;
                }
                
                $iqr = $q3-$q1;
            }else{
                $q1 = $array[0];
                $q3 = $array[0];
            }

            // === Menghitung Rata-rata ===
            $sum = 0;
    
            foreach($array as $data){
                $sum = $sum + $data;
            }
            
            $average = $sum / $count;
            
            // === Mengambil nilai maksimum dan minimum ===
            $minimum = min($array);
            $maksimum = max($array);
    
            // === Memasukkan semua hasil ke object ===
            $hasil = [
                "minimum" => round($minimum,1)." Detik",
                "maksimum" => round($maksimum,1)." Detik",
                "average" => round($average,1)." Detik",
                "q1" => round($q1,1)." Detik",
                "median" => round($median,1)." Detik",
                "q3" => round($q3,1)." Detik",
                "iqr" => round($iqr,1)." Detik"
            ];
            
            return $hasil;
        }else{
            $hasil = [
                "minimum" => "Tidak ada",
                "maksimum" => "Tidak ada",
                "average" => "Tidak ada",
                "q1" => "Tidak ada",
                "median" => "Tidak ada",
                "q3" => "Tidak ada",
                "iqr" => "Tidak ada"
            ];

            return $hasil;
        }
    }

    // menggabungkan hasil akurasi, directness, dan time completion kedalam 1 array
    public static function combineHasil($akurasi, $directness, $time){
        // menyiapkan array hasil dengan key akurasi, directness, dan time
        $hasil['akurasi'] = [];
        $hasil['directness'] = [];
        $hasil['time'] = [];

        for ($i=0; $i < count($akurasi); $i++) { 
            array_push($hasil['akurasi'], $akurasi[$i]);
            array_push($hasil['directness'], $directness[$i]);
            array_push($hasil['time'], $time[$i]);
        }

        return $hasil;
    }
}
