<?php

use Illuminate\Support\Facades\Route;
use X\X\Controllers\Create;
use X\X\Controllers\Delete;
use X\X\Controllers\Update;
use X\X\Middlewares\AdjustFormRequest;

/**
 * Group all routes 
 */

Route::middleware([
    'web', // Load settings related to web middleware
    'auth', // These routes will be protected using auth
    AdjustFormRequest::class
])
->prefix('x')
->group(function(){

    // Create route where user can send data
    // to create object
    Route::post('/create', [Create::class, 'index'])
    ->name('x.create');

    // Create route for update data
    Route::post('/update/{target}/{reference?}', [Update::class, 'index'])
    ->name('x.update');
});

// Delete methods doesnt require middleware
// for removing...
Route::middleware([
    'web',
    'auth'
])
->prefix('x')
->group(function(){

    // Remove item using link
    Route::get('/delete/{model}/{value}/{key?}', [Delete::class, 'index'])
    ->name('x.delete');

});
