<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserSessionRepository;
use App\Repositories\IAppStorageInterface;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (Config::get('game.typeOfStorage') == 'session') {
            $this->app->bind(IAppStorageInterface::class, UserSessionRepository::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
