<?php

use App\Http\Controllers\CoreIntegrationAPIController;
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

// ! Start here ****************************************************************** refine prosses, rename, refactor, UML*** read up on testing names, end-to-end, integration, unit, ect. ability to add images
// TODO: endpoint or resource

Route::any("v1/{endpoint?}/{endpointId?}", [CoreIntegrationAPIController::class, 'processRequest']); // generic route -> both REST and Context

Route::any("rest/v1/{endpoint?}/{endpointId?}", [CoreIntegrationAPIController::class, 'processRestRequest']); // REST only route

Route::any("context/v1", [CoreIntegrationAPIController::class, 'processContextRequest']); // Context only route

// TODO: To utilize leader for perhaps building out a more complex pathing rest request
// Route::any("rest/v1/{class?}/{id?}/{path?}", [CoreIntegrationAPIController::class, 'processRestRequest'])->where('path', '.+');
// last endpoint valid then work up