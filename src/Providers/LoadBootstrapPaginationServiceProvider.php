<?php 

namespace X\X\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class LoadBootstrapPaginationServiceProvider extends ServiceProvider
{
    public function boot() : void 
    {
        Paginator::useBootstrapFive();
    }

    public function register()
    {
        
    }
}