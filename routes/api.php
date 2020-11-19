<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// List articles
Route::get('articles','ArticleController@index');

// List single article
Route::get('article/{id}','ArticleController@show');

// Create new article
Route::post('article','ArticleController@store');

// Update article
Route::put('article/{id}', 'ArticleController@store');

// Delete article
Route::delete('article/{id}', 'ArticleController@destroy');

// Authentification Routes
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');
Route::get('user', 'AuthController@getAuthUser');
Route::get('refresh', 'AuthController@refresh');


Route::group(['middleware' => ['admin']], function () {
    Route::get('admin', 'AuthController@restricted');
});


Route::get('allowed', 'AuthController@allowed');


//Employee Routes
Route::get('staffs', 'EmployeeController@index');
Route::get('attendance', 'EmployeeController@show');
Route::post('add-staff', 'EmployeeController@store');
Route::get('staff/{id}','EmployeeController@single');
Route::post('live-search', 'EmployeeController@search');
Route::post('month-records', 'EmployeeController@monthRecords');
Route::post('date-record', 'EmployeeController@dateRecord');


Route::get('test', 'EmployeeController@test');


// TEAM ROUTES
Route::get('teams', 'TeamController@index');
Route::post('team', 'TeamController@store');
Route::get('team-employees/{id}', 'TeamController@show');
Route::get('team-data', 'TeamController@count'); 
Route::get('team-dataa', 'TeamController@count'); //new routes


// ATTENDANCE ROUTES
Route::post('attendance-all', 'AttendanceController@bulkCreate');
Route::post('attendance', 'AttendanceController@create');

// MONTH ROUTES
Route::get('months', 'MonthController@index');
Route::post('month', 'MonthController@store');


// ROLE ROUTES
Route::get('roles', 'RoleController@index');
Route::post('role', 'RoleController@store');





