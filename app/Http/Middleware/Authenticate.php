<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            $rotte=[];
            $rotte=$request->segments();
            if (in_array ('admin',$rotte)) {
                return route('adminLogin');
            }
            /*if ($request->routeIs('admin.*')) {
                return route('admin.login');
            }*/

            //return redirect('/login');
            return route('homepage');
        }
    }
}
