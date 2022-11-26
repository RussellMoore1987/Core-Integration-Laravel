<?php

use App\CoreIntegrationApi\ContextApi\ContextRequestDataPrepper;
use App\CoreIntegrationApi\ContextApi\ContextRequestValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


use App\Models\Project;

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

// debugging
Route::any("projects", function () {
    // $r = new Request;
    // $c = new ContextRequestDataPrepper($r);
    // $v = new ContextRequestValidator($c);

    // ! failed
    // $v2 = App::make(ContextRequestValidator::class);
    // * good
    // $c = App::make(ContextRequestDataPrepper::class);

    // dd($v);
    return Project::all();
});

// ! Start here ****************************************************************** refine prosses, rename, refactor, UML*** read up on testing names, end-to-end, integration, unit, ect. ability to add images
// TODO: GlobalAPIController -> CoreIntegrationAPIController

Route::any("v1/{endpoint?}/{endpointId?}", [GlobalAPIController::class, 'processRequest']); // generic route -> both REST and Context

Route::any("rest/v1/{endpoint?}/{endpointId?}", [GlobalAPIController::class, 'processRestRequest']); // REST only route

Route::any("context/v1", [GlobalAPIController::class, 'processContextRequest']); // Context only route

// TODO: To utilize leader for perhaps building out a more complex pathing rest request
// Route::any("rest/v1/{class?}/{id?}/{path?}", [GlobalAPIController::class, 'processRestRequest'])->where('path', '.+');
// last endpoint valid then work up