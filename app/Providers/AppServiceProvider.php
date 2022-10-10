<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public $isLogghed=0;
    public $idLogghed=0;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*if (Auth::check()) {
           $this->isLogghed=1;
           $this->idLogghed=Auth::id();
        }
        
        echo Auth::check();exit;
        View::share('isLogghed', $this->isLogghed);
        View::share('idLogghed', $this->idLogghed);*/
        
        
        
    }
}
