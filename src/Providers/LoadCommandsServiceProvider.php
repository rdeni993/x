<?php 

namespace X\X\Providers;

use Illuminate\Support\ServiceProvider;
use X\X\Commands\CreateRedirector;

class LoadCommandsServiceProvider extends ServiceProvider
{
    public function boot()
    {}

    public function register()
    {
        $this->commands([
            CreateRedirector::class
        ]);
    }
}