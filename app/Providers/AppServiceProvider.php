<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;

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
        /* if i only need this in one view
        \View::composer('threads.create', function ($view){
            $view->with('channels', \App\Channel::all());
        });
        */
        //for using in any view we can usr
        \View::share('channels', Channel::all());
    }
}
