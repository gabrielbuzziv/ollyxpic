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


Route::get('teamhunt/{hunt}', 'TeamHuntController@find');
Route::post('teamhunt/calculate', 'TeamHuntController@calculate');
Route::post('teamhunt/{hunt}/item/{item}', 'TeamHuntController@updateItem');
Route::post('teamhunt/{hunt}/teammate/{teammate}', 'TeamHuntController@updateTeammate');
Route::post('teamhunt/{hunt}/sign', 'TeamHuntController@signPassword');

Route::get('tiles', 'TileController@index');

Route::get('imbuements', 'ImbuementController@index');

Route::post('contact', 'PageController@sendContact');

Route::get('mvp/{mvp}', 'MVPController@show');
Route::post('mvp', 'MVPController@calculate');

/**
 * CreatureController routes.
 */
Route::get('creatures', 'CreatureController@index');
Route::get('creatures/{creature}', 'CreatureController@show');

/**
 * HuntingSpotsController routes.
 */
Route::post('hunting-spots', 'HuntingSpotController@store');

/**
 * HuntingCreatureController routes.
 */
Route::get('huntingcreature', 'HuntingCreatureController@index');
Route::get('huntingcreature/{huntingcreature}', 'HuntingCreatureController@show');

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
 * CategoryController
 */
Route::get('categories', 'CategoryController@usables');

/**
 * ItemController
 */
Route::get('items/{category}', 'ItemController@usables');

/**
 * WorldController routes
 */
Route::get('worlds', 'WorldController@index');
Route::get('worlds/{world}', 'WorldController@show');

/**
 * VocationController routes.
 */
Route::get('vocations', 'VocationController@index');

/**
 * SupplyController routes.
 */
Route::get('supplies', 'SupplyController@index');

/**
 * All the routes in this group will need to send a Header
 * Authorization with a valide token, withou this the user will
 * not be authorized to access the route.
 */
Route::group(['middleware' => 'auth:api', 'prefix' => 'admin'], function () {
    
    /**
     * AuthController routes.
     */
    Route::post('auth/user', 'AuthController@getAuthenticatedUser');

    /**
     * CategoryController routes.
     */
    Route::get('categories', 'CategoryController@index');
    Route::get('categories/{category}/show', 'CategoryController@show');
    Route::get('categories/{category}/usable', 'CategoryController@toggleUsable');
    Route::patch('categories/{category}', 'CategoryController@update');
    Route::post('categories/sync', 'CategoryController@syncronize');

    /**
     * ItemController routes.
     */
    Route::get('items/{category}', 'ItemController@index');
    Route::get('items/{item}/show', 'ItemController@show');
    Route::get('items/{item}/usable', 'ItemController@toggleUsable');
    Route::post('items/sync', 'ItemController@syncronize');
    Route::delete('items/{item}', 'ItemController@destroy');

    /**
     * ItemController routes.
     */
    Route::post('npcs/sync', 'NPCController@syncronize');

    /**
     * CreatureController routes.
     */
    Route::post('creatures/sync', 'CreatureController@syncronize');

    /**
     * TileController routes.
     */
    Route::post('tiles/sync', 'TileController@syncronize');

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
     * ImbuementController routes.
     */
    Route::get('imbuements', 'ImbuementController@index');
    Route::get('imbuements/{imbuement}', 'ImbuementController@show');
    Route::post('imbuements', 'ImbuementController@store');
    Route::patch('imbuements/{imbuement}', 'ImbuementController@update');

    /**
     * WorldController routes.
     */
    Route::get('worlds', 'WorldController@index');
    Route::get('worlds/{world}', 'WorldController@show');
    Route::post('worlds', 'WorldController@store');
    Route::patch('worlds/{world}', 'WorldController@update');

    /**
     * WorldCurrencyController
     */
    Route::post('worlds/{world}/currencies', 'WorldCurrencyController@store');
    Route::patch('worlds/currencies/{currency}', 'WorldCurrencyController@update');
    Route::delete('worlds/currencies/{currency}', 'WorldCurrencyController@destroy');
});
