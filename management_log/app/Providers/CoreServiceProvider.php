<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 23/05/2019
 * Time: 16:26
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind(
            UserRepository::class
        );
        $this->app->bind(
            UserService::class
        );
    }
}