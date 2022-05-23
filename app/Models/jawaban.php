<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class jawaban extends Model
{
    protected $table = 'jawaban';
    public $timestamps = false;
    
    use HasFactory;

    public static function getDetailHasil($kodePengujian){
        return DB::table('user')
                ->join('partisipan','user.id_user','=','partisipan.id_user')
                ->join('jawaban','jawaban.id_partisipan','=','partisipan.id_partisipan')
                ->join('task','jawaban.id_task','=','task.id_task')
                ->select(DB::raw('task.id_task,
                            task.deskripsi, 
                            partisipan.id_partisipan,
                            CASE 
                                WHEN jawaban.jawaban = task.id_node
                                THEN "Iya"
                                WHEN jawaban.jawaban IS NULL
                                THEN "NULL"
                                ELSE "Tidak"
                            END AS "accuracy",
                            CASE 
                                WHEN jawaban.path = task.direct_path AND jawaban.jawaban = task.id_node
                                THEN "Iya" 
                                WHEN jawaban.path != task.direct_path AND jawaban.jawaban = task.id_node
                                THEN "Tidak"
                                ELSE "NULL"
                            END AS "directness",
                            jawaban.path AS "direct_path", 
                            waktu'))
                ->where('jawaban.kode_pengujian', '=' , $kodePengujian)
                ->orderBy('id_task')
                ->get();
    }

    public static function addNomorTask($detailHasil, $id_task){
        // counter nomor task
        $nomorTask = 1;

        // melakukan looping untuk setiap id
        foreach($id_task as $id){
            // melakukan loopin setiap detail hasil
            foreach($detailHasil as $row){
                // jika id task pada detail hasil sama dengan id_task, maka akan ditambahkan nomor task sesuai urutan
                if($row->id_task == $id['id_task']){
                    $row->nomorTask = $nomorTask;
                }
            }
            // nomor task selalu bertambah setiap iterasi id
            $nomorTask += 1;
        }
        
        return $detailHasil;
    }

    // fungsi untuk konversi path dari id node ke nama node
    public static function convertPathIdToNama($detailHasil){
        // looping array detail hasil
        foreach ($detailHasil as $data) {
            // jika data path tidak null (dijawab)
            if($data->direct_path){
                // memecah string path yang berisi id node berdasarkan panah
                $id_node = explode("->",$data->direct_path); 
                // menyatukan id node yang telah dipecah untuk order by fungsi sql
                $idNodeOrder = implode(',', $id_node);
                // mengambil path name berdasarkan id dengan urutan order by sesuai dengan id node diatas
                $pathName = DB::table('tree')->select('nama_node')
                            ->wherein('id_node', $id_node)
                            ->orderByRaw(DB::raw("FIELD(id_node, $idNodeOrder)"))
                            ->get();
                // array yang menyimpan path yang telah diubah ke nama_node
                $newPath = [];
                // mengambil setiap nama node yang telah didapat dari database
                foreach($pathName as $node){
                    // memasukkan tiap nama node baru ke arry newPath
                    array_push($newPath, $node->nama_node);
                }
                $newPath = implode(' -> ', $newPath);
                $data->direct_path = $newPath;
            }else{
                $data->direct_path = "Tidak Dijawab";
            }
        }
        
        return $detailHasil;
    }

    public static function countHasil($detailHasil, $metrik, $getIdTask){
        // mengambil id task dari collection detail hasil
        $id_task = [];
        foreach ($getIdTask as $row) {
            array_push($id_task, $row->id_task);
        }
        // menghapus duplikat
        $id_task = array_unique($id_task);
        // melakukan reset index
        $id_task = array_values($id_task);

        // inisialisasi array 2d
        $accuracy = [];
        // counter array 2d
        $j = 0;
        // perhitungan akurasi
        $sukses = 0;
        $gagal = 0;
        $tidakDijawab = 0;
        $kondisi = "";

        // melakukan perhitungan jumlah akurasi
        foreach($id_task as $id){
            foreach($detailHasil->where('id_task', $id) as $hasil){
                // untuk mengecek yang dihitung apakah akurasi atau directness
                if($metrik == "akurasi"){
                    $kondisi = $hasil->accuracy;
                }else if($metrik == "directness"){
                    $kondisi = $hasil->directness;
                }

                if($kondisi == "Iya"){
                    $sukses++;
                }else if($kondisi == "Tidak"){
                    $gagal++;
                }else{
                    $tidakDijawab++;
                }
            }
            // memasukkan hasil perhitungan kedalam array 2d
            array_push($accuracy,[]);
            array_push($accuracy[$j],$id);
            array_push($accuracy[$j],$sukses);
            array_push($accuracy[$j],$gagal);
            array_push($accuracy[$j],$tidakDijawab);
            array_push($accuracy[$j],$hasil->deskripsi);
            array_push($accuracy[$j],$hasil->nomorTask);
 
            // melakukan rename pada key dari angka menjadi huruf
            $accuracy[$j]["id_task"] = $accuracy[$j][0];
            $accuracy[$j]["sukses"] = $accuracy[$j][1];
            $accuracy[$j]["gagal"] = $accuracy[$j][2];
            $accuracy[$j]["tidak_dijawab"] = $accuracy[$j][3];
            $accuracy[$j]["deskripsi"] = $accuracy[$j][4];
            $accuracy[$j]["nomor_task"] = $accuracy[$j][5];

            // menghapus key yang berupa angka
            unset($accuracy[$j][0]);
            unset($accuracy[$j][1]);
            unset($accuracy[$j][2]);
            unset($accuracy[$j][3]);
            unset($accuracy[$j][4]);
            unset($accuracy[$j][5]);

            // melakukan reset perhitungan
            $sukses = 0;
            $gagal = 0;
            $tidakDijawab = 0;
            // melakukan penambahan counter untuk array 2d
            $j++;
        }
        return $accuracy;
    }

    public static function timeToCompletion($detailHasil){
        // mengambil id task dari collection detail hasil
        $id_task = [];
        foreach ($detailHasil as $row) {
            array_push($id_task, $row->id_task);
        }

        // menghapus duplikat
        $id_task = array_unique($id_task);
        // melakukan reset index
        $id_task = array_values($id_task);

        $time = [];
        // counter untuk array 2d
        $j = 0;
        
        foreach($id_task as $id){
            // counter untuk array 3d
            $i = 0;
            array_push($time,[]);
            // melakukan looping berdasarkan tiap id
            foreach($detailHasil->where('id_task', $id) as $hasil){
                // memasukan id partisipan beserta dengan waktu pengerjaan ke array time
                array_push($time[$j],[]);
                array_push($time[$j][$i],$hasil->id_partisipan);
                array_push($time[$j][$i],$hasil->waktu);

                $i++;
            }

            // melakukan penambahan counter untuk array 2d
            $j++;
        }
        // dd($time);
        return $time;
    }

}
