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
    return redirect('http://www.ollyxpic.com');
});

Route::get('/discord/friends', function () {
    event(new \App\Events\CharactersOnlineEvent());
});

Route::get('images/outfit/{name}/{sex?}', 'ImageController@loadOutfit');
Route::get('images/blob/{type}/{id}', 'ImageController@loadImage');
Route::get('images/blob/{type}/name/{name}', 'ImageController@loadImageByName');
Route::get('images/{directory}/{filename}/{ext}', 'ImageController@load');
Route::get('images/stackable', 'ImageController@stackables');

Route::get('images/export/npc', 'ImageController@exportNPC');
