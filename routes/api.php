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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Route::get('/login', function () {
//    return "hello";
//});



Route::resource('users','UserController');
Route::apiResource('tasks', 'TaskController');
Route::get('leaves/{leave}/approve', 'LeaveController@approve');
Route::get('leaves/{leave}/deny', 'LeaveController@deny');
Route::apiResource('leaves', 'LeaveController');
Route::post('login','Oauth@login');
Route::post('register', 'Oauth@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('get-details', 'PassportController@getDetails');
});