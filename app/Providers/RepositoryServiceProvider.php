<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\JurnalRepositoryInterface;
use App\Repositories\HistoryItemRepositoryInterface;
use App\Repositories\Eloquent\JurnalRepository;
use App\Repositories\Eloquent\HistoryItemRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(JurnalRepositoryInterface::class, JurnalRepository::class);
        $this->app->bind(HistoryItemRepositoryInterface::class, HistoryItemRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
