<?php 

namespace X\X\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LoadDirectivesServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot() : void
    {
        // Create target model,
        // This will be used in form to send
        // data to controller
        Blade::directive('TargetModel', function($modelName){
            return "<input type='hidden' name='xTargetModel' value='{$modelName}' autocomplete='off' />";
        });

        // Create action that will decide how to
        // handle request
        Blade::directive('Action', function($action){
            return "<input type='hidden' name='xAction' value='{$action}' autocomplete='off' />";
        });
    }

    /**
     * @return void
     */
    public function register() : void
    {
        
    }
}