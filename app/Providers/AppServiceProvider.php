<?php

namespace App\Providers;

use App\Services\Server\Database\LogDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('log_db', function($app, $params) {
            return new LogDatabase($params['server']);
        });
    }
}
