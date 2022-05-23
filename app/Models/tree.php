<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tree extends Model
{
    protected $table = 'tree';
    public $timestamps = false;
    
    use HasFactory;

    public static function bangunTree($kode_pengujian){
        // mengambil semua node dari database dan menyimpannya di collection $allnodes
        $allNodes = Tree::where('kode_pengujian',$kode_pengujian)->orderBy('urutan')->get();

        // mengambil semua parent node dan menyimpannya di variable rootNodes
        $rootNodes = $allNodes->whereNull('parent_node');

        // memanggil fungsi formatTree untuk membangun tree
        self::formatTree($rootNodes, $allNodes);

        return $rootNodes->values();
    }

    // fungsi recursive
    private static function formatTree($rootNodes, $allNodes){
        // looping setiap node yang tidak memiliki parent (rootNode)
        foreach($rootNodes as $rootNode){
            // menyimpan child dari rootnode dengan kondisi, setiap node yang memiliki id yang sama dengan root node
            $rootNode->children = $allNodes->where('parent_node', $rootNode->id_node)->values();

            // jika children yang baru dibuat memiliki child juga, maka akan dipanggil fungsi recursive ini lagi
            if($rootNode->children->isNotEmpty()){
                self::formatTree($rootNode->children, $allNodes);
            }
        }
    }
}
