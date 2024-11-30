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

// TODO: page and perpage have been causing some query issues, causing some things to be out of scope, look into and see what we want to do with them
// TODO: page parameter affects selecting data, and also making it not found /posts/?perPage=5&page=2&id=10*
// TODO: look into between not working (int) should include both ends of the range /posts/?perPage=5&id=15

// TODO: clean up notes

Route::any("v1/{resource?}/{resourceId?}", [CILApiController::class, 'processRestRequest']); // REST only route

Route::post("context/v1", [CILApiController::class, 'processContextRequest']); // Context only route

// TODO: To utilize leader for perhaps building out a more complex pathing rest request
// Route::any("rest/v1/{class?}/{id?}/{path?}", [CILApiController::class, 'processRestRequest'])->where('path', '.+');
// last endpoint valid then work up

// TODO: make sure api works with model hidden properties, not showing in the api********
// TODO: get different leaves of data, form, data, pagination data, all data

// TODO: make package version tests, so I know when I brake a version***


// db
// models
// composer install
// coreintegration.php
// full rest api
// full context api

// Basic
// db validation

// advanced
// instructions/settings/modelAddOns
