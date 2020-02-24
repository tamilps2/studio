<?php

use Illuminate\Support\Facades\Auth;
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

Route::namespace('Studio')->prefix('studio')->group(function () {
    Route::prefix('posts')->group(function () {
        Route::get('/', 'PostController@index');
        Route::get('{username}', 'PostController@getByUsername');
        Route::get('{username}/{slug}', 'PostController@findByUsername')->middleware('Canvas\Http\Middleware\Session');
    });

    Route::prefix('tags')->group(function () {
        Route::get('/', 'TagController@index');
        Route::get('{slug}', 'TagController@getPostsForTag');
    });

    Route::prefix('topics')->group(function () {
        Route::get('/', 'TopicController@index');
        Route::get('{slug}', 'TopicController@getPostsForTopic');
    });

    Route::prefix('users')->group(function () {
        Route::get('{username}', 'UserController@show');
    });

    Route::get('/{view?}', 'ViewController')->where('view', '(.*)')->name('studio');
});
