<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\partisipan;
use App\Models\tester;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function __construct(){
        // // inisialisasi model
        // $this->Main_Model = new Main_Model();
    }

    public function index(){
        $data['title'] = "Tree Testing";

        return view("mainpage.landing",$data);
    }

    public function login(){
        $data['title'] = "Login | Tree Testing";

        return view("mainpage.login",$data);
    }

    public function loginProcess(Request $request){
        // form validation
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // mencari apakah username ada atau tidak
        $user = User::where('username', '=', $request->username)->first(); 
        // mencari apakah user partisipan atau tester jika user terdaftar
        $partisipan = false;
        $tester = false;
        if($user){
            $partisipan = Partisipan::where('id_user', '=', $user->id_user)->first();
            $tester = Tester::where('id_user', '=', $user->id_user)->first();
        }

        if($user){
            // mengecek hash password sesuai atau tidak
            if(Hash::check($request->password, $user->password)){
                // memasukkan data user kedalam session
                $request->session()->put('id_user',$user->id_user);
                $request->session()->put('nama',$user->nama);
                $request->session()->put('email',$user->email);
                $request->session()->put('profile_pic',$user->profile_pic);

                // jika user adalah partisipan maka akan diarahkan ke halaman partisipan, dan sebaliknya
                if($partisipan){
                    $request->session()->put('role','Partisipan');
                    $request->session()->put('id_partisipan', $partisipan->id_partisipan);

                    return redirect('/partisipan/home');
                }else if($tester){
                    $request->session()->put('role','Tester');
                    $request->session()->put('kode_pengujian', $tester->kode_pengujian);
                    $request->session()->put('kode_string', $tester->kode_string);
                    $request->session()->put('informasi_pengujian', $tester->nama_website);
                
                    return redirect('tester/konfigurasiTree');
                }
            }else{
                return back()->with('failed','Password salah !');
            }
        }else{
            return back()->with('failed','Username tidak terdaftar !');
        }
    }

    public function logout(Request $request){
        if(Session()->has('id_user')){
            $request->session()->flush();

            return redirect('/login');
        }
    }
    
    public function register(){
        $data['title'] = "Register | Tree Testing";

        return view("mainpage.register",$data);
    }

    public function storeRegister(Request $request){
        // form validation
        $validate = $request->validate([
            'role' => 'required',
            'nama' => 'required|min:3|max:255',
            'username' => 'required|unique:user|min:3|max:255',
            'email' => 'required|unique:user|email:dns|min:3|max:255',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'profile_pic' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        // enkripsi password
        $validate['password'] = Hash::make($validate['password']);

        // insert data ke database user
        $user = new User();
        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $validate['password'];

        if($request->hasFile('profile_pic')){
            // mengambil value dari input type file
            $image = $request->file('profile_pic');
            // mengganti nama menjadi tanggal upload+nama gambar
            $imageName = time()."-".$image->getClientOriginalName();
            // memindahkan foto ke folder img/userImages
            $image->move('img/userImages/', $imageName);
            // memasukkan nama gambar ke database
            $user->profile_pic = $imageName;
        }
        $q1 = $user->save();
        // status tester dan partisipan
        $q2 = false;
        $q3 = false;

        // insert data sesuai dengan role, partisipan atau tester
        if($validate['role'] == 'Partisipan'){
            $partisipan = new Partisipan();
            $partisipan->id_user = $user->id;
            $q2 = $partisipan->save();
        }else if($validate['role'] == "Tester"){
            $tester = new Tester();
            $tester->id_user = $user->id;
            $tester->kode_string = Str::random(3);
            $q3 = $tester->save();
        }

        // menampilkan message terkait insert data
        if(($q1 && $q2) || ($q1 && $q3)){
            return redirect('/login')->with('success','Registrasi Berhasil Dilakukan !');
        }else{
            return back()->with('failed','Gagal Melakukan Registrasi, Coba Beberapa Saat Lagi');
        }
    }
}
