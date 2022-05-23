<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tree;
use App\Models\task;
use App\Models\tester;
use App\Models\User;
use App\Models\jawaban;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Input;

class PartisipanController extends Controller
{
    public function __construct(){

    }

    public function index(Request $request){
        $data['title'] = "Home | Tree Testing";
        $data['userData'] = [
            "id_user" => $request->session()->get('id_user'),
            "nama" => $request->session()->get('nama'),
            "role" => $request->session()->get('role')
        ];

        return view("partisipan.homePartisipan", $data);
    }

    public function ikutPengujian(Request $request){
        $data['title'] = "Ikuti Pengujian | Tree Testing";

        return view("partisipan.ikutPengujian", $data);
    }

    public function mulaiPengujian(Request $request){
        // form validation
        $validate = $request->validate([
            'kodePengujian' => 'required'
        ]);
        
        $kode_pengujian = $validate['kodePengujian'];
        // mengambil kode string dari $kode_pengujian
        $kode_string = substr($kode_pengujian,0,3);
        // mengambil angka kode pengujian
        $kode_pengujian = substr($kode_pengujian,3);
        
        // mengecek apakah kode pengujian dan kode string ada di database
        if(Tester::where('kode_pengujian','=', $kode_pengujian)->where('kode_string','=', $kode_string)->get()->isNotEmpty()){
            // mengambil informasi pengujian dari database tester
            $informasiPengujian = Tester::where('kode_pengujian','=', $kode_pengujian)->first();
            // mengecek apakah sekarang dalam masa pengujian atau tidak
            TesterController::updateStatusPengujian($informasiPengujian['mulai_pengujian'], $informasiPengujian['akhir_pengujian'], $kode_pengujian);

            // Jika tree telah dibuat dan sedang dalam masa pengujian
            if(Tree::where('kode_pengujian',$kode_pengujian)->get()->isNotEmpty() && Task::where('kode_pengujian',$kode_pengujian)->get()->isNotEmpty() && Tester::where('kode_pengujian','=', $kode_pengujian)->where('status_pengujian','=', 1)->get()->isNotEmpty()){
                $id_partisipan = $request->session()->get('id_partisipan');
                // mengecek apakah partisipan telah menjawab sebelumnya
                if(Jawaban::where('kode_pengujian', '=', $kode_pengujian)->where('id_partisipan',$id_partisipan)->get()->isEmpty()){
                    $data['title'] = "Soal | Tree Testing";
                    // memanggil model tree fungsi bangun tree(menghasilkan object tree yang berisi node beserta childnya)
                    $tree = Tree::bangunTree($kode_pengujian);
                    // mengambil node yang memiliki parent_node null (akan digunakan untuk fungsi recursive)
                    $data['parentNode'] = $tree->whereNull('parent_node');
                    // mengambil data task dari model task
                    $data['task'] = Task::where('kode_pengujian', '=', $kode_pengujian)->get();
                    
                    $request->session()->put('sedangPengujian',$kode_pengujian);
        
                    return view("partisipan.halamanSoal", $data);
                }else{
                    return redirect('partisipan/ikutiPengujian')->with('failed','Anda telah mengikuti pengujian dengan kode ini sebelumnya !');
                }
            }else{
                return redirect('partisipan/ikutiPengujian')->with('failed','Pengujian belum dimulai oleh tester');
            }
        }else{
            return redirect('partisipan/ikutiPengujian')->with('failed','Kode pengujian salah !');
        }
            
    }

    public function storeJawaban(Request $request){
        // mengambil jawaban yang telah dikirimkan melalui ajax
        $jawaban = json_decode($request->getContent());

        if($jawaban){
            // $insert = new Jawaban();
            foreach ($jawaban as $data) {
                $path = $data->path;
                // jika path berupa array(ada pathnya) maka akan diubah menjadi string, jika path bukan array, maka akan diisi dengan id node(karena jawaban memang tidak memiliki path) 
                if(is_array($data->path)){
                    $path = implode("->",$data->path);
                }else{
                    $path = $data->id_node;
                }

                DB::table('jawaban')->insert([
                    'kode_pengujian' => $request->session()->get('sedangPengujian'),
                    'id_task' => $data->id_task,
                    'id_partisipan' => $request->session()->get('id_partisipan'),
                    'jawaban' => $data->id_node,
                    'path' => $path,
                    'waktu' => $data->waktu
                ]);
            }

            return "Berhasil menyimpan data";
        }

        return "Tidak ada data";
    }

    // fungsi untuk menyimpan jawaban ke database, dipanggil oleh request ajax
    public function selesaiPengujian(Request $request, $percobaan = false){
        $request->session()->forget('sedangPengujian');

        // if untuk cek apakah percobaan pengujian atau bukan
        if(!$percobaan){
            return redirect('partisipan/ikutiPengujian')->with('success', 'Selamat ! anda telah menyelesaikan pengujian, silahkan hubungi tester anda untuk mengetahui hasil pengujian secara keseluruhan');
        }else{
            return redirect('partisipan/ikutiPengujian')->with('success', 'Anda telah menyelesaikan percobaan pengujian !');
        }

    }

    public function editProfile(Request $request){
        $data['title'] = "Edit Profile | Tree Testing";
        $data['dataUser'] = User::where('id_user','=',$request->session()->get('id_user'))->first();

        return view("partisipan.editProfile", $data);
    }

    public function percobaanPengujian(Request $request){
        $data['title'] = "Percobaan Pengujian | Treet";
        $kode_pengujian = 2;
        // memanggil model tree fungsi bangun tree(menghasilkan object tree yang berisi node beserta childnya)
        $tree = Tree::bangunTree($kode_pengujian);
        // mengambil node yang memiliki parent_node null (akan digunakan untuk fungsi recursive)
        $data['parentNode'] = $tree->whereNull('parent_node');
        // mengambil data task dari model task
        $data['task'] = Task::where('kode_pengujian', '=', $kode_pengujian)->get();
        
        $request->session()->put('sedangPengujian',$kode_pengujian);

        return view("partisipan.percobaanPengujian", $data);
    }

}

