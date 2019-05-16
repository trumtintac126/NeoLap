<?php

namespace Core\Providers;

use Core\Repositories\OrderRepository;
use Core\Repositories\RepositoryInterface;
use Core\Services\OrderService;
use Core\Services\ServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 16/05/2019
 * Time: 14:08
 */
class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
    }
    
    public function register()
    {
        $this->app->bind(
            RepositoryInterface::class,
            OrderRepository::class
        );
        $this->app->bind(
            ServiceInterface::class,
            OrderService::class
        );
    }

}