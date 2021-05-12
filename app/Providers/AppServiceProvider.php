<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
//use Laravel\Dusk\DuskServiceProvider; //mine

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //mine to register Dusk testing
		/*
		if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
		*/
		
    }
}
