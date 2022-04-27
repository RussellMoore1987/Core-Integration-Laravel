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
    // $project = App\Models\Project::first();
    $project = new App\Models\Project();
    // $data = ['title' => 'test', 'description' => 'test', 'is_published'=> 2.2]; // bad
    // $data = ['title' => 'test5678910', 'description' => 'test', 'is_published'=> 1, 'test' => 45678]; // good
    $data = ['title' => 'test5678910!!!!!', 'test' => 45678]; // bad missing description
    // $errors = $project->validateAndSave($data);
    dd($project, $errors);
    return view('welcome');
    
    // redirect
    // $project = new App\Models\Project();
    // $data = ['title' => 'test5678910!!!!!', 'test' => 45678]; // bad missing description
    // $return = $project->validateAndSave($data, '/test/redirect');
    // if ($return) {
    //     return $return;
    // }

    
});

Route::get('/test/redirect', function () {
    return view('testErrors');
});
