<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContactCrudController;

// --------------------------
// Custom Backpack Routes
// This route file is loaded automatically by Backpack\CRUD.
// Define routes inside the group below so they inherit the correct prefix & middleware.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    // Contact CRUD
    Route::crud('contact', ContactCrudController::class);
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
