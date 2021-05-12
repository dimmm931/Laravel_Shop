<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //'App\Events\Event' => [ 'App\Listeners\EventListener', ], //some default Event => Listener
		
		//here is mine defined Events => Listeners
		//'App\Events\SomeEventX'        => ['App\Listeners\EventListenerX', ], //on my SomeEventX run EventListenerX
		//'Illuminate\Auth\Events\Login' => ['App\Listeners\WriteCredentialsToLog',], //on login run WriteCredentialsToLog (event Login is a built Laravel event, we don't have to define it)
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
