<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SedangPengujian
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
        if(session()->has('sedangPengujian') && $request->url() != '/partisipan/mulaiPengujian'){
            return back();
        }
        return $next($request);
    }
}
