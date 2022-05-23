<?php

namespace App\Http\Middleware;

use App\Models\jawaban;
use App\Models\tester;
use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class cekAdaJawaban
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Handler untuk cek apakah ada hasil pada halaman haisl atau tidak
        if(Tester::select('status_pengujian')->where('status_pengujian', 1)->where('kode_pengujian', session()->get('kode_pengujian'))->first()){
            Alert::error('Error !','Pengujian sedang dilaksanakan, Tidak dapat melakukan perubahan pada task atau tree !')->persistent(true);
            return redirect('tester/konfigurasiTree');
        }
        
        if(Jawaban::where('kode_pengujian', session()->get('kode_pengujian'))->first()){
            Alert::error('Error !','Tidak dapat melakukan perubahan pada task atau tree karena terdapat hasil pada halaman hasil pengujian ! (Hapus hasil terlebih dahulu jika anda ingin melakukan perubahan)')->persistent(true);
            return redirect('tester/konfigurasiTree');
        }

        return $next($request);
    }
}
