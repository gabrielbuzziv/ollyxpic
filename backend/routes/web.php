<?php

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

Route::get('images/blob/{type}/{id}', 'ImageController@loadImage');
Route::get('images/{directory}/{filename}/{ext}', 'ImageController@load');
Route::get('images/stackable', 'ImageController@stackables');

Route::get('images/export/npc', 'ImageController@exportNPC');
