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
         //if i only need this in one view
        //\View::composer('threads.create', function ($view){
        //    $view->with('channels', \App\Channel::all());
        //});
        //For all views
        \View::composer('*', function ($view){
            $view->with('channels', \App\Channel::all());
        });
        
        //for using in any view we can use, this work on page, but fail in test
        //becouse the database in not loaded yet in this point
        //\View::share('channels', Channel::all());
    }
}
