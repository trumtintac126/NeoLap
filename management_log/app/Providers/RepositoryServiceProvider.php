<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\Contracts\UserRepository::class, \App\Repositories\Eloquent\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\TablenameRepository::class, \App\Repositories\Eloquent\TablenameRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\RownameRepository::class, \App\Repositories\Eloquent\RownameRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\RowvalueRepository::class, \App\Repositories\Eloquent\RowvalueRepositoryEloquent::class);
        //:end-bindings:
    }
}
