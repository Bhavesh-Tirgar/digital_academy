<?php

namespace App\Providers;
use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
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
        Request::macro('maxUploadSize', function () {
            return 500 * 1024 * 1024; // 500MB
        });
    }
    
}
