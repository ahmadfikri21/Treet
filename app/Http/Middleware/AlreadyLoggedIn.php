<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AlreadyLoggedIn
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
        // jika telah login dan url adalah route yang ditetapkan dibawah maka akan dikembaikan ke halaman sebelumnya
        // if(session()->has('id_user') && (( url('/login') == $request->url() || url('/register') == $request->url() || url('/') == $request->url()))){
        //     return back();
        // }

        if(session()->has('id_user') && url('/login') == $request->url()){
            return  back();
        }
        
        if(session()->has('id_user') && url('/register') == $request->url()){
            return  back();
        }
        
        if(session()->has('id_user') && url('/') == $request->url()){
            return  back();
        }

        return $next($request);
    }
}
