<?php

namespace App\Http\Controllers;

use App\Models\tree;
use DOMDocument;
use Goutte\Client;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GenerateTree extends Controller
{
    // inisialisasi array 2d dan counternya
    private $hasil = array();
    private $i = 0;

    public function generate(Request $request){
        $validate = $request->validate([
            "url" => 'required',
            "nama_selector" => 'required',
            "atribut" => 'required'
        ]);
        
        // mengambil selector, atribut ,dan url dari request get
        $url = $request->url;
        $atribut = $request->atribut;
        $selector = $request->nama_selector;
        
        // mengambil karakter pertama dalam nama selector
        $firstLetter = substr($selector, 0, 1);
        
        // jika karakter pertama adalah titik atau hashtag, maka karakter pertama akan dihapus
        if($firstLetter == "." || $firstLetter == "#"){
            $selector = substr($selector, 1);
        }
        
        if($atribut == "html"){
            $atribut = "";
        }

        // membuat client dari library dan mengambil halaman berdasarkan url
        $client = new Client();
        $page = $client->request('GET', $url);

        // mengambil element sesuai dengan selector yang dituliskan
        $page->filter($atribut.$selector)->each(function($item){
            // mengambil setiap tag a yang merupakan child dari selector
            $item->filter('a')->each(function($a){
                // menyimpan kode pengujian ke array
                $this->hasil[$this->i]['kode_pengujian'] = session()->get('kode_pengujian');
                // menyimpan nama_node ke array (diambil dari text tag a)
                $this->hasil[$this->i]['nama_node'] = $a->text();
                // kondisi untuk mengecek apakah data pertama atau bukan
                if($this->i == 0){
                    // jika iya, maka urutan diambil berdasarkan urutan terakhir yg ada di database
                    $this->hasil[$this->i]['urutan'] = TesterController::cekUrutanTree();
                }else{
                    // jika tidak, maka urutan diambil berdasarkan urutan terakhir yg ada di value array sebelumnya
                    $this->hasil[$this->i]['urutan'] = $this->hasil[$this->i - 1]['urutan'] + 1;
                }
                // menambahkan counter array 2d
                $this->i++;
            });
        });        

        // jika ada data yg didapat maka akan ada alert berhasil, jika tidak maka akan menampilkan error
        if(count($this->hasil)){
            Tree::insert($this->hasil);
            return redirect('/tester/konfigurasiTree')->with('success','Berhasil mengambil navigasi !');
        }else{
            Alert::error('Gagal !','Tidak dapat mengambil data dari navbar, pastikan url dan selector yang digunakan benar !')->persistent(true);
            return redirect('/tester/konfigurasiTree');
        }


    }
}
