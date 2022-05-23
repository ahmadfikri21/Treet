<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tree;
use App\Models\task;
use App\Models\jawaban;
use App\Models\tester;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\hasil;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TesterController extends Controller
{
    public function index(Request $request){
        $data['title'] = "Konfigurasi Pengujian | Tree Testing";
        $data['task'] = Task::getTaskJoin($request->session()->get('kode_pengujian'), 5);
        // mengambil data tree menggunakan fungsi rekursif
        $data['tree'] = Tree::bangunTree($request->session()->get('kode_pengujian'));
        // mengambil data mulai dan akhir pengujian dari database
        $tanggalPengujian = Tester::where("kode_pengujian", "=", $request->session()->get('kode_pengujian'))->first();

        // memanggil fungsi check status pengujian dengan parameter awal_pengujian, akhir_pengujian, dan kode pengujian (fungsi ini akan mengupdate status pengujian sesuai dengan waktu pelaksanaan)
        if($tanggalPengujian){
            $this->updateStatusPengujian($tanggalPengujian['mulai_pengujian'], $tanggalPengujian['akhir_pengujian'], $request->session()->get('kode_pengujian'));
        }

        // mengambil data informasi pengujian dari database
        $data['informasiPengujian'] = Tester::where("kode_pengujian", "=", $request->session()->get('kode_pengujian'))->first();

        return view("tester.konfigurasiTree", $data);
    }

    // fungsi yang digunakan untuk cek apakah sedang dalam masa pengujian atau tidak
    public static function updateStatusPengujian($startDate, $endDate, $kode_pengujian){
        // mengambil string tanggal hari ini kemudian diubah ke datetime format
        $today = strtotime(date('Y-m-d'));
        // mengambil string tanggal mulai pengujian kemudian diubah ke datetime format
        $startDate = strtotime($startDate);
        // mengambil string tanggal akhir pengujian kemudian diubah ke datetime format
        $endDate = strtotime($endDate);

        // jika hari ini sesuai dengan range awal dan akhir pengujian, maka akan update status pengujian menjadi 1 (true), jika sebaliknya maka 0 (false)
        if(($today >= $startDate) && ($today <= $endDate)){
            // mengambil data task
            $cekTaskReady = Task::where('kode_pengujian', $kode_pengujian)->get();
            // mengambil data tree
            $cekTreeReady = Tree::where('kode_pengujian', $kode_pengujian)->first();

            if($cekTreeReady && $cekTaskReady){
                // cek apakah ada task yang masih belum memiliki jawaban
                if(!count($cekTaskReady->where('id_node', null))){
                    Tester::changeStatusPengujian($kode_pengujian, 1);
                }else{
                    // mengubah status pengujian menjadi 0
                    Tester::changeStatusPengujian($kode_pengujian, 0);
                    // menampilkan alert untuk memberi tahu tester untuk mengisi jawaban
                    session()->flash('error','Sudah memasuki masa pelaksanaan pengujian namun masih ada task yang belum memiliki jawaban ! (Silahkan ubah tanggal pengujian jika memerlukan waktu untuk melakukan konfigurasi pengujian)');
                }
            }else{
                // mengubah status pengujian menjadi 0
                Tester::changeStatusPengujian($kode_pengujian, 0);
                // menampilkan alert untuk memberitahu tester untuk membuat tree/task
                session()->flash('error','Sudah memasuki masa pelaksanaan pengujian namun Tree / Task masih belum ada ! (Silahkan ubah tanggal pengujian jika memerlukan waktu untuk melakukan konfigurasi pengujian)');
            }
        }else{
            Tester::changeStatusPengujian($kode_pengujian, 0);
        }
    }

    public function editInformasi(Request $request){
        $validate = $request->validate([
            "nama_website" => "required",
            "scope_pengujian" => "required",
            "profil_partisipan" => "required",
            "minimal_partisipan" => "required"
        ]);

        $edit = Tester::where('kode_pengujian', '=', $request->session()->get('kode_pengujian'))
                ->update([
                    'nama_website' => $request->nama_website,
                    'scope_pengujian' => $request->scope_pengujian,
                    'profil_partisipan' => $request->profil_partisipan,
                    'minimal_partisipan' => $request->minimal_partisipan,
                    'mulai_pengujian' => $request->tanggal_mulai,
                    'akhir_pengujian' => $request->tanggal_akhir
                ]);

        if($edit){
            return redirect('/tester/konfigurasiTree')->with('success','Informasi pengujian berhasil diubah !');
        }
        Alert::error('Gagal','Terjadi Kesalahan, Informasi Pengujian Gagal Untuk Diubah !');
        return redirect('/tester/konfigurasiTree')->with('failed','Terjadi Kesalahan, Informasi Pengujian Gagal Untuk Diubah !');
    }

    public function storeTask(Request $request){
        $validate = $request->validate([
            'task' => 'required',
            'kriteria' => 'required'
        ]);

        $task = new Task();
        $task->kode_pengujian = $request->session()->get('kode_pengujian');
        $task->deskripsi = $request->task;
        $task->kriteria_task = $request->kriteria;
        $task->id_node = $request->jawaban;
        $task->direct_path = $request->direct_path;
        $query = $task->save();

        if($query){
            return redirect('/tester/konfigurasiTree')->with('success','Task Berhasil Ditambahkan !');
        }
        Alert::error('Gagal','Terjadi kesalahan, Task Gagal Untuk Disimpan !');
        return redirect('/tester/konfigurasiTree');
    }

    public function editTask(Request $request){
        $validate = $request->validate([
            'edit_task' => 'required',
            'edit_kriteria' => 'required'
        ]);
    
        $task = Task::where('id_task', '=', $request->id_task)
                ->update([
                    "deskripsi" => $request->edit_task,
                    "kriteria_task" => $request->edit_kriteria,
                    "id_node" => $request->editJawaban,
                    "direct_path" => $request->direct_path,
                    "updated_at" => getTodayTimestamp()
                ]);


        if($task){
            return redirect('/tester/konfigurasiTree')->with('success','Task Berhasil Diubah !');
        }

        Alert::error('Gagal','Terjadi Kesalahan, Task Gagal Untuk Diubah !');
        return redirect('/tester/konfigurasiTree')->with('failed','Terjadi Kesalahan, Task Gagal Untuk Diubah !');
    }

    public function storeInformasiPengujian(Request $request){
        $validate = $request->validate([
            "nama_website" => "required",
            "scope_pengujian" => "required",
            "profil_partisipan" => "required",
            "minimal_partisipan" => "required"
        ]);

        $store = Tester::where('kode_pengujian', '=', $request->session()->get('kode_pengujian'))
                ->update([
                    'nama_website' => $request->nama_website,
                    'scope_pengujian' => $request->scope_pengujian,
                    'profil_partisipan' => $request->profil_partisipan,
                    'minimal_partisipan' => $request->minimal_partisipan,
                    'mulai_pengujian' => $request->tanggal_mulai,
                    'akhir_pengujian' => $request->tanggal_akhir
                ]);

        // update session informasi pengujian agar dapat melewati middleware
        $request->session()->put('informasi_pengujian', $request->nama_website);

        if($store){
            return redirect('/tester/konfigurasiTree')->with('success','Informasi pengujian berhasil ditambahkan !');
        }
        Alert::error('Gagal','Terjadi Kesalahan, Informasi Pengujian Gagal Untuk Ditambahkan !');
        return redirect('/tester/konfigurasiTree')->with('failed','Terjadi Kesalahan, Informasi Pengujian Gagal Untuk Ditambahkan !');
    }

    public function hapusTask(Request $request, $id_task){
        // kondisi untuk menentukan apakah task dihapus semua atau tidak
        if($id_task != "clear"){
            $delete = Task::where('id_task', '=', $id_task)->delete();
        }else{
            $delete = Task::where('kode_pengujian', '=', $request->session()->get('kode_pengujian'))->delete();
        }

        if($delete){
            return redirect('/tester/konfigurasiTree')->with('success','Task Berhasil Dihapus !');
        }
        
        Alert::error('Gagal','Terjadi Kesalahan, Task Gagal Untuk Dihapus !')->persistent(true);
        return redirect('/tester/konfigurasiTree');
    }

    public function tambahNode(Request $request){
        $tree = new Tree();
        $tree->kode_pengujian = $request->session()->get('kode_pengujian');
        $tree->nama_node = $request->nama_node;
        $tree->urutan = $this->cekUrutanTree();

        if($request->parent_node != ""){
            $tree->parent_node = $request->parent_node;
        }
        $q = $tree->save();

        if($q){
            return true;
        }
        
        return false;
    }

    // fungsi untuk menetukan urutan dari node
    public static function cekUrutanTree(){
        // mengambil baris terakhir pada database tree
        $query = Tree::where('kode_pengujian', session()->get('kode_pengujian'))->latest('urutan')->first();
        
        // jika tidak ada data pada tabel tree, maka urutan dimulai dari satu
        if(!$query){
            return 1;
        }

        // jika ada data, maka urutan dimulai dari urutan terakhir + 1
        return $query['urutan'] + 1;
    }

    public function deleteMultipleNode(Request $request){
        if($request->ids){
            $delete = Tree::wherein('id_node', $request->ids)->delete();

            if($delete){
                return redirect('/tester/konfigurasiTree')->with('success','Tree/Node Berhasil Dihapus !');
            }
            Alert::error('Gagal','Terjadi Kesalahan, Tree Gagal Untuk Dihapus !');
            return redirect('/tester/konfigurasiTree');
        }else{
            Alert::error('Gagal','Belum ada node yang dipilih !');
            return redirect('/tester/konfigurasiTree');
        }
        
    }

    public function hapusTree(Request $request, $id_node){
        // kondisi untuk menentukan apakah task dihapus semua atau tidak
        if($id_node != "clear"){
            // mengambil urutan dari node yg dihapus
            $urutan = Tree::where('id_node', $id_node)->first()['urutan'];
            // menghapus node tersebut
            $delete = Tree::where('id_node', '=', $id_node)->delete();

            // melakukan pengurangan terhadap node yang memiliki urutan yang lebih besar daripada urutan node yang dihapus (agar urutan rapih)
            DB::statement('UPDATE tree SET urutan = urutan - 1 WHERE kode_pengujian = '.$request->session()->get('kode_pengujian').' AND urutan > '.$urutan);
        }else{
            // menghapus semua node pada tree
            $delete = Tree::where('kode_pengujian', '=', $request->session()->get('kode_pengujian'))->delete();
        }

        if($delete){
            return redirect('/tester/konfigurasiTree')->with('success','Tree/Node Berhasil Dihapus !');
        }
        Alert::error('Gagal','Terjadi Kesalahan, Tree Gagal Untuk Dihapus !');
        return redirect('/tester/konfigurasiTree');
    }

    public function editNode(Request $request){
        // setJawaban disini adalah id_task
        $validate = $request->validate([
            "nama_node" => "required"
        ]);

        $edit = Tree::where('id_node', '=', $request->id_node)
                ->update([
                    "nama_node" => $request->nama_node,
                ]);

        if($edit){
            return redirect('/tester/konfigurasiTree')->with('success','Nama node berhasil diubah !');
        }
        Alert::error('Gagal','Terjadi Kesalahan, Nama node gagal Untuk diubah !');
        return redirect('/tester/konfigurasiTree');
    }

    public function editParentNode(Request $request){
        $kode_pengujian = $request->session()->get('kode_pengujian');
        // jika request menggandung swappedNode(node yang ingin ditukar)
        if($request->swappedNode){
            // mengambil urutan node yang sedang di drag (ingin ditukar)
            $urutanDraggedNode = Tree::where('id_node', '=', $request->id_node)->first()['urutan'];
            // mengambil urutan node yang akan ditukar tempatnya
            $urutanSwappedNode = Tree::where('id_node', '=', $request->swappedNode)->first()['urutan'];
            
            // kondisi untuk menentukan apakah node didrag keatas atau kebawah
            if($urutanDraggedNode > $urutanSwappedNode){       
                // jika keatas 
                // mengecek apakah div between merupakan between yg pertama atau tidak
                if($request->firstBetween == "true"){
                    // jika iya
                    // mengganti urutan node yang sedang di drag dengan urutan node yang ingin ditukar
                    Tree::where('id_node', '=', $request->id_node)
                    ->update([
                        "parent_node" => $request->parent_node,
                        "urutan" => $urutanSwappedNode
                    ]);

                    // seluruh value kolom urutan diantara dragged node dan swap node akan ditambah 1 (berikut dengan kolom yang ditukar >=)
                    $edit = DB::statement('UPDATE tree SET urutan = urutan + 1 WHERE kode_pengujian = '.$kode_pengujian.' AND id_node != '.$request->id_node.' AND urutan < '.$urutanDraggedNode.' AND urutan >= '.$urutanSwappedNode);
                }else{
                    // mengganti urutan node yang sedang di drag dengan urutan node yang ingin ditukar + 1(maksud +1 adalah setelah node tersebut)
                    Tree::where('id_node', '=', $request->id_node)
                    ->update([
                        "parent_node" => $request->parent_node,
                        "urutan" => $urutanSwappedNode + 1
                    ]);

                    // seluruh value kolom urutan diantara dragged node dan swap node akan ditambah 1 (tidak dengan kolom yg ditukar >)
                    $edit = DB::statement('UPDATE tree SET urutan = urutan + 1 WHERE kode_pengujian = '.$kode_pengujian.' AND id_node != '.$request->id_node.' AND urutan < '.$urutanDraggedNode.' AND urutan > '.$urutanSwappedNode);
                }
                
            }else{  
                // jika kebawah
                // mengganti urutan node yang sedang didrag dengan urutan node yang ingin ditukar
                Tree::where('id_node', '=', $request->id_node)
                ->update([
                    "parent_node" => $request->parent_node,
                    "urutan" => $urutanSwappedNode
                ]);

                // jika kebawah, maka seluruh value kolom urutan diantara dragged node dan swap node akan dikurangi 1
                $edit = DB::statement('UPDATE tree SET urutan = urutan - 1 WHERE kode_pengujian = '.$kode_pengujian.' AND id_node != '.$request->id_node.' AND urutan >= '.$urutanDraggedNode.' AND urutan <= '.$urutanSwappedNode);
            }
        }else{
            // mengambil urutan node child (yg sedang di drag)
            $urutanDraggedNode = Tree::where('id_node', '=', $request->id_node)->first()['urutan'];
            // mengambil urutan node parent
            $urutanParent = Tree::where('id_node', '=', $request->parent_node)->first()['urutan'];

            // jika urutan child lebih kecil/awal daripada urutan parent
            if($urutanDraggedNode < $urutanParent){
                // jika iya, maka urutan child dan parent akan bertukar
                $edit = Tree::where('id_node', '=', $request->id_node)
                ->update([
                    "parent_node" => $request->parent_node,
                    "urutan" => $urutanParent
                ]);
    
                $edit = Tree::where('id_node', '=', $request->parent_node)
                ->update([
                    "urutan" => $urutanDraggedNode
                ]);
            }else{
                // jika tidak, maka urutan child dan parent tidak bertukar
                $edit = Tree::where('id_node', '=', $request->id_node)
                ->update([
                    "parent_node" => $request->parent_node,
                ]);
            }

        }

        // kondisi jika gagal melakukan perubahan
        if(!$edit){
            Alert::error('Gagal','Gagal melakukan perubahan!');
        }
        
    }
    
    public function setJawaban(Request $request){
        // setJawaban disini adalah id_task
        $validate = $request->validate([
            "setJawaban" => "required"
        ]);

        $task = Task::where('id_task', '=', $request->setJawaban)
                ->update([
                    "id_node" => $request->id_node,
                    "direct_path" => $request->direct_path
                ]);

        if($task){
            return redirect('/tester/konfigurasiTree')->with('success','Jawaban berhasil ditetapkan !');
        }
        Alert::error('Error','Terjadi Kesalahan, Jawaban gagal Untuk ditetapkan !');
        return redirect('/tester/konfigurasiTree');
    }

    public function informasiPengujian(Request $request){
        $data['title'] = 'Informasi Pengujian | Tree Testing';

        return view('tester.informasiPengujian', $data);
    }

    public function hasilPengujian(Request $request, $noTask = false){
        $data['title'] = "Hasil Pengujian | Tree Testing";
        $data['task'] = Task::getTaskJoin($request->session()->get('kode_pengujian'));
        $data['task'] = Jawaban::convertPathIdToNama($data['task']);

        // check apakah di url ada nomor task dan cek apakah nomor task melebihi dari jumlah task yang ada ($noTask digunakan untuk memilih hasil task mana yang akan ditampilkan)
        if(!$noTask || $noTask > count($data['task'])){
            $noTask = 1;
        }
        $data['noTask'] = $noTask;

        // mengambil setiap id task
        $data['id_task'] = Task::select('id_task')->where('kode_pengujian', '=', $request->session()->get('kode_pengujian'))->get();
        // mengambil keseluruhan detail hasil
        $data['detailHasil'] = Jawaban::getDetailHasil($request->session()->get('kode_pengujian'));

        $data['akurasi'] = [];
        $data['directness'] = [];
        $data['timeToCompletion'] = [];

        if(!$data['detailHasil']->isEmpty()){
            $data['detailHasil'] = Jawaban::addNomorTask($data['detailHasil'], $data['id_task']);
            $data['detailHasil'] = Jawaban::convertPathIdToNama($data['detailHasil']);
    
            // mengambil perhitungan akurasi
            $data['akurasi'] = Jawaban::countHasil($data['detailHasil'], 'akurasi', $data['id_task']);
            // mengambil perhitungan directness
            $data['directness'] = Jawaban::countHasil($data['detailHasil'], 'directness', $data['id_task']);
            // mengambil keseluruhan timetocompletion
            $data['timeToCompletion'] = Jawaban::timeToCompletion($data['detailHasil'], $data['id_task']);
            $data['timeToCompletion'] = Tester::splitWaktu($data['timeToCompletion']);
        }

        $data['minimalPartisipan'] = Tester::select('minimal_partisipan')->where('kode_pengujian', $request->session()->get('kode_pengujian'))->first()['minimal_partisipan'];

        return view("tester.hasilPengujian", $data);
    }

    public function clearHasil(Request $request){
        $clear = Jawaban::where('kode_pengujian', $request->session()->get('kode_pengujian'))
                        ->delete();
        
        if($clear){
            return redirect('/tester/hasilPengujian/')->with('success', 'Hasil pengujian berhasil dihapus !');
        }

        Alert::error('Error','Terjadi kesalahan, hasil pengujian gagal untuk dihapus');
        return redirect('/tester/hasilPengujian/');
        
    }

    public function editProfil(Request $request){
        $data['title'] = "Edit Profil | Tree Testing";
        $data['dataUser'] = User::where('id_user','=',$request->session()->get('id_user'))->first();

        return view('tester.editProfil', $data);
    }

    public static function exportHasil(){
       return Excel::download(new Hasil, 'hasil.xlsx');
    }

    public function changeStatusExport(Request $request){
        return $request->session()->put('export', true);
    }

    public function selesaikanPengujian(Request $request, $keepTree = false){
        $kode_pengujian = $request->session()->get('kode_pengujian');
        // mengambil status pengujian dan minimal partisipan dari tabel tester
        $informasi = Tester::where('kode_pengujian', $kode_pengujian)
                                ->first();
        // menghitung jumlah partisipan yang telah mengikuti pengujian
        $countPartisipan = count(Jawaban::where('kode_pengujian', $request->session()->get('kode_pengujian'))->get());

        // jika pengujian sedang dilaksanakan, maka akan menampilkan error
        if($informasi['status_pengujian'] == 1){
            Alert::error('Gagal','Sedang dalam masa pelaksanaan pengujian !');
            return redirect('/tester/konfigurasiTree')->with('failed', 'Sedang dalam masa pelaksanaan, tidak dapat menyelesaikan pengujian');
        }
        
        // jika belum ada hasil
        if($countPartisipan == 0){
            Alert::error('Gagal !','Tidak dapat mengakhiri pengujian, Masih belum ada hasil')->persistent(true);
            return redirect('/tester/konfigurasiTree');
        }
        
        // jika belum mencapai minimal partisipan
        if($informasi['minimal_partisipan'] > $countPartisipan){
            Alert::error('Gagal !','Jumlah partisipan belum mencapai minimal yang telah ditentukan')->persistent(true);
            return redirect('/tester/konfigurasiTree');
    
        }

        // jika belum melakukan export
        if(!$request->session()->has('export')){
            Alert::error('Gagal !','Hasil pengujian harus diexport terlebih dahulu !')->persistent(true);
            return redirect('/tester/konfigurasiTree');
        }

        // menghapus session export
        $request->session()->forget('export');

        // menghapus semua task
        Task::where('kode_pengujian', $kode_pengujian)->delete();
        if(!$keepTree){
            // menghapus tree
            Tree::where('kode_pengujian', $kode_pengujian)->delete();
        }
        // menghapus informasi pengujian
        Tester::where('kode_pengujian', '=', $request->session()->get('kode_pengujian'))
                ->update([
                    'nama_website' => null,
                    'scope_pengujian' => null,
                    'profil_partisipan' => null,
                    'minimal_partisipan' => null,
                    'mulai_pengujian' => null,
                    'akhir_pengujian' => null,
                    'status_pengujian' => 0
                ]);
        $request->session()->forget('informasi_pengujian');


        return redirect('/tester/konfigurasiTree')->with('success', 'Pengujian berhasil diselesaikan');


    }

}
