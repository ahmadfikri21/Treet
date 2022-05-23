<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Partisipan
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
        // jika role partisipan dan url konfigurasi tree, maka akan dikembalikan ke halaman home
        if($request->session()->get('role') == "Partisipan" && (url('/tester/konfigurasiTree') == $request->url())){
            return redirect('/partisipan/home');
        }

        return $next($request);
    }
}
