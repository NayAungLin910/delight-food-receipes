<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminOnlyRoute
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
        if(!Auth::check()){
            return redirect("/login")->with("error", "Please login first!");
        }else if(Auth::user()->email_verified_at == null){
            return Inertia::render("EmailVerify");
        }else if(Auth::user()->role == "0" || Auth::user()->role == "1"){
            return redirect("/")->with("info", "Unable to access route");
        }
        return $next($request);
    }
}
