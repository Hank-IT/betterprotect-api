<?php

namespace App\Providers;

use App\Services\Database\AmavisDatabase;
use App\Services\Database\LogDatabase;
use App\Services\Database\PostfixDatabase;
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
        $this->app->bind('postfix_db', function($app, $params) {
            return new PostfixDatabase($params['server']);
        });

        $this->app->bind('amavis_db', function($app, $params) {
            return new AmavisDatabase($params['server']);
        });

        $this->app->bind('log_db', function($app, $params) {
            return new LogDatabase($params['server']);
        });
    }
}
