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

Route::apiResources(['user' => 'API\UserController']);

Route::apiResources(['asset' => 'API\AssetController']);

Route::apiResources(['employee' => 'API\EmployeeController']);

Route::apiResources(['department' => 'API\DepartmentController']);

Route::apiResources(['position' => 'API\PositionController']);

Route::apiResources(['checkIn' => 'API\CheckInController']);

Route::apiResources(['checkOut' => 'API\CheckOutController']);