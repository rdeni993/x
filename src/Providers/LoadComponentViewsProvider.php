<?php 

namespace X\X\Providers;

use Illuminate\Support\ServiceProvider;

class LoadComponentViewsProvider extends ServiceProvider
{
    public function boot() : void
    {
        // X components will use views from
        // package views
        $this->loadViewsFrom( __DIR__ . '/../../views', 'x');
    }

    public function register() : void
    {
        
    }
}