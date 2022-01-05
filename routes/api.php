<?php

use App\Http\Controllers\GlobalAPIController;
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

Route::any("v1/{endpoint?}/{endpointId?}", [GlobalAPIController::class, 'processRequest']);

Route::any("rest/v1/{endpoint?}/{endpointId?}", [GlobalAPIController::class, 'processRestRequest']);

Route::any("context/v1", [GlobalAPIController::class, 'processContextRequest']);

// TODO: To utilize leader for perhaps building out a more complex pathing rest request
// Route::any("rest/v1/{class?}/{id?}/{path?}", [GlobalAPIController::class, 'processRestRequest'])->where('path', '.+');