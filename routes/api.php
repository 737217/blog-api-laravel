<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/* Articles */

Route::get('article', 'ArticleController@index');
Route::get('article/{id}', 'ArticleController@show');
Route::get('article/category/{id}', 'ArticleController@showCategory');

Route::get('category', 'CategoryController@index');
Route::get('category-by-id/{id}', 'CategoryController@getCategoryById');
Route::get('category/{slug}', 'CategoryController@show');

Route::group(['middleware' => 'auth:api'], function() {
    Route::prefix('admin')->group(function () {
        Route::post('article', 'ArticleController@store');
        Route::put('article/{slug}', 'ArticleController@update');
        Route::delete('article/{id}', 'ArticleController@delete');

        Route::post('category', 'CategoryController@store');
        Route::put('category/{slug}', 'CategoryController@update');
        Route::delete('category/{id}', 'CategoryController@delete');

        Route::post('user/{id}', 'UserController@getUserById');
    });
});

/* .Articles */


/* User */

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('details', 'UserController@details');
});

/* .User */
