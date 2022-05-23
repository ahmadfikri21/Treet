<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PartisipanController;
use App\Http\Controllers\TesterController;
use App\Http\Controllers\editProfilController;
use App\Http\Controllers\GenerateTree;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Landing Page
Route::get('/', [LandingController::class,'index'])->middleware('alreadyLoggedIn');
Route::get('/login', [LandingController::class,'login'])->middleware('alreadyLoggedIn');
Route::post('/login', [LandingController::class, 'loginProcess'])->middleware('alreadyLoggedIn');;
Route::get('/register', [LandingController::class,'register'])->middleware('alreadyLoggedIn');;
Route::post('/register', [LandingController::class, 'storeRegister'])->middleware('alreadyLoggedIn');;
Route::get('/logout', [LandingController::class,'logout']);

// ======================== Partisipan =================
// === Halaman Utama ===
Route::get('/partisipan', [PartisipanController::class,'index'])->middleware(['loggedIn','tester','SedangPengujian']);
Route::get('/partisipan/home', [PartisipanController::class,'index'])->middleware(['loggedIn','tester','SedangPengujian']);

// === Pengujian ===
Route::get('/partisipan/ikutiPengujian', [PartisipanController::class,'ikutPengujian'])->middleware(['loggedIn','tester','SedangPengujian']);
Route::get('/partisipan/mulaiPengujian', [PartisipanController::class,'mulaiPengujian'])->middleware(['loggedIn','tester']);
Route::post('/partisipan/storeJawaban', [PartisipanController::class,'storeJawaban'])->middleware(['loggedIn','tester']);
Route::get('/partisipan/selesaiPengujian', [PartisipanController::class,'selesaiPengujian'])->middleware(['loggedIn','tester']);

// === Percobaan Pengujian ===
Route::get('/partisipan/percobaanPengujian', [PartisipanController::class,'percobaanPengujian'])->middleware(['loggedIn','tester']);
Route::get('/partisipan/selesaiPengujian/{percobaan}', [PartisipanController::class,'selesaiPengujian'])->middleware(['loggedIn','tester']);

// === edit Profile ===
Route::get('/partisipan/editProfile', [PartisipanController::class,'editProfile'])->middleware(['loggedIn','tester','SedangPengujian']);
Route::post('/partisipan/prosesEditProfil', [editProfilController::class,'prosesEditProfil'])->middleware(['loggedIn']);
Route::post('/partisipan/gantiPassword', [editProfilController::class,'gantiPassword'])->middleware(['loggedIn']);

// ====================== Tester =======

// === Konfigurasi Pengujian ===
Route::get('/tester/konfigurasiTree', [TesterController::class,'index'])->middleware(['loggedIn','partisipan','cekInformasiPengujian']);
Route::post('/tester/editInformasi', [TesterController::class,'editInformasi'])->middleware(['loggedIn','partisipan']);
// konfigurasi Task
Route::post('/tester/storeTask', [TesterController::class,'storeTask'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
Route::post('/tester/editTask', [TesterController::class,'editTask'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
Route::get('/tester/hapusTask/{id_task}', [TesterController::class,'hapusTask'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
// Generate Tree
Route::get('/tester/generateTree', [GenerateTree::class,'generate'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
// Konfigurasi Tree
Route::post('/tester/tambahNode', [TesterController::class,'tambahNode'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
Route::post('/tester/editParentNode', [TesterController::class,'editParentNode'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
Route::get('/tester/hapusTree/{id_node}', [TesterController::class,'hapusTree'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
Route::get('/tester/deleteMultipleNode', [TesterController::class,'deleteMultipleNode'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
Route::post('/tester/editNode', [TesterController::class,'editNode'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
Route::post('/tester/setJawaban', [TesterController::class,'setJawaban'])->middleware(['loggedIn','partisipan','cekAdaJawaban']);
// informasiPengujian
Route::get('/tester/informasiPengujian', [TesterController::class,'informasiPengujian'])->middleware(['loggedIn','partisipan']);
Route::post('/tester/storeInformasiPengujian', [TesterController::class,'storeInformasiPengujian'])->middleware(['loggedIn','partisipan']);

// === Hasil pengujian ===
Route::get('/tester/hasilPengujian/', [TesterController::class,'hasilPengujian'])->middleware(['loggedIn','partisipan','cekInformasiPengujian']);
Route::get('/tester/hasilPengujian/{noTask}', [TesterController::class,'hasilPengujian'])->middleware(['loggedIn','partisipan','cekInformasiPengujian']);
Route::get('/tester/clearHasil/', [TesterController::class,'clearHasil'])->middleware(['loggedIn','partisipan','cekInformasiPengujian']);

// === Edit Profile ===
Route::get('/tester/editProfil', [TesterController::class,'editProfil'])->middleware(['loggedIn','partisipan']);
Route::post('/tester/prosesEditProfil', [editProfilController::class,'prosesEditProfil'])->middleware(['loggedIn']);
Route::post('/tester/gantiPassword', [editProfilController::class,'gantiPassword'])->middleware(['loggedIn']);

// =========== Export ===========
Route::get('/tester/selesaikanPengujian', [TesterController::class,'selesaikanPengujian'])->middleware(['loggedIn','partisipan']);
Route::get('/tester/selesaikanPengujian/{keepTree}', [TesterController::class,'selesaikanPengujian'])->middleware(['loggedIn','partisipan']);
Route::get('/tester/export', [TesterController::class,'exportHasil'])->middleware(['loggedIn','partisipan']);
Route::get('/tester/changeStatusExport', [TesterController::class,'changeStatusExport'])->middleware(['loggedIn','partisipan']);
