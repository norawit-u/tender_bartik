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
Route::get('user', 'UserController@current');
Route::post('me/upload_image', 'UserController@uploadImage');
Route::get('me/tasks', 'UserController@tasks');
Route::get('me/leaves', 'UserController@leaves');
Route::get('me/supervisors', 'UserController@supervisors');
Route::get('me/subordinates', 'UserController@subordinates');
Route::post('me/changePassword','Oauth@changePassword');
Route::get('me', 'UserController@me');
Route::get('opt/administrator', 'LineController@genOTPAdministrator');
Route::get('opt/supervisor', 'LineController@genOTPASupervisor');
Route::get('opt/subordinate', 'LineController@genOTPASubordinate');
Route::get('opt', 'LineController@genOTP');


Route::group(['middleware' => 'auth:api'], function(){
    Route::post('get-details', 'PassportController@getDetails');
});