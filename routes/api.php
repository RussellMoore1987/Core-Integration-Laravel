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

Route::any("v1/{class?}/{id?}/{path?}", [GlobalAPIController::class, 'processRequest'])->where('path', '.+');

Route::any("rest/v1/{class?}/{id?}/{path?}", [GlobalAPIController::class, 'processRestRequest'])->where('path', '.+');

Route::any("context/v1", [GlobalAPIController::class, 'processContextRequest']);
