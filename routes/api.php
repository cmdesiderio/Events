<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('App\Http\Controllers')->group(function() {
	
	Route::get('/events/{eventId}', 'EventController@show')->name('events.show');
	Route::get('/events', 'EventController@index')->name('events.index');
	Route::post('/events', 'EventController@store')->name('events.store');
	Route::put('/events/{eventId}', 'EventController@update')->name('events.update');
	Route::delete('/events/{eventId}', 'EventController@destroy')->name('events.destroy');
});