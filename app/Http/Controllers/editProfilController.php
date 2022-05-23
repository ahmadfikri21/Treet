<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class editProfilController extends Controller
{
    public function prosesEditProfil(Request $request){
        $validate = $request->validate([
            'nama' => 'required|min:3|max:255',
            'username' => [
                'required',
                'min:3',
                'max:255',
                Rule::unique('user')->ignore($request->session()->get('id_user'),'id_user')
            ],
            'email' => [
                'required',
                'email:dns',
                'min:3',
                'max:255',
                Rule::unique('user')->ignore($request->session()->get('id_user'),'id_user')
            ],
            'profile_pic' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);
        
        // mengambil nama image lama(sebagai imagename default)
        $oldImage = User::select('profile_pic')
                        ->where('id_user','=', $request->session()->get('id_user'))
                        ->first();
        $imageName = $oldImage->profile_pic;
        
        if($request->hasFile('profile_pic')){
            // untuk cek apakah sebelumnya ada profil picture atau tidak
            if($imageName){
                // menghapus image lama
                unlink('img/userImages/'.$oldImage->profile_pic);
            }
            // mengambil value dari input type file
            $image = $request->file('profile_pic');
            // mengganti nama menjadi tanggal upload+nama gambar
            $imageName = time()."-".$image->getClientOriginalName();
            // memindahkan foto ke folder img/userImages
            $image->move('img/userImages/', $imageName);
        }
        
        $edit = User::where('id_user', '=', $request->session()->get('id_user'))
                ->update([
                    'nama' => $request->nama,
                    'username' => $request->username,
                    'email' => $request->email,
                    'profile_pic' => $imageName
                ]);

        if($edit){
            $request->session()->put('nama', $request->nama);
            $request->session()->put('email', $request->email);
            $request->session()->put('profile_pic', $imageName);

            // jika merupakan akun partisipan atau tester
            if($request->role == "Partisipan"){
                return redirect('/partisipan/editProfile')->with('success','Profil berhasil diubah !');
            }else{
                return redirect('/tester/editProfil')->with('success','Profil berhasil diubah !');
            }
        }

        // jika merupakan akun partisipan atau tester
        if($request->role == "Partisipan"){
            return redirect('/partisipan/editProfile')->with('failed','Terjadi Kesalahan, Profil Gagal Untuk Diubah !');
        }else{
            return redirect('/tester/editProfil')->with('failed','Terjadi Kesalahan, Profil Gagal Untuk Diubah !');
        }
    }

    public function gantiPassword(Request $request){
        $validate = $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        $getPass = User::select('password')->where('id_user','=',$request->session()->get('id_user'))->first();

        if(Hash::check($request->old_password, $getPass->password)){
            $newPass = Hash::make($request->password);

            $edit = User::where('id_user', '=', $request->session()->get('id_user'))
                ->update(['password' => $newPass]);

            if($edit){ 
                // jika merupakan akun partisipan atau tester
                if($request->role == "Partisipan"){
                    return redirect('/partisipan/editProfile')->with('success','Password berhasil diubah !');
                }else{
                    return redirect('/tester/editProfil')->with('success','Password berhasil diubah !');
                }   
            }
            // jika merupakan akun partisipan atau tester
            if($request->role == "Partisipan"){
                return redirect('/partisipan/editProfile')->with('failed','Terjadi Kesalahan, Password Gagal Untuk Diubah !');
            }else{
                return redirect('/tester/editProfil')->with('failed','Terjadi Kesalahan, Password Gagal Untuk Diubah !');
            }
        }
    }
}
