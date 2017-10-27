<?php

use Illuminate\Http\Request;

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

Route::get('items', 'ItemsController@index');
Route::get('items/potions', 'ItemsController@potions');
Route::get('items/ammunitions', 'ItemsController@ammunitions');
Route::get('items/runes', 'ItemsController@runes');
Route::get('items/amulets', 'ItemsController@amulets');
Route::get('items/rings', 'ItemsController@rings');
Route::get('items/tiles', 'ItemsController@tiles');
Route::get('items/search', 'ItemsController@search');
//Route::get('items/category', 'ItemsController@category');
//Route::get('items/category', 'ItemsController@saveItemCategory');


Route::post('waste/calculate', 'WasteController@calculate');
Route::get('waste/{waste}', 'WasteController@find');

Route::post('teamhunt/calculate', 'TeamHuntController@calculate');
Route::get('teamhunt/{hunt}', 'TeamHuntController@find');
Route::post('teamhunt/{hunt}/item/{item}', 'TeamHuntController@updateItem');
Route::post('teamhunt/{hunt}/teammate/{teammate}', 'TeamHuntController@updateTeammate');
Route::post('teamhunt/{hunt}/sign', 'TeamHuntController@signPassword');

Route::get('tiles', 'TileController@index');

Route::get('imbuements', 'ImbuementController@index');

Route::post('contact', 'PageController@sendContact');

//Route::get('properties', 'ItemPropsController@setProperties');

Route::get('mvp/{mvp}', 'MVPController@show');
Route::post('mvp', 'MVPController@calculate');

Route::get('creatures/search', 'CreatureController@search');
Route::get('creatures/{creature}', 'CreatureController@find');

/**
 * Authentication routes.
 */
Route::get('auth/token', 'AuthController@refreshToken');
Route::post('auth', 'AuthController@authenticate');


/**
 * PostController routes
 */
Route::get('news', 'PostController@news');
Route::get('news/list', 'PostController@newsList');

/**
 * ChangeController routes
 */
Route::get('change-log', 'ChangeController@getChanges');

/**
 * CategoriesController
 */
Route::get('categories', 'CategoriesController@index');
Route::get('categories/{category}/items', 'CategoriesController@items');

/**
 * All the routes in this group will need to send a Header
 * Authorization with a valide token, withou this the user will
 * not be authorized to access the route.
 */
Route::group(['middleware' => 'auth:api'], function () {
    
    /**
     * AuthController routes.
     */
    Route::post('auth/user', 'AuthController@getAuthenticatedUser');

    /**
     * NewsController routes
     */
    Route::get('posts', 'PostController@index');
    Route::get('posts/{post}', 'PostController@show');
    Route::post('posts', 'PostController@store');
    Route::patch('posts/{post}', 'PostController@update');
    Route::delete('posts/{post}', 'PostController@destroy');

    /**
     * ChangeController routes
     */
    Route::get('changes', 'ChangeController@index');
    Route::get('changes/{change}', 'ChangeController@show');
    Route::post('changes', 'ChangeController@store');
    Route::patch('changes/{change}', 'ChangeController@update');
    Route::delete('changes/{change}', 'ChangeController@destroy');

    /**
     * DamageProtection routes
     */
    Route::get('admin/damage-protection/items', 'DamageProtectionController@itemsByCategory');
    Route::post('admin/damage-protection/toggle/{item}/{category}', 'DamageProtectionController@syncItemCategory');
});