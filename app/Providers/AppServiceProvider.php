<?php

namespace App\Providers;

use App\Helpers\Token;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(
            'App\Repositories\*'
        );

        $this->app->bind(
            'App\Services\*'
        );

        $this->app->bind('token_helper', function () {
            return new Token;
        });
    }
}
