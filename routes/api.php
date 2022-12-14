<?php

use App\Http\Controllers\CILApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// TODO: clean up notes

// ! Start here ****************************************************************** refine prosses, rename, refactor, UML*** read up on testing names, end-to-end, integration, unit, ect. ability to add images

Route::any("v1/{resource?}/{resourceId?}", [CILApiController::class, 'processRequest']); // generic route -> both REST and Context

Route::any("rest/v1/{resource?}/{resourceId?}", [CILApiController::class, 'processRestRequest']); // REST only route

Route::post("context/v1", [CILApiController::class, 'processContextRequest']); // Context only route

// TODO: To utilize leader for perhaps building out a more complex pathing rest request
// Route::any("rest/v1/{class?}/{id?}/{path?}", [CILApiController::class, 'processRestRequest'])->where('path', '.+');
// last endpoint valid then work up

// TODO: make sure api works with model hidden properties, not showing in the api********
// TODO: get different leaves of data, form, data, pagination data, all data


// db
// models
// composer install
// coreintegration.php
// full rest api

// Basic
// db validation
