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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

# task routes
Route::get('/tasks', 'TaskListController@index')->name('task.index');
Route::post('/tasks', 'TaskListController@store')->name('task.store');
//Route::get('/tasks/create', 'TaskListController@create')->name('task.create');
Route::get('/tasks/{task}', 'TaskListController@show')->name('task.show');
//Route::get('/tasks/{task}/edit', 'TaskListController@edit')->name('task.edit');
//Route::put('/tasks/{task}', 'TaskListController@update')->name('task.update');
//Route::get('/tasks/{task}/delete', 'TaskListController@destroy')->name('task.delete');
