<?php

namespace App\Providers;

use App\Broadcasting\SocketIoBroadcaster;
use App\Events\TransactionStatusUpdated;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->make(BroadcastManager::class)->extend('socket_io', function ($app, $config) {
            return new SocketIoBroadcaster($config);
        });
    }
}
