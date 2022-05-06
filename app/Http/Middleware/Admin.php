<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = Auth::user();
        if(!$auth){
            return redirect()->route('dashboard');
        }

        $user = User::find($auth->id);
        if($user->role->name === 'admin'){
            return $next($request);
        }

        return redirect()->route('dashboard');
    }
}
