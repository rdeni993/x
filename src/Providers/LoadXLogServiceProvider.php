<?php 

namespace X\X\Providers;

use Illuminate\Support\ServiceProvider;

class LoadXLogServiceProvider extends ServiceProvider
{
    public function boot() : void 
    {
        // Log will appear in usual folder
        // where logs are stored
        app('config')->set('logging.channels.xLog', [
            'driver' => 'single',
            'path' => storage_path('logs/x-debug-log.log'),
            'level' => env('LOG_LEVEL', 'debug')
        ]);
    }

    public function register() : void
    {
    }
}