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

//Route::get('/home', 'HomeController@index')->name('home');

# board routes
Route::get('/boards', 'BoardController@index')->name('board.index');
Route::post('/boards', 'BoardController@store')->name('board.store');

# task routes
Route::get('/{slug}', 'TaskListController@index')->name('task.index');
Route::get('/{slug}/testing', 'TaskListController@testing')->name('task.testing');
Route::post('/{slug}', 'TaskListController@store')->name('task.store');
//Route::get('/tasks/create', 'TaskListController@create')->name('task.create');
//Route::get('/boards/tasks/{task}', 'TaskListController@show')->name('task.show');
//Route::get('/tasks/{task}/edit', 'TaskListController@edit')->name('task.edit');
//Route::put('/tasks/{task}', 'TaskListController@update')->name('task.update');
//Route::get('/tasks/{task}/delete', 'TaskListController@destroy')->name('task.delete');

# task item routes
Route::post('/{slug}/items', 'TaskItemController@store')->name('item.store');
Route::put('/{slug}/items/{name}', 'TaskItemController@update')->name('item.update');
Route::post('/{slug}/status', 'TaskItemController@getStatus')->name('item.status');
Route::post('/{slug}/items/mark-done', 'TaskItemController@isDone')->name('item.isDone');
