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

Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login')->name('login.post');

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('admin.dashboard')->middleware('auth');
Route::get('/index', 'App\Http\Controllers\HomeController@index')->name('admin.index')->middleware('auth');
Route::get('/event', 'App\Http\Controllers\HomeController@event')->name('admin.event')->middleware('auth');
Route::get('/event/list', 'App\Http\Controllers\HomeController@event_list')->name('admin.event.list')->middleware('auth');
Route::get('/event/export', 'App\Http\Controllers\HomeController@event_export')->name('admin.event.export')->middleware('auth');


Route::get('/setting', 'App\Http\Controllers\HomeController@setting')->name('admin.setting')->middleware('auth');
Route::post('/setting/update', 'App\Http\Controllers\HomeController@setting_update')->name('admin.setting.update')->middleware('auth');
