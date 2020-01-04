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

Route::get('/', 'HomeController')->name('home');

Auth::routes(['verify' => true]);

Route::get('tag/{slug}', 'TagController')->name('tag');
Route::get('topic/{slug}', 'TopicController')->name('topic');
Route::get('{username}', 'UserController')->name('user');
Route::get('{username}/{slug}', 'PostController')->middleware('Canvas\Http\Middleware\ViewThrottle')->name('post');
