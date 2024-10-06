<?php 

namespace X\X\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Load X Project Service Provider
 * ==
 * 
 * Basic Provider that load all other important
 * components.
 */

class LoadXProjectServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot() : void
    {
        // Load Main routes from our own
        // web php
        $this->loadRoutesFrom(__DIR__ . "/../Routes/web.php");
    }

    public function register() : void 
    {
        //
    }
}