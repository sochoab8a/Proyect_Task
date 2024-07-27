<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController; 

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('me', 'App\Http\Controllers\AuthController@me');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    
    //tasks
    



});




Route::group([
    'middleware' => 'api',
    'prefix' => 'tasks'
], function () {
    Route::post('newTask', [TaskController::class, 'store']); // Cambié 'new' a 'store' para seguir las convenciones RESTful
    Route::put('updateTask/{id}', [TaskController::class, 'update']); 
    Route::delete('deleteTask/{id}', [TaskController::class,'destroy']);         
    Route::get('getAllTask/{id}', [TaskController::class,'index']);                                                      // Cambié 'new' a 'store' para seguir las convenciones RESTful
    // Cambié 'new' a 'store' para seguir las convenciones RESTful

});
