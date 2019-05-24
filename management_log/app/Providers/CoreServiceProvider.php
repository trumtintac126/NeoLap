<?php

namespace App\Providers;

use App\Repositories\RepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\ServiceInterface;
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
            RepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            ServiceInterface::class,
            UserService::class
        );
    }
}