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
        if ($this->app->isLocal()){
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
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
            //Guarda los channels en cache y solo los recarga si hay nuevos
            $channels = \Cache::rememberForever('channels', function() {
                return Channel::all();
            });

            //$view->with('channels', \App\Channel::all());
            $view->with('channels', $channels);
        });
        
        //for using in any view we can use, this work on page, but fail in test
        //becouse the database in not loaded yet in this point
        //\View::share('channels', Channel::all());
    }
}
