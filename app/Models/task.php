<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class task extends Model
{
    protected $table = 'task';
    public $timestamps = false;
    
    use HasFactory;

    // mengambil data task yang telah dilakukan join dengan tree
    public static function getTaskJoin($kodePengujian, $paginate = false){
        // left join untuk mengambil semua data task beserta data yg tidak memiliki id_node (jawaban)
        if($paginate){
            return DB::table('task')
                    ->leftjoin('tree','task.id_node','=','tree.id_node')
                    ->select('task.*','tree.nama_node')
                    ->where('task.kode_pengujian', '=', $kodePengujian)
                    ->simplePaginate($paginate);
        }else{
            return DB::table('task')
                    ->leftjoin('tree','task.id_node','=','tree.id_node')
                    ->select('task.*','tree.nama_node')
                    ->where('task.kode_pengujian', '=', $kodePengujian)
                    ->get();
        }
    }
}
