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




Route::apiResource('tasks', 'TaskController');
Route::get('tasks/{leave}/doing', 'TaskController@doing');
Route::get('tasks/{leave}/done', 'TaskController@done');
Route::get('leaves/{leave}/approve', 'LeaveController@approve');
Route::get('leaves/{leave}/deny', 'LeaveController@deny');
Route::get('leaves/{leave}/approve', 'LeaveController@approve');
Route::get('leaves/{leave}/deny', 'LeaveController@deny');
Route::get('leaves/pending', 'LeaveController@pending');
Route::get('leaves/substitutable/{task}', 'LeaveController@substitutable');
Route::apiResource('leaves', 'LeaveController');
Route::post('login','Oauth@login');
Route::post('register', 'Oauth@register');
Route::get('user', 'UserController@current');
Route::get('users/supervisors', 'UserController@supervisors');
Route::get('users/subordinates', 'UserController@subordinates');
Route::get('users/administrators', 'UserController@administrators');
Route::resource('users','UserController');
Route::post('me/upload_image', 'UserController@uploadImage');
Route::get('me/tasks', 'UserController@tasks');
Route::get('me/leaves', 'UserController@leaves');
Route::get('me/substitution', 'UserController@substitution');
Route::get('me/supervisors', 'UserController@mySupervisors');
Route::get('me/subordinates', 'UserController@mySubordinates');
Route::post('me/changePassword','Oauth@changePassword')->middleware('auth');
Route::put('me/','UserController@update');
Route::get('me', 'UserController@me');
Route::get('opt/administrator', 'LineController@genOTPAdministrator');
Route::get('opt/supervisor', 'LineController@genOTPASupervisor');
Route::get('opt/subordinate', 'LineController@genOTPASubordinate');
Route::get('opt', 'LineController@genOTP');

Route::get('line/addUser/{lineID}', 'LineController@addUser');
Route::get('line/addTask/{lineID}', 'LineController@addTask');
Route::get('line/listTask/{lineID}', 'LineController@listTask');
Route::get('line/listLeave/{lineID}', 'LineController@listLeave');
Route::get('line/requestLeave/{lineID}', 'LineController@requestLeave');
Route::get('line/listTask/{lineID}', 'LineController@listTask');
Route::get('line/listLeave/{lineID}', 'LineController@listTask');


Route::group(['middleware' => 'auth:api'], function(){
    Route::post('get-details', 'PassportController@getDetails');
});