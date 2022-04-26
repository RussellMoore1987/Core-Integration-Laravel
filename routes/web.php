<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    // ! testing output **********************************************************************
    $project = App\Models\Project::first();
    $data = ['title' => 'test', 'description' => 'test', 'is_published'=> 1];
    $project->validateAndSave($data);
    dd($project);
    return view('welcome');
});
