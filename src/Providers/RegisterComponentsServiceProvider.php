<?php 

namespace X\X\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use X\X\Components\Input;
use X\X\Components\Table;
use X\X\Components\UnorderList;

class RegisterComponentsServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        // Simple unorder list component that
        // create bootstraped ul.
        Blade::component('unorder-list', UnorderList::class);
        Blade::component('table', Table::class);
        Blade::component('input', Input::class);
    }

    public function register() : void
    {
        
    }
}